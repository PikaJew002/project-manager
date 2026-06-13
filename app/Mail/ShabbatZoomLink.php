<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class ShabbatZoomLink extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public ?Carbon $date = null,
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $dateString = $this->date ? $this->date->timezone('America/New_York')->format('M j, Y') : now('America/New_York')->addDays(2)->format('M j, Y');

        return new Envelope(
            from: new Address('aaron@ironmthome.com', 'Aaron Eisenberg'),
            subject: 'Baruch Haba is inviting you to a scheduled Zoom meeting.  Topic: Shabbat Service Baruch Haba Time: ' . $dateString . ' 11:00 AM',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            text: 'email.zoom-link',
            with: [
                'dateString' => $this->date ? $this->date->timezone('America/New_York')->format('M j, Y') : now('America/New_York')->addDays(2)->format('M j, Y'),
            ],
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
