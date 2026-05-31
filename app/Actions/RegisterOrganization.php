<?php

namespace App\Actions;

use App\Models\Organization;
use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterOrganization
{
    public function __invoke(Request $request)
    {
        $fields = $request->validate([
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'initials' => ['required', 'string', 'max:5'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'organization_name' => ['required', 'string', 'max:255'],
            'timezone' => ['nullable', 'string', 'max:255'],
        ]);
        $user = DB::transaction(function () use ($fields) {
            $organization = Organization::create([
                'name' => $fields['organization_name'],
            ]);

            $user = User::create([
                'organization_id' => $organization->id,
                'first_name' => $fields['first_name'],
                'last_name' => $fields['last_name'],
                'initials' => $fields['initials'],
                'is_admin' => true,
                'email' => $fields['email'],
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
                'organization_id' => $organization->id,
                'administered_by' => $user->id,
                'name' => 'Private Tasks',
                'initials' => 'PT',
                'is_personal' => true,
            ]);

            return $user;
        });

        event(new Registered($user));

        Auth::login($user);

        return response()->redirectToRoute('organization');
    }
}
