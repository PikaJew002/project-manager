<?php

namespace App\Http\Middleware;

use Closure;

class SetUserTimezone
{
    public function handle($request, Closure $next)
    {
        if ($timezone = $request->header('X-Timezone')) {
            config(['app.user_timezone' => $timezone]);
        } else {
            config(['app.user_timezone' => $request->user()->timezone]);
        }

        return $next($request);
    }
}
