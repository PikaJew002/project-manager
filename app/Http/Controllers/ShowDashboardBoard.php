<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class ShowDashboardBoard
{
    public function __invoke(Request $request): Response
    {
        $groupedBy = $request->query('grouped_by', 'bucket');
        $user = $request->user();
        $projects = Project::with([
            'tasks' => function (BelongsToMany $query) use ($user) {
                $query->with([
                    'projects' => function (BelongsToMany $query) use ($user) {
                        $query->yourProjects($user);
                    },
                    'buckets' => function (BelongsToMany $query) use ($user) {
                        $query->yourBuckets($user);
                    },
                    'users' => function () {},
                    'tasks' => function () {},
                ])->assignedTo($user->id);
            },
            'buckets' => [
                'tasks' => function (BelongsToMany $query) use ($user) {
                    $query->with([
                        'projects' => function (BelongsToMany $query) use ($user) {
                            $query->yourProjects($user);
                        },
                        'buckets' => function (BelongsToMany $query) use ($user) {
                            $query->yourBuckets($user);
                        },
                        'users' => function () {},
                        'tasks' => function () {},
                    ])->assignedTo($user->id);
                },
            ],
        ])->yourProjects($user)->get();

        $projectsIds = $projects->pluck('id');

        $tasks = Task::with([
            'buckets' => function (BelongsToMany $query) use ($projectsIds) {
                $query->whereIn('project_id', $projectsIds)->with(['project']);
            },
            'users' => function (BelongsToMany $query) use ($user) {
                $query->whereRelation('organization', 'id', $user->organization_id);
            },
            'projects' => function (BelongsToMany $query) use ($projectsIds) {
                $projectsString = $projectsIds->join(',');
                $query->with('buckets')->whereRaw("`projects`.`id` in ({$projectsString})");
            },
            'tasks' => function () {},
        ])->whereRelation('users', DB::raw('`users`.`id`'), $user->id)
            ->whereDoesntHaveRelation('projects', function (Builder $query) use ($user) {
                $query->yourProjects($user);
            })->get();


        // grouped as: bucket, project

        return Inertia::render('DashboardBoard', [
            'your_projects' => ProjectResource::collection($projects),
            'your_tasks' => TaskResource::collection($tasks),
        ]);
    }
}
