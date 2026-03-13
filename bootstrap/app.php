<?php

use App\Http\Middleware\NoIndexNonProduction;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(NoIndexNonProduction::class);

        $middleware->redirectGuestsTo(fn () => route('filament.admin.auth.login'));
        $middleware->redirectUsersTo(fn () => route('filament.admin.pages.dashboard'));

        $middleware->group('filament-web', [
            Illuminate\Cookie\Middleware\EncryptCookies::class,
            Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            Illuminate\Session\Middleware\StartSession::class,
            Illuminate\Session\Middleware\AuthenticateSession::class,
            Illuminate\View\Middleware\ShareErrorsFromSession::class,
            Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            Illuminate\Routing\Middleware\SubstituteBindings::class,
            Filament\Http\Middleware\DisableBladeIconComponents::class,
            Filament\Http\Middleware\DispatchServingFilamentEvent::class,
        ]);

        $middleware->group('filament-auth', [
            Filament\Http\Middleware\Authenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

    })
    ->create();
