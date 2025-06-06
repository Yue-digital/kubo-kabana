<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use App\Models\Booking;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class BookingCalendar extends Widget
{
    protected static string $view = 'filament.widgets.booking-calendar';

    public static function getAssets(): array
    {
        return [
            Css::make('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css'),
            Js::make('fullcalendar', 'https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'),
        ];
    }

    public function getEvents(): array
    {
        $bookings = Booking::query()
            ->where('status', '!=', 'cancelled')
            ->get();

        $events = [];
        foreach ($bookings as $booking) {
            $events[] = [
                'title' => $booking->full_name,
                'start' => $booking->check_in,
                'end' => $booking->check_out,
                'color' => match ($booking->status) {
                    'pending' => '#f59e0b',
                    'approved' => '#10b981',
                    'confirmed' => '#3b82f6',
                    'paid' => '#6366f1',
                    default => '#6b7280',
                },
            ];
        }

        return $events;
    }
} 