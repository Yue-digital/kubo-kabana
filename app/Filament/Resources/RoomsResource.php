<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RoomsResource\Pages;
use App\Filament\Resources\RoomsResource\RelationManagers;
use App\Models\Rooms;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RoomsResource extends Resource
{
    protected static ?string $model = Rooms::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('description')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('lean_weekday_price')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lean_weekend_price')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('peak_weekday_price')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\TextInput::make('peak_weekend_price')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('rooms')
                    ->label('Main Image'),
                Forms\Components\TextInput::make('amenities')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('num_guest')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('description')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lean_weekday_price')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('lean_weekend_price')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('peak_weekday_price')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\TextColumn::make('peak_weekend_price')
                    ->numeric()
                    ->searchable(),
                Tables\Columns\ImageColumn::make('image_url')
                    ->label('Image'),
                Tables\Columns\TextColumn::make('amenities')
                    ->searchable(),
                Tables\Columns\TextColumn::make('num_guest')
                    ->numeric()
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
            ->filters([
                //
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
            RelationManagers\GalleryImagesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRooms::route('/create'),
            'edit' => Pages\EditRooms::route('/{record}/edit'),
        ];
    }
}
