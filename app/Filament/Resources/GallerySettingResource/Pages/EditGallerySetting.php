<?php

namespace App\Filament\Resources\GallerySettingResource\Pages;

use App\Filament\Resources\GallerySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGallerySetting extends EditRecord
{
    protected static string $resource = GallerySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
