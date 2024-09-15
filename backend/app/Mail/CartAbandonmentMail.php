<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CartAbandonmentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $cart;

    public function __construct($user, $cart)
    {
        $this->user = $user;
        $this->cart = $cart;
    }

    public function build()
    {
        return $this->view('emails.cart_abandonment')
            ->with([
                'user' => $this->user,
                'cart' => $this->cart,
            ]);
    }
}
