<?php

namespace App\Actions;

use App\Models\Invite;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class DeclineInvite
{
    public function __invoke(Request $request)
    {
        $fields = $request->validate([
            'token' => [
                'required',
                'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (Invite::where('token', Crypt::decryptString($value))->doesntExist()) {
                        $fail("The {$attribute} is invalid.");
                    }
                },
            ],
        ]);

        $token = Crypt::decryptString($fields['token']);

        $invite = Invite::where('token', $token)->firstOrFail();

        $invite->update([
            'declined_at' => now(),
        ]);

        session()->flash('inertia', ['status' => "Invitation Declined. We're sorry to see you go!"]);

        return response()->redirectToRoute('register-invite', $token);
    }
}
