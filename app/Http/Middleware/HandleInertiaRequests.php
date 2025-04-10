<?php

namespace App\Http\Middleware;

use App\Http\Resources\BucketResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $yourProjects = auth()->check() ? Project::with('buckets.project')->yourProjects($request->user())->get() : collect();
        $navProjects = $yourProjects->filter(fn (Project $project) => $project->is_personal === false);
        $yourBuckets = $yourProjects->map(fn(Project $project): Collection => $project->buckets)->flatten();
        $users = auth()->check()
            ? User::with(['projects'])->where('organization_id', $request->user()->organization_id)->get()
            : collect();

        return [
            ...parent::share($request),
            ...$request->session()->get('inertia', []),
            'nav_projects' => $navProjects,
            'task_options' => auth()->check() ? [
                'your_projects' => ProjectResource::collection($yourProjects),
                'your_buckets' => BucketResource::collection($yourBuckets),
                'assignable_users' => UserResource::collection($users),
            ] : [],
            'auth' => [
                'user' => $request->user(),
            ],
        ];
    }
}
