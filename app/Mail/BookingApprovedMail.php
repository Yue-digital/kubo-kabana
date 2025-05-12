<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingApprovedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $booking;

    /**
     * Create a new message instance.
     */
    public function __construct($booking, $checkoutUrl)
    {
        //
        $this->checkoutUrl = $checkoutUrl;
        $this->booking = $booking;

    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Booking Approved Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-approved',
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

    public function build()
    {
        return $this->subject('Your Booking is Approved')
                    ->view('emails.booking-approved')
                    ->with([
                        'checkoutUrl' => $this->checkoutUrl,
                    ]);
    }
}
