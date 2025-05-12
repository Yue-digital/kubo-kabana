<?php

namespace App\Filament\Resources\RoomsResource\Pages;

use App\Filament\Resources\RoomsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;

class CreateRooms extends CreateRecord
{
    protected static string $resource = RoomsResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                RichEditor::make('description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('lean_weekday_price')
                    ->required()
                    ->numeric()
                    ->label('Lean Season Weekday Price'),
                TextInput::make('lean_weekend_price')
                    ->required()
                    ->numeric()
                    ->label('Lean Season Weekend Price'),
                TextInput::make('peak_weekday_price')
                    ->required()
                    ->numeric()
                    ->label('Peak Season Weekday Price'),
                TextInput::make('peak_weekend_price')
                    ->required()
                    ->numeric()
                    ->label('Peak Season Weekend Price'),
                FileUpload::make('image')
                    ->image()
                    ->directory('rooms')
                    ->label('Main Image')
                    ->required(),
                FileUpload::make('gallery_images')
                    ->multiple()
                    ->image()
                    ->imageResizeMode('cover')
                    ->imageCropAspectRatio('16:9')
                    ->imageResizeTargetWidth('1920')
                    ->imageResizeTargetHeight('1080')
                    ->directory('rooms/gallery')
                    ->label('Gallery Images')
                    ->columnSpanFull(),
                TextInput::make('amenities')
                    ->maxLength(255)
                    ->label('Amenities (comma-separated)'),
                TextInput::make('num_guest')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->label('Maximum Number of Guests'),
            ]);
    }

    protected function afterCreate(): void
    {
        $room = $this->record;
        $galleryImages = $this->data['gallery_images'] ?? [];

        foreach ($galleryImages as $index => $imagePath) {
            $room->galleryImages()->create([
                'image_path' => $imagePath,
                'order' => $index
            ]);
        }
    }
}
