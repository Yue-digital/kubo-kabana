<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\DatePicker;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->label('Booking Name'),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->label('Email Address'),
                TextInput::make('phone')
                    ->required()
                    ->tel()
                    ->label('Phone Number'),
                DatePicker::make('check_in')
                    ->required()
                    ->label('Check-in Date'),
                DatePicker::make('check_out')
                    ->required()
                    ->label('Check-out Date'),
            ]);
    }
}
