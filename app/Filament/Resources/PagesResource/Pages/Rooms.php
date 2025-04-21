<?php

namespace App\Filament\Resources\PagesResource\Pages;

use App\Filament\Resources\PagesResource;
use Filament\Resources\Pages\Page;

class Rooms extends Page
{
    protected static string $resource = PagesResource::class;

    protected static string $view = 'filament.resources.pages-resource.pages.rooms';

    protected static ?string $navigationIcon = 'heroicon-o-document-text';


}
