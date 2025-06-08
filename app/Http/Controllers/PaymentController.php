<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use App\Models\Rooms;
use App\Models\Discount;
use Carbon\Carbon;
use App\Mail\BookingConfirmationMail;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $checkIn = $request->query('checkIn');
        $checkOut = $request->query('checkOut');
        $rooms = Rooms::first();
        $discountCode = $request->query('discount_code');
        $discount = null;
        $discountAmount = 0;

        if ($checkIn && $checkOut && $rooms) {
            $totalPrice = $this->calculatePrice($rooms, $checkIn, $checkOut);

            if ($discountCode) {
                $discount = Discount::where('code', $discountCode)->first();
                if ($discount && $discount->isValid()) {
                    $discountAmount = $discount->calculateDiscount($totalPrice);
                    $totalPrice -= $discountAmount;
                }
            }

            $rooms->total_price = $totalPrice;
            $rooms->discount_amount = $discountAmount;
            $rooms->original_price = $totalPrice + $discountAmount;
        }

        return view('pages.payment.book', compact('checkIn', 'checkOut', 'rooms', 'discountCode'));
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

        // Function to check if a date falls within a season range
        $isDateInSeason = function(Carbon $date, $seasonStart, $seasonEnd) {
            if (!$seasonStart || !$seasonEnd) {
                return false;
            }

            $dateMonthDay = $date->format('m-d');
            $startMonthDay = Carbon::parse($seasonStart)->format('m-d');
            $endMonthDay = Carbon::parse($seasonEnd)->format('m-d');

            // Handle case where season spans across new year
            if ($startMonthDay > $endMonthDay) {
                return $dateMonthDay >= $startMonthDay || $dateMonthDay <= $endMonthDay;
            }

            return $dateMonthDay >= $startMonthDay && $dateMonthDay <= $endMonthDay;
        };

        // Calculate price for each night
        for ($i = 0; $i < $nights; $i++) {
            $isWeekend = $currentDate->isWeekend(); // Saturday or Sunday
            $isPeak = $isDateInSeason($currentDate, $room->peak_season_start, $room->peak_season_end);

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

    public function validateDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'amount' => 'required|numeric|min:0'
        ]);

        $discount = Discount::where('code', $request->code)->first();

        if (!$discount) {
            return response()->json([
                'valid' => false,
                'message' => 'Invalid discount code.'
            ]);
        }

        if (!$discount->isValid()) {
            return response()->json([
                'valid' => false,
                'message' => 'Discount code is not valid or has expired.'
            ]);
        }

        $discountAmount = $discount->calculateDiscount($request->amount);
        $finalAmount = $request->amount - $discountAmount;

        return response()->json([
            'valid' => true,
            'discount_amount' => $discountAmount,
            'final_amount' => $finalAmount,
            'discount' => [
                'code' => $discount->code,
                'type' => $discount->type,
                'value' => $discount->value
            ]
        ]);
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
            'discount_code' => 'nullable|string',
            'original_amount' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0',
            'add_adult' => 'nullable|integer|min:0',
            'add_child' => 'nullable|integer|min:0',
            'pet' => 'nullable|integer|min:0',
        ]);

        $discount = null;
        if ($request->discount_code) {
            $discount = Discount::where('code', $request->discount_code)->first();
            if ($discount && $discount->isValid()) {
                $discount->increment('used_count');
            }
        }

        // Store booking information in the session or database
        $booking = Booking::create([
            'full_name' => $request->input('name'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'check_in' => $request->input('check_in'),
            'check_out' => $request->input('check_out'),
            'total' => $request->input('total_amount'),
            'original_amount' => $request->input('original_amount'),
            'discount_amount' => $request->input('discount_amount'),
            'discount_code' => $request->input('discount_code'),
            'additional_adults' => $request->input('add_adult', 0),
            'additional_children' => $request->input('add_child', 0),
            'additional_pets' => $request->input('pet', 0),
            'booking_reference' => 'BK' . strtoupper(substr(uniqid(), -6)),
            'status' => 'pending',
        ]);

        // Send confirmation email
        try {
            Mail::to($booking->email)->send(new BookingConfirmationMail($booking));
        } catch (\Exception $e) {
            Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
        }

        return redirect()->route('payment.success');
    }

    public function processPayment(Request $request)
    {
        try {
            $originalAmount = $request->input('amount', 100000);
            $discountCode = $request->input('discount_code');
            $finalAmount = $originalAmount;
            $discountAmount = 0;
            $discountDetails = null;

            // Calculate additional costs
            $additionalAdults = $request->input('additional_adults', 0);
            $additionalChildren = $request->input('additional_children', 0);
            $additionalPets = $request->input('additional_pets', 0);

            // Define rates (you may want to move these to a configuration file)
            $adultRate = 50000; // 50,000 per additional adult
            $childRate = 25000; // 25,000 per additional child
            $petRate = 15000;   // 15,000 per additional pet

            // Calculate additional costs
            $additionalCosts = ($additionalAdults * $adultRate) +
                             ($additionalChildren * $childRate) +
                             ($additionalPets * $petRate);

            // Add additional costs to original amount
            $originalAmount += $additionalCosts;
            $finalAmount = $originalAmount;

            // Apply discount if code is provided
            if ($discountCode) {
                $discount = Discount::where('code', $discountCode)->first();
                if ($discount && $discount->isValid()) {
                    $discountAmount = $discount->calculateDiscount($originalAmount);
                    $finalAmount = $originalAmount - $discountAmount;
                    $discountDetails = [
                        'code' => $discount->code,
                        'type' => $discount->type,
                        'value' => $discount->value,
                        'amount' => $discountAmount
                    ];
                }
            }

            $response = Http::accept('application/json')
                ->withBasicAuth('xnd_development_2nQJ3teo6aUVknsR74NjiIps4mmNHXfz2I8csL05uIEXJBmlJXp3ncYw5M0f', '')
                ->post('https://api.xendit.co/v2/invoices', [
                    "external_id" => "payment-" . time(),
                    "amount" => $finalAmount,
                    "description" => $request->input('description', 'Room Booking Payment'),
                    "items" => [
                        [
                            "name" => $request->input('item_name', 'Room Booking'),
                            "quantity" => 1,
                            "price" => $originalAmount,
                            "category" => "Accommodation",
                        ]
                    ],
                    "fees" => array_merge(
                        $discountAmount > 0 ? [
                            [
                                "type" => "Discount",
                                "value" => -$discountAmount,
                                "description" => "Discount Code: " . ($discountDetails['code'] ?? '')
                            ]
                        ] : [],
                        $additionalCosts > 0 ? [
                            [
                                "type" => "Additional Costs",
                                "value" => $additionalCosts,
                                "description" => sprintf(
                                    "Additional guests: %d adults, %d children, %d pets",
                                    $additionalAdults,
                                    $additionalChildren,
                                    $additionalPets
                                )
                            ]
                        ] : []
                    ),
                    "success_redirect_url" => route('payment.success'),
                    "failure_redirect_url" => route('payment.failure'),
                ]);

            if ($response->successful()) {
                // Store booking with discount information
                $booking = Booking::create([
                    'full_name' => $request->input('name'),
                    'email' => $request->input('email'),
                    'phone' => $request->input('phone'),
                    'check_in' => $request->input('check_in'),
                    'check_out' => $request->input('check_out'),
                    'total' => $finalAmount,
                    'original_amount' => $originalAmount,
                    'discount_amount' => $discountAmount,
                    'discount_code' => $discountCode,
                    'additional_adults' => $request->input('additional_adults', 0),
                    'additional_children' => $request->input('additional_children', 0),
                    'additional_pets' => $request->input('additional_pets', 0),
                    'status' => 'pending',
                ]);

                // Increment discount usage if applied
                if ($discountCode && $discountAmount > 0) {
                    Discount::where('code', $discountCode)->increment('used_count');
                }

                return redirect($response->json()['invoice_url']);
            }

            return back()->with('error', 'Payment processing failed. Please try again.');
        } catch (\Throwable $th) {
            Log::error('Payment processing error: ' . $th->getMessage());
            return back()->with('error', 'An error occurred. Please try again later.');
        }
    }

    public function success(Request $request)
    {
        $data = $request->query('data');
        $data = decrypt($data);

        $booking = Booking::find($data['booking_id']);

        if($booking['booking_reference'] == $data['booking_reference'] && $booking->status != 'paid') {
            $booking->status = 'paid';
            $booking->updated_at = now();
            $booking->save();

            // Send confirmation email
            try {
                Mail::to($booking->email)->send(new \App\Mail\BookingReservationConfirmation($booking));
            } catch (\Exception $e) {
                Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
            }
        }

        return view('pages.payment.success', compact('data'));
    }

    public function failure()
    {
        return view('pages.payment.failure');
    }

    public function handle(Request $request)
    {
        // Verify Paymongo signature
        $signature = $request->header('Paymongo-Signature');
        if (!$signature) {
            Log::error('PayMongo Webhook: Missing signature');
            return response()->json(['error' => 'Missing signature'], 400);
        }

        $payload = $request->getContent();
        $computedSignature = hash_hmac('sha256', $payload, env('PAYMONGO_WEBHOOK_SECRET'));
        
        if (!hash_equals($signature, $computedSignature)) {
            Log::error('PayMongo Webhook: Invalid signature');
            return response()->json(['error' => 'Invalid signature'], 400);
        }

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
                    try {
                        $booking->status = 'paid';
                        $booking->save();
                        Log::info('Booking status updated to paid', ['booking_id' => $bookingId]);
                    } catch (\Exception $e) {
                        Log::error('Failed to update booking status', [
                            'booking_id' => $bookingId,
                            'error' => $e->getMessage()
                        ]);
                        return response()->json(['error' => 'Failed to update booking status'], 500);
                    }
                } else {
                    Log::warning('Booking not found or already paid', ['booking_id' => $bookingId]);
                }
            } else {
                Log::warning('No booking_id found in webhook metadata');
            }
        }

        return response()->json(['status' => 'ok'], 200);
    }
}
