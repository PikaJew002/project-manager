<?php

namespace App\Notifications;

use App\Mail\TasksNotification;
use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class TasksDue extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Collection $tasks,
        public string $type = 'Today' // or 'This Week'
    ) {
        $this->tasks = $this->tasks->map(function (Task $task) {
            $task->loadMissing(['createdBy', 'users']);

            return $task;
        })->sortBy('due_at');
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
        $emailTasks = $this->tasks->map(fn(Task $task) => $task->toEmail($notifiable));

        return (new TasksNotification(
            tasks: $emailTasks,
            subjectText: 'Tasks Due ' . $this->type,
            introText: 'These tasks are due ' . strtolower($this->type) . '.',
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
