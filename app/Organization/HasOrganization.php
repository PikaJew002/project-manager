<?php

namespace App\Organization;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

trait HasOrganization
{
    /**
     * Determine if the given organization is the current organization.
     *
     * @param  mixed  $organization
     * @return bool
     */
    public function isCurrentTeam($organization)
    {
        return $organization->id === $this->currentTeam->id;
    }

    /**
     * Get the current organization of the user's context.
     */
    public function currentTeam(): BelongsTo
    {
        if (is_null($this->current_organization_id) && $this->id) {
            $this->switchTeam($this->personalTeam());
        }

        return $this->belongsTo(Organization::class, 'current_organization_id');
    }

    /**
     * Switch the user's context to the given organization.
     *
     * @param  mixed  $organization
     */
    public function switchTeam($organization): bool
    {
        if (!$this->belongsToTeam($organization)) {
            return false;
        }

        $this->forceFill([
            'current_organization_id' => $organization->id,
        ])->save();

        $this->setRelation('currentTeam', $organization);

        return true;
    }

    /**
     * Get all of the organizations the user owns or belongs to.
     */
    public function allTeams(): Collection
    {
        return $this->ownedTeams->merge($this->teams)->sortBy('name');
    }

    /**
     * Get all of the organizations the user owns.
     */
    public function ownedTeams(): HasMany
    {
        return $this->hasMany(Organization::class, 'user_id');
    }

    /**
     * Get all of the organizations the user belongs to.
     */
    public function teams(): BelongsToMany
    {
        return $this->belongsToMany(Organization::class, 'organization_user')
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }

    /**
     * Get the user's "personal" organization.
     */
    public function personalTeam(): Organization
    {
        return $this->ownedTeams->where('personal_team', true)->first();
    }

    /**
     * Determine if the user owns the given organization.
     */
    public function ownsTeam(Organization $organization): bool
    {
        if (is_null($organization)) {
            return false;
        }

        return $this->id == $organization->user_id;
    }

    /**
     * Alias for ownsTeam for consistency.
     */
    public function ownsOrganization(Organization $organization): bool
    {
        return $this->ownsTeam($organization);
    }

    /**
     * Determine if the user belongs to the given organization.
     */
    public function belongsToTeam(?Organization $organization): bool
    {
        if (is_null($organization)) {
            return false;
        }

        return $this->ownsTeam($organization) || $this->teams->contains(function ($t) use ($organization) {
            return $t->id === $organization->id;
        });
    }

    /**
     * Get the role that the user has on the organization.
     */
    public function teamRole(Organization $organization): ?Role
    {
        if ($this->ownsTeam($organization)) {
            return new OwnerRole;
        }

        if (!$this->belongsToTeam($organization)) {
            return null;
        }

        $role = $organization->users
            ->where('id', $this->id)
            ->first()
            ->membership
            ->role;

        return $role ? Roles::findRole($role) : null;
    }

    /**
     * Determine if the user has the given role on the given organization.
     */
    public function hasTeamRole(Organization $organization, string $role): bool
    {
        if ($this->ownsTeam($organization)) {
            return true;
        }

        return $this->belongsToTeam($organization) && optional(value: Roles::findRole($organization->users->where(
            'id',
            $this->id
        )->first()->membership->role))->key === $role;
    }

    /**
     * Get the user's permissions for the given organization.
     */
    public function teamPermissions(Organization $organization): array
    {
        if ($this->ownsTeam($organization)) {
            return ['*'];
        }

        if (!$this->belongsToTeam($organization)) {
            return [];
        }

        return (array) optional($this->teamRole($organization))->permissions;
    }

    /**
     * Determine if the user has the given permission on the given organization.
     */
    public function hasTeamPermission(Organization $organization, string $permission): bool
    {
        if ($this->ownsTeam($organization)) {
            return true;
        }

        if (!$this->belongsToTeam($organization)) {
            return false;
        }

        $permissions = $this->teamPermissions($organization);

        return in_array($permission, $permissions) ||
            in_array('*', $permissions) ||
            (Str::endsWith($permission, ':create') && in_array('*:create', $permissions)) ||
            (Str::endsWith($permission, ':update') && in_array('*:update', $permissions));
    }

    /**
     * Create a new organization for the user.
     */
    public function createOrganization(array $attributes): Organization
    {
        $organization = $this->ownedTeams()->create($attributes);

        $this->switchTeam($organization);

        return $organization;
    }

    /**
     * Create a new personal organization for the user.
     */
    public function createPersonalOrganization(): Organization
    {
        return $this->createOrganization([
            'name' => $this->first_name . "'s Organization",
            'personal_team' => true,
        ]);
    }
}
