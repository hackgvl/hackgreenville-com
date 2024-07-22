<?php

namespace App\Filament\Resources;

use App\Enums\EventServices;
use App\Filament\Resources\EventResource\Pages;
use App\Models\Event;
use App\Models\Org;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Malzariey\FilamentDaterangepickerFilter\Filters\DateRangeFilter;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Event Details')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('organization_id')
                            ->relationship('organization', 'title')
                            ->searchable()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                $org = Org::find($state);

                                $set('group_name', $org->title);
                            }),

                        Forms\Components\Select::make('venue_id')
                            ->relationship('venue', 'name', fn ($query) => $query->orderBy('name'))
                            ->getOptionLabelFromRecordUsing(fn (Venue $venue) => "{$venue->name} - {$venue->address}"),

                        Forms\Components\TextInput::make('uri')
                            ->label('Event URL Page')
                            ->required()
                            ->url()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('event_name')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\TextInput::make('group_name')
                            ->required()
                            ->readOnly()
                            ->columnSpanFull(),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull(),

                        Forms\Components\DateTimePicker::make('active_at')
                            ->label('Event Date')
                            ->date()
                            ->required(),

                        Forms\Components\DateTimePicker::make('cancelled_at')
                            ->date()
                            ->time(false)
                            ->nullable(),
                    ]),
                Forms\Components\Section::make('Event Service')
                    ->columns(2)
                    ->schema([
                        Forms\Components\Select::make('service')
                            ->options(EventServices::class)
                            ->required(),

                        Forms\Components\TextInput::make('service_id')
                            ->required()
                            ->maxLength(255)
                            ->default(0),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('organization.title')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('service')
                    ->searchable(),
                Tables\Columns\TextColumn::make('active_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cancelled_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('uri')
                    ->searchable(),
                Tables\Columns\TextColumn::make('venue.name')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                DateRangeFilter::make('active_at')
                    ->defaultToday()
                    ->autoApply()
                    ->label('Event Date')
                    ->displayFormat('MM/DD/YYYY')
                    ->format('m/d/Y'),
                Tables\Filters\SelectFilter::make('organization')
                    ->relationship('organization', 'title')
                    ->multiple(),
                Tables\Filters\SelectFilter::make('service')
                    ->options(EventServices::class)
                    ->multiple()
                    ->attribute('service'),
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
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
