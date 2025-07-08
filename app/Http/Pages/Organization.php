<?php

namespace App\Http\Pages;

use App\Http\Resources\OrganizationResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Organization
{
    public function __invoke(Request $request): Response|RedirectResponse
    {
        if (!$request->user()->currentTeam) {
            return response()->redirectToRoute('dashboard-grid');
        }

        $organization = $request->user()->currentTeam->load(['users', 'invitesNotDeclined']);

        return Inertia::render('Organization', [
            'organization' => new OrganizationResource($organization),
        ]);
    }
}
