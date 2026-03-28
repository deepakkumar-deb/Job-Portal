<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )

    ->withMiddleware(function (Middleware $middleware): void {

        // ❗ Not logged-in user → redirect to login
        $middleware->redirectGuestsTo(fn () => route('account.login'));

        // ❗ Logged-in user → cannot access login/register
        $middleware->redirectUsersTo(fn () => route('account.profile'));

    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();