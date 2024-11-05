<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VenueResource\Pages;
use App\Models\Venue;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;

    protected static ?string $navigationIcon = Venue::icon;

    protected static ?int $navigationSort = 30;

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'slug',
            'name',
            'address',
            'zipcode',
            'phone',
        ];
    }

    public static function getGlobalSearchResultDetails(Venue|Model $record): array
    {
        return [
            'Name' => $record->name,
            'Address' => $record->address,
            'Phone' => $record->phone,
            'Latitude' => $record->lat,
            'Longitude' => $record->lng,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                                          ->required()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('address')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('zipcode')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                                          ->placeholder('(000) 000-0000')
                                          ->tel()
                                          ->maxLength(255),
                Forms\Components\TextInput::make('city')
                                          ->maxLength(255),
                Forms\Components\Select::make('state_id')
                                       ->relationship('state', 'abbr'),
                Forms\Components\TextInput::make('lat')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('lng')
                                          ->maxLength(255),
                Forms\Components\TextInput::make('country')
                                          ->maxLength(2),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('address')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('zipcode')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('phone')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('city')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('state.abbr')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                                         ->dateTime()
                                         ->sortable()
                                         ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('lat')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('lng')
                                         ->searchable(),
                Tables\Columns\TextColumn::make('country')
                                         ->searchable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit' => Pages\EditVenue::route('/{record}/edit'),
        ];
    }
}
