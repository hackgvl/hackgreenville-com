<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MapLayerResource\Pages;
use App\Models\MapLayer;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class MapLayerResource extends Resource
{
    protected static ?string $model = MapLayer::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('General')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->maxLength(255)
                            ->live(onBlur: true)
                            ->afterStateUpdated(fn (Set $set, ?string $state) => $set('slug', Str::slug($state ?? ''))),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->alphaDash()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Textarea::make('description')
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Map Settings')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('center_latitude')
                            ->numeric()
                            ->default(34.850700),

                        Forms\Components\TextInput::make('center_longitude')
                            ->numeric()
                            ->default(-82.398500),

                        Forms\Components\TextInput::make('zoom_level')
                            ->numeric()
                            ->default(10)
                            ->minValue(1)
                            ->maxValue(20),
                    ]),

                Forms\Components\Section::make('Links')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('geojson_link')
                            ->label('GeoJSON Link')
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('contribute_link')
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('raw_data_link')
                            ->url()
                            ->maxLength(255),
                    ]),

                Forms\Components\Section::make('Maintainers')
                    ->schema([
                        Forms\Components\Repeater::make('maintainers')
                            ->hiddenLabel()
                            ->schema([
                                Forms\Components\TextInput::make('name')
                                    ->required(),

                                Forms\Components\TextInput::make('github')
                                    ->label('GitHub URL')
                                    ->url(),

                                Forms\Components\TextInput::make('url')
                                    ->label('Other URL')
                                    ->url(),
                            ])
                            ->columns(3)
                            ->defaultItems(0)
                            ->reorderable()
                            ->collapsible(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('slug')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('zoom_level')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([])
            ->actions([
                Tables\Actions\Action::make('sync')
                    ->label('Sync')
                    ->icon('heroicon-o-arrow-path')
                    ->requiresConfirmation()
                    ->action(function (MapLayer $record) {
                        $result = app(\App\Services\MapLayerSyncService::class)->sync($record);

                        Notification::make()
                            ->title($result['success'] ? 'Sync Successful' : 'Sync Failed')
                            ->body($result['message'])
                            ->status($result['success'] ? 'success' : 'danger')
                            ->send();
                    }),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('title', 'asc');
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMapLayers::route('/'),
            'create' => Pages\CreateMapLayer::route('/create'),
            'edit' => Pages\EditMapLayer::route('/{record}/edit'),
        ];
    }
}
