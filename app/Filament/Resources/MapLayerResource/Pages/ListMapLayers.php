<?php

namespace App\Filament\Resources\MapLayerResource\Pages;

use App\Filament\Resources\MapLayerResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMapLayers extends ListRecords
{
    protected static string $resource = MapLayerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
