<?php

namespace App\Notifications;

use App\Models\Task;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

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
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)->subject("Tasks Due " . $this->type)->line('These tasks are due ' . strtolower($this->type) . ':');

        $list = '<ul>';

        foreach ($this->tasks as $task) {
            $assignedBy = User::firstWhere('id', $task->users->first(fn (User $user) => $user->id === $notifiable->id)?->pivot?->assigned_by);
            $description = $task->description ? '<li>Description: ' . $task->description . '</li>' : '';
            $list .= '<li>' . $task->name . ' is due on ' . $task->due_at?->tz($notifiable->timezone ?? config('app.timezone'))?->format("M j, Y \\a\\t g:i A") . '<ul>' . $description . '<li>Created By: ' . $task->createdBy->first_name . ' ' . $task->createdBy->last_name . '</li><li>Assigned By: ' . $assignedBy?->first_name . ' ' . $assignedBy?->last_name . '</li><li>Assigned To: ' . $task->users->map(fn (User $user) => "{$user->first_name} {$user->last_name}")->implode(', ') . '</li></ul></li>';
        }

        $list .= '</ul>';

        return $message->line(new HtmlString($list))->action('View Your Tasks Board', route('dashboard-board'))->action('View Your Tasks Grid', route('dashboard-grid'))->line("Thank you for using " . config('app.name') . "!");
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
