<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Organization extends Model
{
    /** @use HasFactory<\Database\Factories\OrganizationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'personal_team',
        'user_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'personal_team' => 'boolean',
        ];
    }

    /**
     * Get the user that owns the organization.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get all of the organization's users including its owner.
     */
    public function allUsers(): BelongsToMany
    {
        return $this->users()->withPivot('role')->withTimestamps();
    }

    /**
     * Get all of the users that belong to the organization.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'organization_user')
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    /**
     * Get the organization's invitations.
     */
    public function invites(): HasMany
    {
        return $this->hasMany(OrganizationInvitation::class);
    }

    /**
     * Get the organization's invitations that haven't been declined.
     */
    public function invitesNotDeclined(): HasMany
    {
        return $this->hasMany(OrganizationInvitation::class)->whereNull('declined_at');
    }

    /**
     * Get the organization's projects.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Determine if the given user belongs to the organization.
     */
    public function hasUser($user): bool
    {
        if (is_null($user)) {
            return false;
        }

        return $this->users->contains($user) || $user->ownsOrganization($this);
    }

    /**
     * Determine if the given user owns the organization.
     */
    public function ownedBy($user): bool
    {
        if (is_null($user)) {
            return false;
        }

        return $user->ownsOrganization($this);
    }

    /**
     * Remove the given user from the organization.
     */
    public function removeUser($user, bool $silent = false): void
    {
        if ($user->membership) {
            $user->membership->delete();
        }

        $this->users()->detach($user);

        if (!$silent) {
            $this->owner->notify(new \App\Notifications\OrganizationMemberRemoved($this, $user));
        }
    }

    /**
     * Purge all of the organization's resources.
     */
    public function purge(): void
    {
        $this->owner->where('current_organization_id', $this->id)
            ->update(['current_organization_id' => null]);

        $this->users()->where('current_organization_id', $this->id)
            ->update(['current_organization_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
}
