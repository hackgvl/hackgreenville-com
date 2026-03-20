<?php

namespace App\Filament\Resources\MapLayerResource\Pages;

use App\Filament\Resources\MapLayerResource;
use App\Services\MapLayerSyncService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListMapLayers extends ListRecords
{
    protected static string $resource = MapLayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('syncAll')
                ->label('Sync All')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->action(function () {
                    $results = app(MapLayerSyncService::class)->syncAll();
                    $succeeded = collect($results)->where('success', true)->count();
                    $failed = collect($results)->where('success', false)->count();

                    Notification::make()
                        ->title('Sync Complete')
                        ->body("{$succeeded} synced, {$failed} failed.")
                        ->status($failed > 0 ? 'warning' : 'success')
                        ->send();
                }),
            Actions\CreateAction::make(),
        ];
    }
}
