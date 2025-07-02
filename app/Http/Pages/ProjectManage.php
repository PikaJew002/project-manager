<?php

namespace App\Http\Pages;

use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectManage
{
    public function __invoke(Request $request, int $id)
    {
        $project = Project::with(['users', 'administeredBy'])->findOrFail($id);

        return Inertia::render('ProjectGrid', [
            'project' => $project,
        ]);
    }
}
