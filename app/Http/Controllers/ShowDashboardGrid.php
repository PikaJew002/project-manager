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

class ShowDashboardGrid
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        $projectsIds = Project::yourProjects($user)->get()->pluck('id');
        $tasks = Task::with([
            'buckets' => function (BelongsToMany $query) use ($projectsIds) {
                $query->whereIn('project_id', $projectsIds)->with(['project']);
            },
            'users' => function (BelongsToMany $query) use ($user) {
                $query->whereRelation('organization', 'id', $user->organization_id);
            },
            'projects' => function (Builder $query) use ($projectsIds) {
                $projectsString = $projectsIds->join(',');
                $query->with('buckets')->whereRaw("`projects`.`id` in ({$projectsString})");
            },
            'tasks' => function () {},
        ])->whereRelation('users', DB::raw('`users`.`id`'), $user->id)->get();

        return Inertia::render('DashboardGrid', [
            'your_tasks' => TaskResource::collection(resource: $tasks),
        ]);
    }
}
