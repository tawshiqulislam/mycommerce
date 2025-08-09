<?php

namespace App\Mail;

use App\Models\Order; // Correct import
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $status;

    public function __construct(Order $order, $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function build()
    {
        $subject = "Order " . ($this->status === 'pending' ? 'confirmed' : $this->status) . ": #{$this->order->code}";
        return $this->subject($subject)
            ->view('email.order_status')
            ->with(['order' => $this->order, 'status' => $this->status]);
    }
}