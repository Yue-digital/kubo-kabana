<?php

namespace App\Http\Controllers;

use App\Models\Booking;

class BookingEventController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('status', '!=', 'cancelled')->get();

        $events = $bookings->map(function ($booking) {
            return [
                'title' => $booking->full_name,
                'start' => $booking->check_in,
                'end' => $booking->check_out,
                'backgroundColor' => match ($booking->status) {
                    'pending' => '#f59e0b',
                    'approved' => '#10b981',
                    'confirmed' => '#3b82f6',
                    'paid' => '#6366f1',
                    default => '#6b7280',
                },
                'borderColor' => match ($booking->status) {
                    'pending' => '#f59e0b',
                    'approved' => '#10b981',
                    'confirmed' => '#3b82f6',
                    'paid' => '#6366f1',
                    default => '#6b7280',
                },
            ];
        });

        return response()->json($events);
    }
}