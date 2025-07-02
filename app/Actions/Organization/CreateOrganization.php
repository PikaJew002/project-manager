<?php

namespace App\Actions\Organization;

use Illuminate\Http\Request;

class CreateOrganization
{
    public function __invoke(Request $request)
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();

        AddingTeam::dispatch($user);
        $user->switchTeam($team = $user->ownedTeams()->create([
            'name' => $input['name'],
            'personal_team' => false,
        ]));

        return $team;

        return $this->redirectPath($creator);
    }
}
