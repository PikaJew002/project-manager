<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class TeamController extends Controller
{
    /**
     * Show the team creation screen.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Teams/Create');
    }

    /**
     * Create a new team.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $organization = $request->user()->createOrganization([
            'name' => $request->name,
            'personal_team' => false,
        ]);

        return redirect()->route('teams.show', $organization);
    }

    /**
     * Show the given team.
     */
    public function show(Request $request, Organization $team): Response
    {
        $team->load(['users', 'invitesNotDeclined']);

        return Inertia::render('Teams/Show', [
            'team' => new OrganizationResource($team),
        ]);
    }

    /**
     * Update the given team's information.
     */
    public function update(Request $request, Organization $team): RedirectResponse
    {
        if (!$request->user()->ownsTeam($team)) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $team->forceFill([
            'name' => $request->name,
        ])->save();

        return back(303);
    }

    /**
     * Delete the given team.
     */
    public function destroy(Request $request, Organization $team): RedirectResponse
    {
        if (!$request->user()->ownsTeam($team)) {
            abort(403);
        }

        $team->purge();

        return redirect()->route('dashboard-grid');
    }
}
