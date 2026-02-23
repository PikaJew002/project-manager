<?php

namespace App\Enums;

enum TaskProgressOrder: int
{
    case NotStarted = 2;
    case InProgress = 1;
    case Completed = 3;

    public static function getOrder(TaskProgress $progress): static
    {
        return match ($progress) {
            TaskProgress::NotStarted => self::NotStarted,
            TaskProgress::InProgress => self::InProgress,
            TaskProgress::Completed => self::Completed,
        };
    }
}
