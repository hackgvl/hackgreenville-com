<?php

namespace App\Filament\Resources;

use App\Enums\EventServices;
use App\Enums\OrganizationStatus;
use App\Filament\Resources\OrgResource\Pages;
use App\Models\Org;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrgResource extends Resource
{
    protected static ?string $modelLabel = 'Organization';

    protected static ?string $recordTitleAttribute = 'title';

    protected static ?string $model = Org::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('General')
                    ->columns(2)
                    ->schema([
                        Forms\Components\TextInput::make('title')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),

                        Forms\Components\TextInput::make('slug')
                            ->required()
                            ->columnSpanFull()
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
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('service_api_key')
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
