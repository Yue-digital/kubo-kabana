<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use App\Models\Rooms;
use Carbon\Carbon;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $checkIn = $request->query('checkIn');
        $checkOut = $request->query('checkOut');
        $rooms = Rooms::first();

        if ($checkIn && $checkOut && $rooms) {
            $totalPrice = $this->calculatePrice($rooms, $checkIn, $checkOut);
            $rooms->total_price = $totalPrice;
        }

        return view('pages.payment.book', compact('checkIn', 'checkOut', 'rooms'));
    }

    /**
     * Calculate the total price based on check-in, check-out dates and room type
     *
     * @param Rooms $room
     * @param string $checkIn
     * @param string $checkOut
     * @return float
     */
    private function calculatePrice(Rooms $room, string $checkIn, string $checkOut)
    {
        $checkInDate = Carbon::parse($checkIn);
        $checkOutDate = Carbon::parse($checkOut);

        // Get total number of nights
        $nights = $checkInDate->diffInDays($checkOutDate);

        // If 0 nights, make it at least 1 night
        if ($nights < 1) {
            $nights = 1;
        }

        $totalPrice = 0;
        $currentDate = clone $checkInDate;

        // Check if within peak season (April to June, December)
        $isPeakSeason = function(Carbon $date) {
            $month = $date->month;
            return ($month >= 4 && $month <= 6) || $month == 12;
        };

        // Calculate price for each night
        for ($i = 0; $i < $nights; $i++) {
            $isWeekend = $currentDate->isWeekend(); // Saturday or Sunday
            $isPeak = $isPeakSeason($currentDate);

            if ($isPeak) {
                $price = $isWeekend ? $room->peak_weekend_price : $room->peak_weekday_price;
            } else {
                $price = $isWeekend ? $room->lean_weekend_price : $room->lean_weekday_price;
            }

            $totalPrice += $price;
            $currentDate->addDay();
        }

        return $totalPrice;
    }

    public function book(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:15',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'total_amount' => 'required|numeric|min:0',
        ]);

        // Store booking information in the session or database
        Booking::create([
            'full_name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'total' => $request->input('total_amount'),
            'status' => 'pending',
        ]);

        return redirect()->route('payment.success');
    }

    public function processPayment(Request $request)
    {
        try {
            $response = Http::accept('application/json')
                ->withBasicAuth('xnd_development_2nQJ3teo6aUVknsR74NjiIps4mmNHXfz2I8csL05uIEXJBmlJXp3ncYw5M0f', '')
                ->post('https://api.xendit.co/v2/invoices', [
                    "external_id" => "payment-" . time(),
                    "amount" => $request->input('amount', 100000),
                    "description" => $request->input('description', 'Room Booking Payment'),
                    "items" => [
                        [
                            "name" => $request->input('item_name', 'Room Booking'),
                            "quantity" => 1,
                            "price" => $request->input('amount', 100000),
                            "category" => "Accommodation",
                        ]
                    ],
                    "success_redirect_url" => route('payment.success'),
                    "failure_redirect_url" => route('payment.failure'),
                ]);

            if ($response->successful()) {
                return redirect($response->json()['invoice_url']);
            }

            return back()->with('error', 'Payment processing failed. Please try again.');
        } catch (\Throwable $th) {
            return back()->with('error', 'An error occurred. Please try again later.');
        }
    }

    public function success()
    {
        return view('pages.payment.success');
    }

    public function failure()
    {
        return view('pages.payment.failure');
    }

    public function handle(Request $request)
    {
        $payload = $request->all();

        // Log the raw payload for debugging
        Log::info('PayMongo Webhook Received:', $payload);

        $eventType = $payload['data']['type'] ?? null;
        $attributes = $payload['data']['attributes'] ?? [];
        $metadata = $attributes['metadata'] ?? [];

        if ($eventType === 'checkout.session.paid') {
            $bookingId = $metadata['booking_id'] ?? null;
            $userId = $metadata['user_id'] ?? null;

            if ($bookingId) {
                $booking = Booking::find($bookingId);
                if ($booking && $booking->status !== 'paid') {
                    $booking->status = 'paid';
                    $booking->save();
                }
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }
}
