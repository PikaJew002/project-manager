<?php

namespace App\Actions;

use App\Enums\TaskPriority;
use App\Enums\TaskProgress;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CreatePersonalTask
{
    public function __invoke(Request $request): RedirectResponse
    {
        $fields = $request->validate([
            'task_id' => ['nullable', 'integer', Rule::exists('tasks', 'id')],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_at' => ['nullable', 'date'],
            'status' => ['nullable', Rule::enum(TaskProgress::class)],
            'priority' => ['nullable', Rule::enum(TaskPriority::class)],
            'projects' => ['array'],
            'projects.*' => ['integer', Rule::exists('projects', 'id')],
            'buckets' => ['nullable', 'array'],
            'buckets.*' => ['integer', Rule::exists('buckets', 'id')],
            'assigned_to' => ['nullable', 'array'],
            'assigned_to.*' => ['integer', Rule::exists('users', 'id')],
        ]);

        Task::createFrom($fields, $request->user());

        return response()->redirectTo($request->header('X-From'));
    }
}
