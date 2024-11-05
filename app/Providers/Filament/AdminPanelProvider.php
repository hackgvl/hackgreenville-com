<?php

namespace App\Providers\Filament;

use App\Filament\Resources\EventResource\Widgets\TotalActiveEvents;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Http\Request;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path(config('app.dashboard.root_path'))
            ->login()
            ->passwordReset()
            ->darkMode()
            ->spa()
            ->unsavedChangesAlerts()
            ->globalSearchKeyBindings([
                'ctrl+k',
                'command+k',
            ])
            ->globalSearchDebounce('750ms')
            ->colors([
                'primary' => Color::hex('#60ae6d'),
                'gray' => Color::hex('#201748'),
            ])
            ->brandLogo(static fn () => view('includes.logo', ['darkMode' => false]))
            ->darkModeBrandLogo(static fn () => view('includes.logo'))
            ->brandLogoHeight(static fn (Request $request) => match (true) {
                str($request->route()->getName())
                    ->isMatch('/filament\.admin\.*/') => '3rem',
                default => 'auto',
            })
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                TotalActiveEvents::class,
            ])
            ->middleware(['filament-web'])
            ->authMiddleware(['filament-auth'])
            ->favicon(asset('favicon.png'));
    }
}
