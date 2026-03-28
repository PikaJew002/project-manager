<?php

namespace App\Actions;

use App\Enums\TaskPriority;
use App\Enums\TaskProgress;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class UpdateSubtask
{
    public function __invoke(Request $request, int $id): RedirectResponse
    {
        $subtask = Task::with(['users', 'buckets', 'projects'])->findOrFail($id);

        $fields = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:10000'],
            'due_at' => ['nullable', 'date'],
            'status' => ['nullable', Rule::enum(TaskProgress::class)],
            'priority' => ['nullable', Rule::enum(TaskPriority::class)],
            'projects' => ['nullable', 'array'],
            'projects.*' => ['integer', Rule::exists('projects', 'id')],
            'buckets' => ['nullable', 'array'],
            'buckets.*' => ['integer', Rule::exists('buckets', 'id')],
            'assigned_to' => ['nullable', 'array'],
            'assigned_to.*' => ['integer', Rule::exists('users', 'id')],
        ]);

        $subtask->updateFrom($fields, $request->user(), $request->header('X-Timezone'));

        Inertia::flash([
            'subtasks' => TaskResource::collection(Task::where('task_id', $subtask->task_id)->with('users', 'buckets', 'projects', 'tasks', 'createdBy', 'task')->ordered()->get()),
        ]);

        return response()->redirectTo($request->header('X-From'));
    }
}
