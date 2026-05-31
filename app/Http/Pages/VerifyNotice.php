<?php

namespace App\Http\Pages;

use Inertia\Inertia;
use Inertia\Response;

class VerifyNotice
{
    public function __invoke(): Response
    {
        return Inertia::render('VerifyNotice');
    }
}
