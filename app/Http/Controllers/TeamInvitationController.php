<?php

namespace App\Http\Controllers;

use App\Models\OrganizationInvitation;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;

class TeamInvitationController extends Controller
{
    /**
     * Accept a team invitation.
     */
    public function accept(Request $request, OrganizationInvitation $invitation): RedirectResponse
    {
        $invitation->organization->users()->attach($request->user()->id, [
            'role' => $invitation->role,
        ]);

        $invitation->delete();

        return redirect()->route('teams.show', $invitation->organization);
    }

    /**
     * Cancel the given team invitation.
     */
    public function destroy(Request $request, OrganizationInvitation $invitation): RedirectResponse
    {
        if (!Gate::forUser($request->user())->allows('removeTeamMember', $invitation->organization)) {
            abort(403);
        }

        $invitation->delete();

        return back(303);
    }
}
