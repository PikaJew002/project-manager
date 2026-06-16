<!DOCTYPE html>
<html class="h-full bg-white">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
        <link rel="icon" href="/favicon.ico" sizes="48x48">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <title inertia>{{ config('app.name') }}</title>
        @routes
        @vite('resources/js/app.js', 'build')
        @inertiaHead
    </head>
    <body class="h-full">
        @inertia
    </body>
</html>
