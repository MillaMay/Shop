<?php

namespace App\Mail;

use App\Models\Admin\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendSubscriptionMessage extends Mailable
{
    use Queueable, SerializesModels;

    protected $product;

    /**
     * Create a new message instance.
     *
     * @param Product $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.subscription', ['product' => $this->product]);
        // 'product' => $this->product] - тут $product передается так, потому что достается не из текущего метода,
        // а из поля данного класса
    }
}
