<?php

namespace App\Http\Pages;

use Inertia\Inertia;
use Inertia\Response;

class Welcome
{
    public function __invoke(): Response
    {
        return Inertia::render('Welcome');
    }
}
