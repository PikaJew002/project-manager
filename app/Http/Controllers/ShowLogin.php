<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class ShowLogin
{
    public function __invoke(): Response
    {
        return Inertia::render('Welcome');
    }
}
