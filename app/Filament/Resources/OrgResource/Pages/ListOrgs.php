<?php

namespace App\Filament\Resources\OrgResource\Pages;

use App\Filament\Resources\OrgResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrgs extends ListRecords
{
    protected static string $resource = OrgResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
