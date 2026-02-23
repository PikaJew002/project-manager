<?php

namespace App\Enums;

enum TaskPriorityOrder: int
{
    case Urgent = 1;
    case Important = 2;
    case Medium = 3;
    case Low = 4;

    public static function getOrder(TaskPriority $priority): static
    {
        return match ($priority) {
            TaskPriority::Urgent => self::Urgent,
            TaskPriority::Important => self::Important,
            TaskPriority::Medium => self::Medium,
            TaskPriority::Low => self::Low,
        };
    }
}
