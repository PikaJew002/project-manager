<?php

use App\Http\Middleware\HandleInertiaRequests;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web([
            HandleInertiaRequests::class,
        ]);
        $middleware->redirectGuestsTo(fn() => route('welcome'));
        $middleware->redirectUsersTo(fn () => route('dashboard-grid'));
    })
    ->withSchedule(function (Schedule $schedule) {
        $schedule->command('model:prune')->daily();
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
