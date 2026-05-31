<?php

namespace App\Http\Pages\Settings;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class Account
{
    public function __invoke(Request $request): Response|RedirectResponse
    {
        return Inertia::render('Settings/Account');
    }
}
