<!DOCTYPE html>
<html class="h-full bg-white">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <title inertia>{{ config('app.name', 'Project Manager') }}</title>
        @routes
        @vite('resources/js/app.js')
        @inertiaHead
    </head>
    <body class="h-full">
        @inertia
    </body>
</html>
