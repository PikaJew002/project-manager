<?php

namespace App\Actions;

use App\Models\Invite;
use App\Models\Project;
use App\Models\User;
use Closure;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AcceptInvite
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
                    if (Invite::query()->where('token', Crypt::decryptString($value))->doesntExist()) {
                        $fail("The {$attribute} is invalid.");
                    }
                },
            ],
            'timezone' => ['nullable', 'string', 'max:255'],
        ]);

        $invite = Invite::with('organization')->where('token', Crypt::decryptString($fields['token']))->firstOrFail();

        $user = DB::transaction(function () use ($invite, $fields) {
            $user = User::create([
                'organization_id' => $invite->organization->id,
                'first_name' => $fields['first_name'],
                'last_name' => $fields['last_name'],
                'initials' => $fields['initials'],
                'email' => $invite->email,
                'password' => Hash::make($fields['password']),
                'timezone' => $fields['timezone'] ?? null,
                'settings' => [
                    'notifications' => [
                        'daily_tasks_due' => true,
                        'weekly_tasks_due' => true,
                        'tasks_assigned' => true,
                        'tasks_updated' => true,
                    ],
                ],
            ]);

            Project::create([
                'organization_id' => $invite->organization->id,
                'administered_by' => $user->id,
                'name' => 'Private Tasks',
                'initials' => 'PT',
                'is_personal' => true,
            ]);

            Invite::destroy($invite->id);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        session()->flash('inertia', ['status' => "Invitation accepted! Welcome aboard, {$fields['first_name']}!"]);

        return response()->redirectToRoute('dashboard-grid');
    }
}
