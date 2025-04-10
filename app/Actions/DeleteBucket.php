<?php

namespace App\Actions;

use App\Models\Bucket;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeleteBucket
{
    public function __invoke(Request $request, int $id): Response
    {
        $bucket = Bucket::with(['tasks', 'project'])->findOrFail($id);

        $taskIds = $bucket->tasks->pluck('id');
        $project = $bucket->project;

        $project->tasks()->attach($taskIds);
        $bucket->tasks()->detach($taskIds);

        $bucket->delete();

        return response()->noContent();
    }
}
