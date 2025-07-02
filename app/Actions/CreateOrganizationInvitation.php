<?php

namespace App\Actions;

use App\Models\OrganizationInvitation;
use App\Models\User;
use App\Notifications\OrganizationInvitationUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CreateOrganizationInvitation
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class,'unique:' . OrganizationInvitation::class],
        ]);

        $invite = OrganizationInvitation::create([
            'organization_id' => $request->user()->organization_id,
            'invited_by' => $request->user()->id,
            'email' => $request->email,
            'token' => Str::random(16),
        ]);

        Notification::route('mail', $request->email)->notify(new OrganizationInvitationUser($invite));

        session()->flash('inertia', ['status' => 'New User OrganizationInvitation Sent!']);

        return response()->redirectToRoute('organization');
    }
}
