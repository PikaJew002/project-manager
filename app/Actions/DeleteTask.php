<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteTask
{
    public function __invoke(Request $request, int $id): RedirectResponse|Response
    {
        $task = Task::with('tasks')->findOrFail($id);
        if($task->deleteTasks()) {
            return response()->redirectTo($request->header('X-From'));;
        }

        return response(null, 500)->noContent();
    }
}
