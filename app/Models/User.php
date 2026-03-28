<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The relationships that should always be loaded.
     *
     * @var array<int, string>
     */
    protected $with = ['organization'];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'organization_id',
        'first_name',
        'last_name',
        'initials',
        'is_admin',
        'email',
        'password',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class, 'invited_by');
    }

    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class)->withPivot('accepted_at')->withTimestamps();
    }

    public function administering(): HasMany
    {
        return $this->hasMany(Project::class, 'administered_by', 'id');
    }

    public function buckets(): HasMany
    {
        return $this->hasMany(Bucket::class, 'created_by', 'id');
    }

    public function tasks(): BelongsToMany
    {
        return $this->belongsToMany(Task::class)->withPivot('assigned_by', 'notified_at')->withTimestamps()->orderByRaw('(completed_at IS NOT NULL) ASC')
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

    public function scopeYourUsers(Builder $query, User $user): void
    {
        $query->whereHas('projects', function (Builder $query) use ($user) {
            $query->yourProjects($user);
        });
    }
}
