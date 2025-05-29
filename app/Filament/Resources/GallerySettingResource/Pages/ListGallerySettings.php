<?php

namespace App\Filament\Resources\GallerySettingResource\Pages;

use App\Filament\Resources\GallerySettingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGallerySettings extends ListRecords
{
    protected static string $resource = GallerySettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
