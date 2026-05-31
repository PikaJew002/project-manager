<?php

namespace App\Actions;

use Illuminate\Http\Request;

class ResendEmailVerificationNotice
{
    public function __invoke(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();

        return response()->redirectToRoute('verification.notice')->with('message', 'Verification link sent!');
    }
}
