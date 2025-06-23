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

        $room = Rooms::first();
        $checkInDate = \Carbon\Carbon::parse($checkIn);
        $checkOutDate = \Carbon\Carbon::parse($checkOut);
        
        // Check if dates fall within peak or lean season
        $isPeakSeason = false;
        if ($room->peak_season_start && $room->peak_season_end) {
            $peakStart = \Carbon\Carbon::parse($room->peak_season_start);
            $peakEnd = \Carbon\Carbon::parse($room->peak_season_end);
            
            // Function to compare dates ignoring year
            $isDateInRange = function($date, $start, $end) {
                $dateMonthDay = $date->format('m-d');
                $startMonthDay = $start->format('m-d');
                $endMonthDay = $end->format('m-d');
                
                // Handle case where peak season spans across new year
                if ($startMonthDay > $endMonthDay) {
                    return $dateMonthDay >= $startMonthDay || $dateMonthDay <= $endMonthDay;
                }
                
                return $dateMonthDay >= $startMonthDay && $dateMonthDay <= $endMonthDay;
            };
            
            // Check if any part of the stay falls within peak season
            $isPeakSeason = $isDateInRange($checkInDate, $peakStart, $peakEnd) || 
                           $isDateInRange($checkOutDate, $peakStart, $peakEnd) ||
                           ($checkInDate->lte($peakStart) && $checkOutDate->gte($peakEnd));
        }

        // Calculate price based on season and day of week
        $totalPrice = 0;
        $currentDate = $checkInDate->copy();
        
        while ($currentDate->lte($checkOutDate)) {
            $isWeekend = $currentDate->isWeekend();
            
            if ($isPeakSeason) {
                $price = $isWeekend ? $room->peak_weekend_price : $room->peak_weekday_price;
            } else {
                $price = $isWeekend ? $room->lean_weekend_price : $room->lean_weekday_price;
            }
            
            $totalPrice += $price;
            $currentDate->addDay();
        }

        $room->total_price = $totalPrice;
        $room->is_peak_season = $isPeakSeason;

        return view('pages.rooms.index', compact('checkIn', 'checkOut', 'room'));
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
            
            // Add all dates in the range to bookedDates, but exclude the checkout date
            // Guests check out in the morning, so the checkout date should be available for new bookings
            $currentDate = $start->copy();
            while ($currentDate->lt($end)) { // Use lt() instead of lte() to exclude checkout date
                $bookedDates[] = $currentDate->format('Y-m-d');
                $currentDate->addDay();
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

    public function getAirbnbDates(Request $request)
    {
        try {
            $request->validate([
                'airbnb_url' => 'required|url'
            ]);

            $airbnbUrl = $request->input('airbnb_url');
            
            // Extract listing ID from various Airbnb URL formats
            $listingId = null;
            $patterns = [
                '/rooms\/(\d+)/',  // Standard URL format
                '/\/listing\/(\d+)/', // Alternative URL format
                '/\/homes\/(\d+)/' // Another common format
            ];

            foreach ($patterns as $pattern) {
                if (preg_match($pattern, $airbnbUrl, $matches)) {
                    $listingId = $matches[1];
                    break;
                }
            }

            if (!$listingId) {
                return response()->json(['error' => 'Could not extract listing ID from URL'], 400);
            }
            
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

            return response()->json([
                'booked_dates' => $bookedDates
            ]);

        } catch (\Exception $e) {
            Log::error('Airbnb Fetch Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to fetch Airbnb dates: ' . $e->getMessage()], 500);
        }
    }
}
