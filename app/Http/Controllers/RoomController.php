<?php

namespace App\Http\Controllers;

use App\Models\Rooms;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Rooms::all();
        return view('pages.rooms.index', compact('rooms'));
    }

    public function show(Rooms $room)
    {
        return view('pages.rooms.show', compact('room'));
    }

    public function checkAvailability(Request $request)
    {
        $checkIn = $request->input('check_in');
        $checkOut = $request->input('check_out');

        // TODO: Add actual availability checking logic here
        $rooms = Rooms::first();

        return view('pages.rooms.index', compact('checkIn', 'checkOut', 'rooms'));
    }

    public function getBookedDates()
    {
        $bookings = Booking::where('status', '!=', 'cancelled')
            ->select('check_in', 'check_out')
            ->get();

        $bookedDates = [];
        foreach ($bookings as $booking) {
            $start = Carbon::parse($booking->check_in);
            $end = Carbon::parse($booking->check_out);
            
            // Add all dates in the range to bookedDates
            for ($date = $start; $date->lte($end); $date->addDay()) {
                $bookedDates[] = $date->format('Y-m-d');
            }
        }

        return response()->json($bookedDates);
    }

    public function importAirbnbCalendar(Request $request)
    {
        try {
            $request->validate([
                'airbnb_url' => 'required|url'
            ]);

            $airbnbUrl = $request->input('airbnb_url');
            
            // Extract listing ID from Airbnb URL
            preg_match('/rooms\/(\d+)/', $airbnbUrl, $matches);
            if (empty($matches[1])) {
                return response()->json(['error' => 'Invalid Airbnb URL format'], 400);
            }
            
            $listingId = $matches[1];
            
            // Make request to Airbnb API
            $response = Http::withHeaders([
                'X-Airbnb-API-Key' => config('services.airbnb.api_key'),
                'Accept' => 'application/json'
            ])->get("https://api.airbnb.com/v2/calendar_months", [
                'listing_id' => $listingId,
                'month' => Carbon::now()->format('Y-m'),
                'count' => 12, // Get next 12 months
                'currency' => 'USD'
            ]);

            if (!$response->successful()) {
                Log::error('Airbnb API Error', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return response()->json(['error' => 'Failed to fetch Airbnb calendar'], 400);
            }

            $calendarData = $response->json();
            $bookedDates = [];

            // Process calendar data
            foreach ($calendarData['calendar_months'] as $month) {
                foreach ($month['days'] as $day) {
                    if ($day['available'] === false) {
                        $bookedDates[] = Carbon::parse($day['date'])->format('Y-m-d');
                    }
                }
            }

            // Update local bookings
            foreach ($bookedDates as $date) {
                Booking::updateOrCreate(
                    [
                        'check_in' => $date,
                        'check_out' => Carbon::parse($date)->addDay()->format('Y-m-d'),
                    ],
                    [
                        'status' => 'blocked',
                        'source' => 'airbnb'
                    ]
                );
            }

            return response()->json([
                'message' => 'Airbnb calendar imported successfully',
                'booked_dates' => $bookedDates
            ]);

        } catch (\Exception $e) {
            Log::error('Airbnb Import Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to import Airbnb calendar: ' . $e->getMessage()], 500);
        }
    }
}
