<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    public function index()
    {
        return view('pages.reservation.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'check_in' => 'required|date',
            'check_out' => 'required|date|after:check_in',
            'number_of_kubos' => 'required|integer|min:1',
            'bonfire_usage' => 'required|boolean',
        ]);

        // Calculate total based on number of kubos and bonfire usage
        $kuboPrice = 1000; // Price per kubo per night
        $bonfirePrice = 500; // Price for bonfire
        
        $numberOfNights = ceil((strtotime($validated['check_out']) - strtotime($validated['check_in'])) / (60 * 60 * 24));
        $total = ($validated['number_of_kubos'] * $kuboPrice * $numberOfNights) + ($validated['bonfire_usage'] ? $bonfirePrice : 0);

        // Create reservation
        $reservation = Reservation::create([
            'name' => $validated['name'],
            'contact_number' => $validated['contact_number'],
            'email' => $validated['email'],
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'number_of_kubos' => $validated['number_of_kubos'],
            'bonfire_usage' => $validated['bonfire_usage'],
            'total_amount' => $total,
            'status' => 'pending'
        ]);

        // Redirect to payment with reservation details
        return redirect()->route('payment.index')->with([
            'reservation_id' => $reservation->id,
            'amount' => $total,
            'description' => 'Reservation for ' . $validated['number_of_kubos'] . ' kubo(s) from ' . $validated['check_in'] . ' to ' . $validated['check_out']
        ]);
    }
} 