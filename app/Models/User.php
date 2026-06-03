<?php

namespace App\Models;

use DateTimeZone;
use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

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
        'settings',
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
            'settings' => AsArrayObject::class,
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
        return $this->belongsToMany(Task::class)->withPivot('assigned_by')->withTimestamps()->ordered();
    }

    public function scopeYourUsers(Builder $query, User $user): void
    {
        $query->whereHas('projects', function (Builder $query) use ($user) {
            $query->yourProjects($user);
        });
    }

    public function is8AMInTimezone(): bool
    {
        $tz = new DateTimeZone($this->timezone ?? config('app.timezone'));
        $now = new DateTime('now', $tz);

        return (int) $now->format('H') === 8 && (int) $now->format('i') === 0;
    }

    public function isMondayInTimezone(): bool
    {
        $tz = new DateTimeZone($this->timezone ?? config('app.timezone'));
        $now = new DateTime('now', $tz);

        return (int) $now->format('w') === 1;
    }
}
