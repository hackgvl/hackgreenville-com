<?php

namespace App\Filament\Resources;

use App\Enums\EventServices;
use App\Enums\OrganizationStatus;
use App\Filament\Resources\OrgResource\Pages;
//use App\Filament\Resources\OrgResource\RelationManagers\EventsRelationManager; // including this in another PR
use App\Models\Org;
use App\Models\Venue;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrgResource extends Resource
{
    protected static ?string $modelLabel = 'Organization';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $model = Org::class;

    protected static ?string $navigationIcon = Org::icon;

    protected static ?int $navigationSort = 10;

    public static function getGloballySearchableAttributes(): array
    {
        return [
            'slug',
            'title',
            'description',
            'uri',
            'focus_area',
            'city',
        ];
    }

    public static function getGlobalSearchResultDetails(Venue|Model $record): array
    {
        return [
            'Title' => $record->title,
            'Description' => $record->description,
            'City' => $record->city,
            'Focus Area' => $record->focus_area,
            'URI' => $record->uri,
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('General')
                                        ->columns(2)
                                        ->schema([
                                            Forms\Components\TextInput::make('title')
                                                                      ->required()
                                                                      ->live(onBlur: true)
                                                                      ->afterStateUpdated(fn (Forms\Set $set, ?string $state) => $set('slug', Str::slug($state)))
                                                                      ->maxLength(255),

                                            Forms\Components\TextInput::make('slug')
                                                                      ->disabledOn(
                                                                          'edit'
                                                                      )
                                                                      ->live()
                                                                      ->maxLength(255),

                                            Forms\Components\Textarea::make('description')
                                                                     ->required()
                                                                     ->columnSpanFull(),

                                            Forms\Components\TextInput::make('focus_area')
                                                                      ->required()
                                                                      ->columnSpanFull(),

                                            Forms\Components\TextInput::make('city')
                                                                      ->required()
                                                                      ->maxLength(255),

                                            Forms\Components\TextInput::make('organization_type')
                                                                      ->datalist(Org::distinct()->pluck('organization_type'))
                                                                      ->required(),

                                            Forms\Components\Select::make('category_id')
                                                                   ->relationship(name: 'category', titleAttribute: 'label')
                                                                   ->required(),

                                            Forms\Components\Select::make('tags')
                                                                   ->multiple()
                                                                   ->relationship('tags', 'name'),

                                            Forms\Components\TextInput::make('uri')
                                                                      ->name('Url')
                                                                      ->url()
                                                                      ->maxLength(255),

                                            Forms\Components\Select::make('status')
                                                                   ->required()
                                                                   ->options(OrganizationStatus::class),

                                            Forms\Components\DateTimePicker::make('established_at')
                                                                           ->required()
                                                                           ->time(false)
                                                                           ->format('m/d/Y'),

                                            Forms\Components\DateTimePicker::make('inactive_at')
                                                                           ->time(false)
                                                                           ->format('m/d/Y'),
                                        ]),

                Forms\Components\Section::make('Contacts')
                                        ->schema([
                                            Forms\Components\TextInput::make('primary_contact_person')
                                                                      ->maxLength(255),
                                        ]),

                Forms\Components\Section::make('Event Service')
                                        ->columns(2)
                                        ->schema([
                                            Forms\Components\Select::make('service')
                                                                   ->options(EventServices::class),

                                            Forms\Components\TextInput::make('service_api_key')
                                                                      ->maxLength(255),

                                            Forms\Components\TextInput::make('event_calendar_uri')
                                                                      ->maxLength(255),
                                        ]),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                                         ->searchable(),

                Tables\Columns\TextColumn::make('city')
                                         ->searchable(),

                Tables\Columns\TextColumn::make('primary_contact_person')
                                         ->searchable(),

                Tables\Columns\TextColumn::make('service')
                                         ->toggleable(isToggledHiddenByDefault: true)
                                         ->searchable()
                                         ->sortable(),

                Tables\Columns\TextColumn::make('service_api_key')
                                         ->toggleable(isToggledHiddenByDefault: true)
                                         ->searchable()
                                         ->sortable(),

                Tables\Columns\TextColumn::make('status')
                                         ->sortable(),

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
            ])
            ->defaultSort('title', 'asc')
            ->paginated(['all']);
    }

    public static function getRelations(): array
    {
        return [
            //EventsRelationManager::class, // including this in another PR
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrgs::route('/'),
            'create' => Pages\CreateOrg::route('/create'),
            'edit' => Pages\EditOrg::route('/{record}/edit'),
        ];
    }
}
