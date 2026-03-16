<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskProgress;
use App\Enums\TaskPriorityOrder;
use App\Enums\TaskProgressOrder;
use App\Notifications\TaskAssigned;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class Task extends Model
{
    /** @use HasFactory<\Database\Factories\TaskFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'priority' => TaskPriority::class,
            'due_at' => 'datetime',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    /**
     * Get the task progress.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => TaskProgress::getState($attributes),
        );
    }

    /**
     * Get the task status order.
     */
    protected function statusOrder(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => TaskProgressOrder::getOrder(TaskProgress::getState($attributes))->value,
        );
    }

    /**
     * Get the task priority order.
     */
    protected function priorityOrder(): Attribute
    {
        return Attribute::make(
            get: fn(mixed $value, array $attributes) => TaskPriorityOrder::getOrder(TaskPriority::tryFrom($attributes['priority']))->value,
        );
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('assigned_by', 'notified_at')->withTimestamps();
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withTimestamps();
    }

    public function buckets(): BelongsToMany
    {
        return $this->belongsToMany(Bucket::class)->withTimestamps();
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(self::class)->with([
            'projects' => function (BelongsToMany $query) {
                $query->yourProjects(request()->user());
            },
            'buckets' => function (BelongsToMany $query) {
                $query->yourBuckets(request()->user());
            },
            'tasks' => function () {},
            'users' => function () {},
        ]);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(self::class);
    }

    public function scopeCreatedByScope(Builder $query, int $userId): void
    {
        $query->where('created_by', $userId);
    }

    public function scopeAssignedTo(Builder $query, int $userId): void
    {
        $query->whereRelation('users', DB::raw('`users`.`id`'), $userId);
    }

    public static function projectTasks(int $projectId, Collection $projectsIds): EloquentCollection
    {
        return static::with([
            'projects' => function (BelongsToMany $query) use ($projectsIds) {
                $projectsString = $projectsIds->join(',');
                $query->whereRaw("`projects`.`id` in ({$projectsString})")->with('buckets.tasks');
            },
            'buckets' => function (BelongsToMany $query) use ($projectsIds) {
                $query->whereIn('project_id', $projectsIds)->with('tasks');
            },
            'tasks' => function () {},
            'users' => function () {},
        ])->where(function (Builder $query) use ($projectId) {
            $query->whereRelation('projects', DB::raw('`projects`.`id`'), $projectId)->orWhereHas('buckets', function (Builder $query) use ($projectId) {
                $query->where('project_id', $projectId);
            });
        })->get();
    }

    public static function createFrom(array $fields, User $user, ?string $timezone = null): static
    {
        $userId = $user->id;
        $timezone = $timezone ?? $user->timezone ?? config('app.timezone');

        [$startedAt, $completedAt] = TaskProgress::stateInitial($fields['status'] ?? TaskProgress::DEFAULT);

        if (!empty($fields['due_at'])) {
            $dueAt = Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $fields['due_at'],
                $timezone
            )->tz(config('app.timezone'));
        } else {
            $dueAt = null;
        }

        $task = Task::create([
            'task_id' => $fields['task_id'] ?? null,
            'created_by' => $userId,
            'name' => $fields['name'],
            'description' => $fields['description'] ?? null,
            'due_at' => $dueAt,
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
            'priority' => $fields['priority'] ?? TaskPriority::DEFAULT,
        ]);

        $assignedTo = collect($fields['assigned_to'] ?? []);

        if ($assignedTo->isNotEmpty()) {
            $task->users()->attach(id: $assignedTo->mapWithKeys(fn (int $id) => [$id => ['assigned_by' => $userId]]));
            $assignedToUsers = User::whereIn('id', $assignedTo)->get();
            Notification::send($assignedToUsers, new TaskAssigned($task));
        }

        $bucketIds = collect($fields['buckets'] ?? []);
        $projectIds = $task->normalizeProjects(collect($fields['projects'] ?? []), $bucketIds);

        if ($projectIds->isNotEmpty()) {
            $task->projects()->attach($projectIds);
        }

        if ($bucketIds->isNotEmpty()) {
            $task->buckets()->attach($bucketIds);
        }

        $task->load(['projects', 'buckets', 'users', 'tasks']);

        return $task;
    }

    public function updateFrom(array $fields, User $user, ?string $timezone = null): static
    {
        $userId = $user->id;
        $timezone = $timezone ?? $user->timezone ?? config('app.timezone');

        $toUpdate = [];

        if (!empty($fields['name'])) {
            $toUpdate['name'] = $fields['name'];
        }
        if (!empty($fields['description'])) {
            $toUpdate['description'] = $fields['description'];
        }
        if (!empty($fields['due_at'])) {
            $toUpdate['due_at'] = Carbon::createFromFormat(
                'Y-m-d\TH:i',
                $fields['due_at'],
                $timezone
            )->tz(config('app.timezone'));
        }
        if (!empty($fields['priority'])) {
            $toUpdate['priority'] = $fields['priority'];
        }
        if (!empty($fields['status'])) {
            [$startedAt, $completedAt] = TaskProgress::stateChange($this, $this->status, TaskProgress::tryFrom($fields['status'] ?? TaskProgress::DEFAULT));
            $toUpdate['started_at'] = $startedAt;
            $toUpdate['completed_at'] = $completedAt;
        }

        $this->update($toUpdate);

        if (!empty($fields['assigned_to'])) {
            $assignedTo = collect($fields['assigned_to'] ?? []);
            $currentUsers = $this->users->pluck('id');

            // attach users not found on the current model, but are in the request
            $assignedToDiff = $assignedTo->diff($currentUsers)->values();
            if ($assignedToDiff->isNotEmpty()) {
                $this->users()->attach($assignedToDiff->mapWithKeys(fn(int $id) => [$id => ['assigned_by' => $userId]]));

                $assignedToUsers = User::whereIn('id', $assignedToDiff)->get();
                Notification::send($assignedToUsers, new TaskAssigned($this));
            }

            // detach users not found in the request, but are on the current model
            $currentUsersToDiff = $currentUsers->diff($assignedTo)->values();
            if ($currentUsersToDiff->isNotEmpty()) {
                $this->users()->detach($currentUsersToDiff);
            }
        }

        if (!empty($fields['buckets']) && !empty($fields['projects'])) {
            $bucketIds = collect($fields['buckets']);
            $projectIds = $this->normalizeProjects(collect($fields['projects']), $bucketIds);

            $currentProjects = $this->projects()->yourProjects($user)->get()->pluck('id');
            $currentBuckets = $this->buckets()->yourBuckets($user)->get()->pluck('id');


            $projectIdsToAttach = $projectIds->diff($currentProjects)->values();

            if ($projectIdsToAttach->isNotEmpty()) {
                $this->projects()->attach($projectIdsToAttach);
            }

            $projectIdsToDetach = $currentProjects->diff($projectIds)->values();

            if ($projectIdsToDetach) {
                $this->projects()->detach($projectIdsToDetach);
            }

            $bucketIdsToAttach = $bucketIds->diff($currentBuckets)->values();

            if ($bucketIdsToAttach->isNotEmpty()) {
                $this->buckets()->attach($bucketIdsToAttach);
            }

            $bucketIdsToDetach = $currentBuckets->diff($bucketIds)->values();

            if ($bucketIdsToDetach->isNotEmpty()) {
                $this->buckets()->detach($bucketIdsToDetach);
            }
        }

        return $this;
    }

    public function normalizeProjects(Collection $projectIds, Collection $bucketIds): Collection
    {
        if ($projectIds->isNotEmpty()) {
            // get project ids for all buckets
            $projectIdsFromBucketIds = Bucket::select(['project_id'])
                ->whereIn('id', $bucketIds)->get()
                ->map(fn(Bucket $bucket) => $bucket->project_id);

            // filter out projects where the bucket which belongs to a project is already present in the bucket ids
            $projectIds = $projectIds->filter(fn (int $projectId) => !$projectIdsFromBucketIds->contains($projectId));
        }

        return $projectIds;
    }

    public function deleteTasks(): ?bool
    {
        $this->tasks->each(function (Task $task) {
            $task->deleteTasks();
        });

        return $this->delete();
    }
}
