<?php

namespace App\PaymentChannels\Drivers\Paydunia;

use Paydunya\Setup;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\PaymentChannel;
use App\PaymentChannels\IChannel;
use App\PaymentChannels\BasePaymentChannel;

// require 'vendor/paydunya/src/Paydunya.php';


class Channel extends BasePaymentChannel implements IChannel
{
    protected $currency;
    protected $master_key;
    protected $public_key;
    protected $private_key;
    protected $token;
    protected $test_mode;
    protected $order_session_key;

    protected array $credentialItems = [
        'master_key',
        'public_key',
        'private_key',
        'token'
    ];
    
    /**
     * Channel constructor.
     * @param PaymentChannel $paymentChannel
     */
    public function __construct(PaymentChannel $paymentChannel)
    {
        $this->order_session_key = 'paydunia.payments.order_id';
        $this->currency = currency();
        $this->setCredentialItems($paymentChannel);
    }

    public function paymentRequest(Order $order)
    {
        $user = $order->user;
        $price = $this->makeAmountByCurrency($order->total_amount, $this->currency);

        $price = $price * 597.44;

        
        $generalSettings = getGeneralSettings();
        $currency = currency();
        
        //sufian
        session()->put('paydunya_order_id', $order->id);

        $this->setPaydunia();
        $this->paymentData($order);
        $invoice = new \Paydunya\Checkout\CheckoutInvoice();
        $invoice->addItem("Chaussures Croco", 1, $price, $price, "Chaussures faites en peau de crocrodile authentique qui chasse la pauvreté");
        $invoice->setTotalAmount($price);

        if ($invoice->create()) {
            return $invoice->getInvoiceUrl();
        }

    }

    private function makeCallbackUrl($order, $status)
    {
        \Log::info('callback', [$order, $status]);

    }


    public function verify(Request $request)
    {
        $input = $request->all();

        $user = auth()->user();
        
        //sufian
        $orderId = session()->get('paydunya_order_id', null);
        session()->forget('paydunya_order_id');
        
        
        // $orderId = $request->input('order_id');
        $token = $request->input('token');

        $order = Order::where('id', $orderId)
            ->where('user_id', $user->id)
            ->with('user')
            ->first();

        $this->setPaydunia();
        $invoice = new \Paydunya\Checkout\CheckoutInvoice();

        if ($invoice->confirm($token)) {
    
            if ($invoice->getStatus() == 'completed') {

                if (!empty($order)) {
                    $order->update(['status' => Order::$paying]);    
                    $order->payment_method = Order::$paymentChannel;
                    $order->save();
                    return $order;
                }
            }
        }

        return $order;
    }

    public function setPaydunia()
    {
        
        \Paydunya\Setup::setMasterKey($this->master_key);
        \Paydunya\Setup::setPublicKey($this->public_key);
        \Paydunya\Setup::setPrivateKey($this->private_key);
        \Paydunya\Setup::setToken($this->token);
        \Paydunya\Setup::setMode($this->test_mode ? 'test' : 'live');
    }


    public function paymentData($order)
    {
        $generalSettings = getGeneralSettings();
        $user = $order->user;
        
        // dd($order, $generalSettings);
        
        //Configuration des informations de votre service/entreprise
        \Paydunya\Checkout\Store::setName($generalSettings['site_name'] . ' payment'); // Seul le nom est requis
        \Paydunya\Checkout\Store::setTagline("L'élégance n'a pas de prix");
        \Paydunya\Checkout\Store::setPhoneNumber($user->mobile);
        \Paydunya\Checkout\Store::setPostalAddress($user->address);
        \Paydunya\Checkout\Store::setWebsiteUrl("http://www.chez-sandra.sn");
        \Paydunya\Checkout\Store::setLogoUrl("http://www.chez-sandra.sn/logo.png");
        \Paydunya\Checkout\Store::setCallbackUrl(url('/payments/verify/Paydunia'));
        \Paydunya\Checkout\Store::setReturnUrl(url('/payments/verify/Paydunia'));
        \Paydunya\Checkout\Store::setCancelUrl(url('/payments/verify/Paydunia'));
    }

}
