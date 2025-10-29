<?php

namespace App\PaymentChannels\Drivers\Payu;

use App\Models\Order;
use App\Models\PaymentChannel;
use App\PaymentChannels\BasePaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Http\Request;
use Tzsk\Payu\Concerns\Attributes;
use Tzsk\Payu\Concerns\Customer;
use Tzsk\Payu\Concerns\Transaction;
use Tzsk\Payu\Facades\Payu;
use Tzsk\Payu\Gateway\Gateway;

class Channel extends BasePaymentChannel implements IChannel
{
    protected $currency;
    protected $test_mode;
    protected $default_gateway;
    protected $money_key;
    protected $money_salt;
    protected $money_auth;


    protected array $credentialItems = [
        'money_key',
        'money_salt',
        'money_auth',
    ];

    /**
     * Channel constructor.
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->currency = currency();
        $this->default_gateway = "money";

        $this->setCredentialItems($paymentChannel);
    }

    private function handleConfigs()
    {
        \Config::set('payu.default', $this->default_gateway);
        \Config::set('payu.gateways.money.mode', $this->test_mode ? Gateway::TEST_MODE : Gateway::LIVE_MODE);
        \Config::set('payu.gateways.money.key', $this->money_key);
        \Config::set('payu.gateways.money.salt', $this->money_salt);
        \Config::set('payu.gateways.money.auth', $this->money_auth);
    }

    public function paymentRequest(Order $order)
    {
        $this->handleConfigs();

        $customer = Customer::make()
            ->firstName($order->user->full_name)
            ->email(!empty($order->user->email) ? $order->user->email : 'john@example.com');

        $attributes = Attributes::make()
            ->udf1($order->id)
            ->udf2($order->user->id);

        $transaction = Transaction::make()
            ->charge($this->makeAmountByCurrency($order->total_amount, $this->currency))
            ->for('Product')
            ->with($attributes)
            ->to($customer);

        return Payu::initiate($transaction)->redirect(route('payment_verify', ['gateway' => 'Payu']));
    }

    public function verify(Request $request)
    {
        $this->handleConfigs();

        $transaction = Payu::capture();

        $order_id = $transaction->response('udf1');
        $user_id = $transaction->response('udf2');

        $order = Order::where('id', $order_id)
            ->where('user_id', $user_id)
            ->first();

        if (!empty($order)) {
            if ($transaction->successful()) {
                $order->update(['status' => Order::$paying]);
            } elseif ($transaction->failed()) {
                $order->update(['status' => Order::$fail]);
            } elseif ($transaction->pending()) {
                $order->update(['status' => Order::$pending]);
            }
        }

        return $order;
    }
}
