<?php

namespace App\Enums;

enum TaskPriority: string
{
    case Urgent = 'Urgent';
    case Important = 'Important';
    case Medium = 'Medium';
    case Low = 'Low';

    public const DEFAULT = self::Medium;
}
