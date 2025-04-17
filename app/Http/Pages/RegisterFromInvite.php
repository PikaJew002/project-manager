<?php

namespace App\Http\Pages;

use App\Models\Invite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;

class RegisterFromInvite
{
    public function __invoke(Request $request, string $token)
    {
        $invite = Invite::with(['organization', 'invitedBy'])->where('token', $token)->firstOrFail();

        return Inertia::render('RegisterFromInvite', [
            'accepted_at' => $invite->accepted_at,
            'declined_at' => $invite->declined_at,
            'invited_by' => $invite->invitedBy->first_name . ' ' . $invite->invitedBy->last_name,
            'invited_to' => $invite->organization->name,
            'token' => Crypt::encryptString($invite->token),
        ]);
    }
}
