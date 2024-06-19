<?php

namespace App\Filament\Resources\OrgResource\Pages;

use App\Filament\Resources\OrgResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrg extends EditRecord
{
    protected static string $resource = OrgResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('view')
                ->label('View Public Page')
                ->link()
                ->url(fn () => route('orgs.show', ['org' => $this->record])),
            Actions\DeleteAction::make(),
        ];
    }
}
