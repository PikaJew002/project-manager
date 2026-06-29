<?php

namespace App\Notifications;

use App\Mail\TasksNotification;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
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
    public function toMail(object $notifiable): Mailable
    {
        $emailTasks = collect([$this->task->toEmail($notifiable)]);

        return (new TasksNotification(
            tasks: $emailTasks,
            subjectText: 'Task Assigned: ' . $this->task->name,
            introText: 'You have been assigned to this task.',
            ctaText: 'View Your Tasks Grid',
            ctaUrl: route('dashboard-grid'),
        ))->to($notifiable->email, $notifiable->first_name . ' ' . $notifiable->last_name);
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
