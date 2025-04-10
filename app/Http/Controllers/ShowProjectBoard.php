<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ShowProjectBoard
{
    public function __invoke(Request $request, int $id): Response
    {
        $user = $request->user();
        $project = Project::with(['users'])->with([
            'buckets' => [
                'tasks' => [
                    'projects' => function (BelongsToMany $query) use ($user) {
                        $query->yourProjects($user);
                    },
                    'buckets' => function (BelongsToMany $query) use ($user) {
                        $query->yourBuckets($user);
                    },
                    'users' => function () {},
                    'tasks' => function () {},
                ],
            ],
            'tasks' => [
                'projects' => function (BelongsToMany $query) use ($user) {
                    $query->yourProjects($user);
                },
                'buckets' => function (BelongsToMany $query) use ($user) {
                    $query->yourBuckets($user);
                },
                'users' => function () {},
                'tasks' => function () {},
            ],
        ])->findOrFail($id);

        return Inertia::render('ProjectBoard', [
            'project' => new ProjectResource($project),
            'id' => $id,
        ]);
    }
}
