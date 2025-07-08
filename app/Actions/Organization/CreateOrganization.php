<?php

namespace App\Actions\Organization;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CreateOrganization
{
    public function __invoke(Request $request): RedirectResponse
    {
        $input = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $user = $request->user();

        $organization = $user->createOrganization([
            'name' => $input['name'],
            'personal_team' => false,
        ]);

        return redirect()->route('teams.show', $organization);
    }
}
