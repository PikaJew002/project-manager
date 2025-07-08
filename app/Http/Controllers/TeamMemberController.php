<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\User;
use App\Organization\Roles;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class TeamMemberController extends Controller
{
    /**
     * Add a new team member.
     */
    public function store(Request $request, Organization $team): RedirectResponse
    {
        if (!Gate::forUser($request->user())->allows('addTeamMember', $team)) {
            abort(403);
        }

        $request->validate([
            'email' => ['required', 'email'],
            'role' => ['required', 'string'],
        ]);

        $team->users()->attach($request->email, [
            'role' => $request->role,
        ]);

        return back(303);
    }

    /**
     * Update the given team member's role.
     */
    public function update(Request $request, Organization $team, User $user): RedirectResponse
    {
        if (!Gate::forUser($request->user())->allows('updateTeamMember', $team)) {
            abort(403);
        }

        $request->validate([
            'role' => ['required', 'string'],
        ]);

        $team->users()->updateExistingPivot($user->id, [
            'role' => $request->role,
        ]);

        return back(303);
    }

    /**
     * Remove the given user from the team.
     */
    public function destroy(Request $request, Organization $team, User $user): RedirectResponse
    {
        if (!Gate::forUser($request->user())->allows('removeTeamMember', $team)) {
            abort(403);
        }

        $team->removeUser($user);

        return back(303);
    }
}
