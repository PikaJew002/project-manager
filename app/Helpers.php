<?php

namespace App;

use App\Models\Task;

class Helpers
{
    public static function findTaskRecursive(Task $haystack, Task $needle): bool
    {
        // dump($haystack->id, $needle->id);
        if ($haystack->id === $needle->id) {
            return true;
        }

        foreach ($haystack->tasks as $t) {
            if (static::findTaskRecursive($t, $needle)) {
                return true;
            }
        }

        return false;
    }
}
