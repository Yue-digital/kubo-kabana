<?php

namespace App\Filament\Resources\RoomsResource\Pages;

use App\Filament\Resources\RoomsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;

class EditRooms extends EditRecord
{
    protected static string $resource = RoomsResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                \Filament\Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('description')
                    ->maxLength(255)
                    ->default(null),
                \Filament\Forms\Components\TextInput::make('lean_weekday_price')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('lean_weekend_price')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('peak_weekday_price')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                \Filament\Forms\Components\TextInput::make('peak_weekend_price')
                    ->required()
                    ->numeric()
                    ->maxLength(255),
                \Filament\Forms\Components\FileUpload::make('image')
                    ->image()
                    ->directory('rooms')
                    ->label('Main Image')
                    ->imagePreviewHeight('250')
                    ->panelAspectRatio('2:1')
                    ->panelLayout('integrated')
                    ->deleteUploadedFileUsing(function ($file) {
                        \Storage::disk('public')->delete($file);
                    }),
                \Filament\Forms\Components\TextInput::make('amenities')
                    ->maxLength(255)
                    ->default(null),
                \Filament\Forms\Components\TextInput::make('num_guest')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
