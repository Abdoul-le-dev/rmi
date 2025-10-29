<?php

namespace App\PaymentChannels\Drivers\FedaPay;

use FedaPay\FedaPay;
use FedaPay\Transaction;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\PaymentChannel;
use App\PaymentChannels\IChannel;
use Illuminate\Support\Facades\Log;
use App\PaymentChannels\BasePaymentChannel;

class Channel extends BasePaymentChannel implements IChannel
{
    protected $currency;
    protected $public_key;
    protected $secret_key;
    protected $order_session_key;

    protected array $credentialItems = [
        'public_key',
        'secret_key',
    ];

    /**
     * Channel constructor.
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->order_session_key = 'fedapay.payments.order_id';
        $this->currency = currency();
        $this->setCredentialItems($paymentChannel);
    }

    public function paymentRequest(Order $order)
    {
        try {
            set_time_limit(600);
            $user = $order->user;
            $price = $order->total_amount;
            $user_email = $order->user->email;
            // Log::info('Order info', [
            //     'order id' => $order->id,
            //     'order info' => $order,
            //     'user email' => $order->user->email,
            //     'datatype total_amount' => gettype($order->total_amount),
            //     'value total_amount' => $order->total_amount
            // ]);

            $amount = $price * 597.44;
            // $amount = $amount * 608.26;

            session()->put('fedapay_order_id', $order->id);
            // Log::info('Order history',['msg'=>$order]);

            $this->setFedaPay();

            $transaction = Transaction::create([
                'description' => 'Payment for order #' . $order->id,
                'amount' => (int) $amount,
                // 'callback_url' => 'http://localhost:8000/cart/checkout',
                'callback_url' => route('payment_verify', ['gateway' => 'FedaPay']),
                "currency" => [
                    "iso" => 'XOF'
                ],
                "customer" => [
                    "email" => $user_email
                ]

            ]);

            // Store the transaction ID in the session for later use
            session()->put('fedapay_transaction_id', $transaction->id);
            return $transaction->generateToken()->url;

        } catch (\Exception $exception) {
            Log::error('FedaPay Payment Request Error:', [
                'message' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]);
            throw $exception;
        }
    }

    public function verify(Request $request)
    {
    $input = $request->all();

    $user = auth()->user();

    // Retrieve the order ID from the session
    $orderId = session()->get('fedapay_order_id', null);
    session()->forget('fedapay_order_id');
    Log::info('orderId', ['orderId' => $orderId]);

    $transactionId = session()->get('fedapay_transaction_id', null);
    session()->forget('fedapay_transaction_id');
    Log::info('transaction_id', ['transaction_id' => $transactionId]);

    $order = Order::where('id', $orderId)
        ->where('user_id', $user->id)
        ->with('user')
        ->first();

    // Initialize Fedapay API
    $this->setFedapay();

    try {
        set_time_limit(600);
        // Fetch the transaction details from Fedapay
        $transaction = Transaction::retrieve($transactionId);

        if ($transaction->status == 'approved') {
            if (!empty($order)) {
                $order->update(['status' => Order::$paying]);
                $order->payment_method = Order::$paymentChannel;
                $order->save();
                return $order;
            }
        }
    } catch (\Exception $e) {
        Log::error('Fedapay verification failed', ['error' => $e->getMessage()]);
    }

    return $order;
    }

    public function setFedaPay()
    {
        FedaPay::setApiKey(env('FEDAPAY_SECRET_KEY'));
        FedaPay::setEnvironment(env('FEDAPAY_ENVIRONMENT'));
    }

    public function paymentData($order)
    {
        // FedaPay doesn't require store setup in the same way as PayDunya
    }
}
