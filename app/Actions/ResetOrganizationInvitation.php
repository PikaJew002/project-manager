<?php

namespace App\Actions;

use App\Models\OrganizationInvitation;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ResetOrganizationInvitation
{
    public function __invoke(Request $request)
    {
        $fields = $request->validate([
            'token' => [
                'required',
                'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (OrganizationInvitation::where('token', Crypt::decryptString($value))->doesntExist()) {
                        $fail("The {$attribute} is invalid.");
                    }
                },
            ],
        ]);

        $token = Crypt::decryptString($fields['token']);

        $invite = OrganizationInvitation::where('token', $token)->firstOrFail();

        $invite->update([
            'declined_at' => null,
        ]);

        session()->flash('inertia', ['status' => "Invitation Reset! You may respond to this invite again."]);

        return response()->redirectToRoute('register-invite', $token);
    }
}
