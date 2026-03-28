<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bucket extends Model
{
    /** @use HasFactory<\Database\Factories\BucketFactory> */
    use HasFactory, SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class)->withTimestamps()->orderByRaw('(completed_at IS NOT NULL) ASC')
            ->orderByRaw('(started_at IS NOT NULL) ASC')
            ->orderByRaw('(due_at IS NULL) ASC')
            ->orderBy('due_at')
            ->orderByRaw("
                CASE priority
                    WHEN 'Urgent' THEN 1
                    WHEN 'Important' THEN 2
                    WHEN 'Medium' THEN 3
                    WHEN 'Low' THEN 4
                END
            ")
            ->orderBy('name');
    }

    public function scopeYourBuckets(Builder $query, User $user): void
    {
        $query->when($user->is_admin, function (Builder $query) use ($user) {
            $query->whereHas('project', function (Builder $query) use ($user) {
                $query->whereRelation('organization', 'id', $user->organization_id)
                    ->where(function (Builder $query) use ($user) {
                        $query->where('is_personal', false)->orWhere('administered_by', $user->id);
                    });
            });
        }, function (Builder $query) use ($user) {
            $query->whereHas('project', function (Builder $query) use ($user) {
                $query->where(function (Builder $query) use ($user) {
                    $query->whereRelation('administeredBy', 'id', $user->id)
                        ->orWhereHas('users', function (Builder $query) use ($user) {
                            $query->whereRaw('`users`.`id` = ?', $user->id);
                        });
                });
            });
        });
    }
}
