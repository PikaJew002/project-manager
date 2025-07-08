<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CurrentTeamController extends Controller
{
    /**
     * Update the user's current team.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'team_id' => ['required', 'integer'],
        ]);

        $organization = Organization::findOrFail($request->team_id);

        if (!$request->user()->belongsToTeam($organization)) {
            abort(403);
        }

        $request->user()->switchTeam($organization);

        return back(303);
    }
}
