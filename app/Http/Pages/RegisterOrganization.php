<?php

namespace App\Http\Pages;

use Inertia\Inertia;
use Inertia\Response;

class RegisterOrganization
{
    public function __invoke(): Response
    {
        return Inertia::render('RegisterOrganization');
    }
}
