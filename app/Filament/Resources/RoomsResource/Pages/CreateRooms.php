<?php

namespace App\Filament\Resources\RoomsResource\Pages;

use App\Filament\Resources\RoomsResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class CreateRooms extends CreateRecord
{
    protected static string $resource = RoomsResource::class;

    public function form(Form $form): Form
{
    return $form
        ->schema([
            RichEditor::make('description'),
        ]);
}
}
