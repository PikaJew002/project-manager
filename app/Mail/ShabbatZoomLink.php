<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ShabbatZoomLink extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $dateString = now('America/New_York')->addDays(2)->format('M j, Y');

        $recipients = env('BH_ZOOM_RECIPIENTS');
        $recipients = explode(',', $recipients);

        return new Envelope(
            from: new Address(env('BH_ZOOM_TO_ADDRESS'), env('BH_ZOOM_TO_NAME')),
            to: [env('BH_ZOOM_TO_ADDRESS')],
            bcc: $recipients,
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
                // assumes it's always sent on Thursdays at 5:00 PM EST
                'dateString' => now('America/New_York')->addDays(2)->format('M j, Y'),
                'link' => env('BH_ZOOM_LINK'),
                'meetingPassword' => env('BH_ZOOM_MEETING_PASSWORD'),
                'meetingId' => env('BH_ZOOM_MEETING_ID'),
                'mobileLink' => env('BH_ZOOM_MOBILE_LINK'),
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
