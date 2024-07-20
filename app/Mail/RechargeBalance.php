<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RechargeBalance extends Mailable
{
    use Queueable, SerializesModels;
    public $balance;
    public $employee;
    public $shippingPoint;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $balance, $employee, $shippingPoint)
    {
        $this->name = $name;
        $this->balance = $balance;
        $this->employee = $employee;
        $this->shippingPoint = $shippingPoint;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recharge Balance',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.RechargeBalance',
            with: [
                'balance' => $this->balance,
                'employee' => $this->employee,
                'shippingPoint' => $this->shippingPoint,
                'name' => $this->name,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
