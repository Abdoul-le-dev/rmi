<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class PaymentSuccessMail extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public $carts;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $carts)
    {
        //
        $this->order = $order;
        $this->carts = $carts;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Payment Successful')
                    ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                    ->view('web.default.emails.payment_success')
                    ->with([
                        'order' => $this->order,
                        'carts' => $this->carts,
                    ]);
    }
}
