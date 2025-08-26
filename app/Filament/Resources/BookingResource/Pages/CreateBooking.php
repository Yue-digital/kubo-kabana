<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use App\Models\Booking;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('full_name')
                    ->required()
                    ->label('Full Name'),
                TextInput::make('email')
                    ->required()
                    ->email()
                    ->label('Email Address'),
                TextInput::make('phone')
                    ->required()
                    ->tel()
                    ->label('Phone Number'),
                DateTimePicker::make('check_in')
                    ->required()
                    ->label('Check-in Date')
                    ->minDate(now()->addDays(1))
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->firstDayOfWeek(1),
                    // ->disabledDates(function () {
                    //     $bookedDates = [];
                    //     $bookings = Booking::query()
                    //         ->where('check_out', '>=', now())
                    //         ->get(['check_in', 'check_out']);

                    //     foreach ($bookings as $booking) {
                    //         $currentDate = $booking->check_in->copy();
                    //         while ($currentDate->lt($booking->check_out)) {
                    //             $bookedDates[] = $currentDate->format('Y-m-d');
                    //             $currentDate->addDay();
                    //         }
                    //     }

                    //     return array_unique($bookedDates);
                    // }),
                DateTimePicker::make('check_out')
                    ->required()
                    ->label('Check-out Date')
                    ->minDate(fn ($get) => $get('check_in') ? \Carbon\Carbon::parse($get('check_in'))->addDay() : now()->addDays(2))
                    ->displayFormat('d/m/Y')
                    ->native(false)
                    ->firstDayOfWeek(1),
                    // ->disabledDates(function () {
                    //     $bookedDates = [];
                    //     $bookings = Booking::query()
                    //         ->where('check_out', '>=', now())
                    //         ->get(['check_in', 'check_out']);

                    //     foreach ($bookings as $booking) {
                    //         $currentDate = $booking->check_in->copy();
                    //         while ($currentDate->lt($booking->check_out)) {
                    //             $bookedDates[] = $currentDate->format('Y-m-d');
                    //             $currentDate->addDay();
                    //         }
                    //     }

                    //     return array_unique($bookedDates);
                    // }),
                Select::make('status')
                    ->required()
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                    ])
            ]);
    }
}
