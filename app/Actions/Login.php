<?php

namespace App\Actions;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;

class Login
{
    public function __invoke(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard-grid', absolute: false));
    }
}
