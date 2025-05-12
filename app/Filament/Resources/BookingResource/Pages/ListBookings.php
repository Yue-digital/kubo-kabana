<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use App\Mail\BookingApprovedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;


class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('full_name'),
                TextColumn::make('email'),
                TextColumn::make('phone'),
                TextColumn::make('check_in')->dateTime(),
                TextColumn::make('check_out')->dateTime(),
                TextColumn::make('status')
                    ->label('Booking Status')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'pending' => 'Pending',
                            'approved' => 'Approved',
                            'confirmed' => 'Confirmed',
                            'paid' => 'Paid',
                            'cancelled' => 'Cancelled',
                            default => 'Unknown',
                        };
                    }),
            ])
            ->actions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status !== 'approved') // only show if not approved
                    ->action(function ($record) {
                        $response = Http::withBasicAuth(env('PAYMONGO_SECRET_KEY'), '')
                            ->post('https://api.paymongo.com/v1/checkout_sessions', [
                                'data' => [
                                    'attributes' => [
                                        'send_email_receipt' => true,
                                        'show_description' => true,
                                        'show_line_items' => true,
                                        'description' => 'Purchase of Test Product',
                                        'line_items' => [
                                            [
                                                'currency' => 'PHP',
                                                'amount' => 50000, // 500.00 PHP
                                                'name' => 'Test Product',
                                                'quantity' => 1,
                                            ]
                                        ],
                                        'payment_method_types' => ['gcash', 'card', 'paymaya'],
                                        'success_url' => route('payment.success'),
                                        'cancel_url' => route('payment.failure'),
                                        'metadata' => [
                                            'booking_id' => $record->id,
                                            'custom_note' => 'Urgent booking',
                                        ]
                                    ]
                                ]
                            ]);

                        if ($response->successful()) {
                            $checkoutUrl = $response->json()['data']['attributes']['checkout_url'];
                        }else{
                            $error = $response->json();
                            logger()->error('PayMongo Checkout Error', $error);
                            return view('pages.payment.error', ['error' => json_encode($error)]);
                            // return back()->with('error', 'Payment processing failed. Please try again.');
                        }
                        $record->update(['status' => 'approved']);

                        $record->checkout_url = $checkoutUrl;
                        Mail::to($record->email)->send(new BookingApprovedMail($record, $checkoutUrl));

                    }),
            ]);
    }

    
}
