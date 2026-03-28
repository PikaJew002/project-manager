<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
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
            'is_personal' => 'boolean',
        ];
    }

    /** @use HasFactory<\Database\Factories\ProjectFactory> */
    use HasFactory, SoftDeletes;

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function administeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'administered_by', 'id');
    }

    public function buckets(): HasMany
    {
        return $this->hasMany(Bucket::class);
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

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('accepted_at')->withTimestamps();
    }

    public function scopeYourProjects(Builder $query, User $user): void
    {
        $query->where(function (Builder $query) use ($user) {
            $query->whereRelation('administeredBy', 'id', $user->id)
                ->orWhereHas('users', function (Builder $query) use ($user) {
                    $query->whereRaw('`users`.`id` = ?', $user->id);
                });
        });
    }
}
