<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GallerySettingResource\Pages;
use App\Filament\Resources\GallerySettingResource\RelationManagers;
use App\Models\GallerySetting;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GallerySettingResource extends Resource
{
    protected static ?string $model = GallerySetting::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('image')
                    ->image()
                    ->disk('public')
                    ->directory('uploads')
                    ->visibility('public')
                    ->required()
                    ->maxSize(20480) // 20 MB
                    ->imagePreviewHeight('250')
                    ->deleteUploadedFileUsing(function ($file) {
                        \Storage::disk('public')->delete($file);
                    })
                    ->label('Image'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\ImageColumn::make('image')
                    ->label('Image'),
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
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGallerySettings::route('/'),
            'create' => Pages\CreateGallerySetting::route('/create'),
            'edit' => Pages\EditGallerySetting::route('/{record}/edit'),
        ];
    }
}
