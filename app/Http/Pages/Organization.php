<?php

namespace App\Http\Pages;

use App\Http\Resources\OrganizationResource;
use App\Models\Organization as OrganizationModel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Organization
{
    public function __invoke(Request $request): Response|RedirectResponse
    {
        if (!$request->user()->is_admin) {

            session()->flash('inertia', ['status' => "You don't have access to that page. Why are you trying random URLs? Unless you clicked on something in the app that lead you there. Then tell us about it please."]);

            return response()->redirectToRoute('dashboard-grid');
        }

        $organization = OrganizationModel::with(['users', 'invitesNotDeclined'])->findOrFail($request->user()->organization_id);

        return Inertia::render('Organization', [
            'organization' => new OrganizationResource($organization),
        ]);
    }
}
