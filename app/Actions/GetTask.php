<?php

namespace App\Actions;

use App\Models\Task;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class GetTask
{
    public function __invoke(Request $request, int $id)
    {
        $user = $request->user();
        $userProjects = $user->projects->pluck('id');
        $userProjectsAdmin = $user->administering->pluck('id');
        $projects = $userProjects->merge($userProjectsAdmin);

        Task::with([
            'projects' => function (Builder $query) use ($projects) {
                $query->whereIn('id', $projects->all());
            },
            'buckets' => function (Builder $query) use ($projects) {
                $query->whereHas('projects', function (Builder $query) use ($projects) {
                    $query->whereIn('id', $projects->all());
                });
            },
            'users' => function (Builder $query) use ($projects) {
                $query->whereHas('projects', function (Builder $query) use ($projects) {
                    $query->whereIn('id', $projects->all());
                });
            },
        ])->findOrFail($id);
    }
}
