<?php

namespace App\Http\Middleware;

use App\Http\Resources\BucketResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\UserResource;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $yourProjects = Auth::check() ? Project::with('buckets.project')->yourProjects($request->user())->get() : collect();
        $adminNavProjects = Auth::check() ? ($request->user()->is_admin ? Project::query()->where('organization_id', $request->user()->organization_id)->whereNot(function (Builder $query) use ($request) {
            $query->yourProjects($request->user());
        })->where('is_personal', false)->get() : collect()) : collect();
        $yourBuckets = $yourProjects->map(fn(Project $project): Collection => $project->buckets)->flatten();
        $users = Auth::check()
            ? User::with(['projects'])->where('organization_id', $request->user()->organization_id)->get()
            : collect();

        $subtasks = $request->session()->get('inertia.flash_data.task_id') ? TaskResource::collection(Task::query()->where('task_id', $request->session()->get('inertia.flash_data.task_id'))->with('users', 'buckets', 'projects', 'tasks', 'createdBy', 'task')->ordered()->get()) : null;

        return [
            ...parent::share($request),
            ...$request->session()->get('inertia', []),
            'nav_projects' => $yourProjects,
            'admin_nav_projects' => $adminNavProjects,
            'task_options' => Auth::check() ? [
                'your_projects' => ProjectResource::collection($yourProjects),
                'your_buckets' => BucketResource::collection($yourBuckets),
                'assignable_users' => UserResource::collection($users),
            ] : [],
            'subtasks' => $subtasks,
            'auth' => [
                'user' => $request->user(),
            ],
            'app_name' => config('app.name'),
        ];
    }
}
