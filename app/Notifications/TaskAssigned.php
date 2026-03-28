<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAssigned extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Task $task
    ) {
        $this->task->loadMissing(['createdBy', 'users']);
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
        $assignedBy = User::find($this->task->users->first(function (User $user) use ($notifiable) {
            return $user->id === $notifiable->id;
        })?->pivot?->assigned_by);

        return (new MailMessage)
                ->subject("Task Assigned: {$this->task->name}")
                ->line("You have been assigned to the task {$this->task->name} by {$assignedBy?->first_name} {$assignedBy?->last_name}")
                ->line("The task {$this->task->name} is due on {$this->task->due_at?->tz($notifiable->timezone ?? config('app.timezone'))?->format("M j, Y \\a\\t g:i A")}")
                ->when($this->task->description, function (MailMessage $message) {
                    $message->line("Description:")->line($this->task->description);
                })
                ->action('View Your Tasks Board', route('dashboard-board'))
                ->action('View Your Tasks Grid', route('dashboard-grid'))
                ->line("Thank you for using " . config('app.name') . "!");
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
