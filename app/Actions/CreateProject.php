<?php

namespace App\Actions;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CreateProject
{
    public function __invoke(Request $request): RedirectResponse
    {
        $fields = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'initials' => ['required', 'string', 'max:5'],
        ]);

        $project = Project::create([
            'organization_id' => $request->user()->currentTeam->id,
            'administered_by' => $request->user()->id,
            'name' => $fields['name'],
            'initials' => $fields['initials'],
        ]);

        $project->users()->attach($request->user()->id, ['accepted_at' => now()]);

        return response()->redirectToRoute('project-board', $project->id);
    }
}
