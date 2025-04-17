<?php

namespace App\Http\Pages;

use App\Models\Invite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Inertia\Inertia;
use Inertia\Response;

class RegisterFromInvite
{
    public function __invoke(string $token): RedirectResponse|Response
    {
        if (Auth::check()) {
            session()->flash('inertia', ['status' => '']);
        }

        $invite = Invite::with(['organization', 'invitedBy'])->where('token', $token)->first();

        if ($invite === null) {
            session()->flash('inertia', ['status' => "The invite link you are trying to access has been declined and deleted after a week."]);

            return response()->redirectToRoute('welcome');
        }

        return Inertia::render('RegisterFromInvite', [
            'declined_at' => $invite->declined_at,
            'invited_by' => $invite->invitedBy->first_name . ' ' . $invite->invitedBy->last_name,
            'invited_to' => $invite->organization->name,
            'token' => Crypt::encryptString($invite->token),
        ]);
    }
}
