<?php

namespace App\Actions;

use App\Models\OrganizationInvitation;
use App\Models\Project;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AcceptOrganizationInvitation
{
    public function __invoke(Request $request)
    {
        $fields = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'initials' => ['required', 'string', 'max:5'],
            'password' => ['required', 'confirmed', Password::defaults()],
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

        $invite = OrganizationInvitation::with('organization')->where('token', Crypt::decryptString($fields['token']))->firstOrFail();

        $user = User::create([
            'organization_id' => $invite->organization->id,
            'first_name' => $fields['first_name'],
            'last_name' => $fields['last_name'],
            'initials' => $fields['initials'],
            'email' => $invite->email,
            'password' => Hash::make($fields['password']),
        ]);

        Project::create([
            'organization_id' => $invite->organization->id,
            'administered_by' => $user->id,
            'name' => 'Private Tasks',
            'initials' => 'PT',
            'is_personal' => true,
        ]);

        $invite->delete();

        Auth::login($user);

        session()->flash('inertia', ['status' => "Invitation Accepted! Welcome aboard, {$fields['first_name']}!"]);

        return response()->redirectToRoute('dashboard-grid');
    }
}
