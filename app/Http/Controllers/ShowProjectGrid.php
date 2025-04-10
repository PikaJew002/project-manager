<?php

namespace App\Http\Controllers;

use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ShowProjectGrid
{
    public function __invoke(Request $request, int $id): Response
    {
        $project = Project::with(['users'])->findOrFail($id);

        $user = $request->user();
        $projectsIds = Project::yourProjects($user)->get()->pluck('id');

        $tasks = Task::with(['createdBy', 'users', 'tasks'])->with([
            'buckets' => function (BelongsToMany $query) use ($projectsIds) {
                $query->whereIn('project_id', $projectsIds)->with('tasks');
            },
            'projects' => function (BelongsToMany $query) use ($projectsIds) {
                $projectsString = $projectsIds->join(',');
                $query->whereRaw("`projects`.`id` in ({$projectsString})")->with('buckets.tasks');
            },
        ])->where(function (Builder $query) use ($project) {
            $query->whereRelation('projects', DB::raw('`projects`.`id`'), $project->id)
                ->orWhereHas('buckets', function (Builder $query) use ($project) {
                    $query->where('project_id', $project->id);
                });
        })->get();

        return Inertia::render('ProjectGrid', [
            'project' => $project,
            'project_tasks' => TaskResource::collection($tasks),
            'id' => $id,
        ]);
    }
}
