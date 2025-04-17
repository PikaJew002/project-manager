<?php

namespace App\Actions;

use App\Models\Invite;
use App\Models\User;
use App\Notifications\InviteUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;

class CreateInvite
{
    public function __invoke(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
        ]);

        $invite = Invite::create([
            'organization_id' => $request->user()->organization_id,
            'invited_by' => $request->user()->id,
            'email' => $request->email,
            'token' => Str::random(16),
        ]);

        Notification::route('mail', $request->email)->notify(new InviteUser($invite));

        session()->flash('inertia', ['status' => 'New User Invite Sent!']);

        return response()->redirectToRoute('organization');
    }
}
