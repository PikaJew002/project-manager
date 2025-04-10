<?php

namespace App\Enums;

use App\Models\Task;
use ErrorException;
use Illuminate\Database\Eloquent\Model;

enum TaskProgress: string
{
    case NotStarted = 'Not Started';
    case InProgress = 'In Progress';
    case Completed = 'Completed';

    public const DEFAULT = self::NotStarted;

    public static function getState(Task|array $task): static
    {
        if ($task instanceof Model) {
            $startedAt = $task->started_at;
            $completedAt = $task->completed_at;
        } else {
            $startedAt = $task['started_at'];
            $completedAt = $task['completed_at'];
        }

        if ($startedAt) {
            if ($completedAt) {
                return TaskProgress::Completed;
            }

            return TaskProgress::InProgress;
        }

        return TaskProgress::NotStarted;
    }

    public static function stateInitial(self|string|null $progress = null): array
    {
        $progress = $progress instanceof self ? $progress : self::tryFrom($progress);

        return match($progress) {
            self::NotStarted => [null, null],
            self::InProgress => [now(), null],
            self::Completed => [now(), now()],
            default => [null, null],
        };
    }

    public static function stateChange(Task $task, self $from, self $to): array
    {
        if ($from === self::NotStarted) {
            if ($to === self::InProgress) {
                return [now(), null];
            } else if ($to === self::Completed) {
                return [now(), now()];
            } else {
                return [$task->started_at, $task->completed_at];
            }
        } else if ($from === self::InProgress) {
            if ($to === self::NotStarted) {
                return [null, null];
            } else if ($to === self::Completed) {
                return [$task->started_at, now()];
            } else {
                return [$task->started_at, $task->completed_at];
            }
        } else if ($from === self::Completed) {
            if ($to === self::NotStarted) {
                return [null, null];
            } else if ($to === self::InProgress) {
                return [now(), null];
            } else {
                return [$task->started_at, $task->completed_at];
            }
        }

        throw new ErrorException('Not a valid state change from ' . $from->value . ' to ' . $to->value);
    }
}
