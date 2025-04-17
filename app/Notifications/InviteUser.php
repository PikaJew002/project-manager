<?php

namespace App\Notifications;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteUser extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Invite $invite
    ) {
        $this->invite->loadMissing(['organization', 'invitedBy']);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject("You've been invited to a Project Manager Organization {$this->invite->organization->name}")
                    ->line("You've been invited to {$this->invite->organization->name} by {$this->invite->invitedBy->first_name} {$this->invite->invitedBy->last_name}!")
                    ->action('Respond To Invite', route('register-invite', $this->invite->token))
                    ->line("P.S.: If you don't respond to an invite (accept or decline), you can't be invited to another organization in Project Manager.");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
