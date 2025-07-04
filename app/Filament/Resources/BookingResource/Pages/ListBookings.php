<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions\CreateAction;
use Filament\Tables\Actions\EditAction;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use App\Mail\BookingApprovedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Filament\Resources\Pages\Concerns\InteractsWithFormActions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Components\View;
use Carbon\Carbon;
use App\Filament\Widgets\BookingCalendar;

class ListBookings extends ListRecords
{
    use InteractsWithForms;

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
                    ->visible(fn ($record) => $record->status !== 'approved' && $record->status !== 'paid')
                    ->action(function ($record) {
                        $response = Http::withBasicAuth(env('PAYMONGO_SECRET_KEY'), '')
                            ->post('https://api.paymongo.com/v1/checkout_sessions', [
                                'data' => [
                                    'attributes' => [
                                        'send_email_receipt' => true,
                                        'show_description' => true,
                                        'show_line_items' => true,
                                        'description' => 'Book Reservation',
                                        'line_items' => [
                                            [
                                                'currency' => 'PHP',
                                                'amount' => (int) ($record->total * 100),
                                                'name' => 'Kubo Kabana',
                                                'quantity' => 1,
                                            ]
                                        ],
                                        'payment_method_types' => ['gcash', 'paymaya','brankas_bdo','brankas_landbank','brankas_metrobank'],
                                        'success_url' => route('payment.success', [
                                            'data' => encrypt([
                                                'booking_id' => $record->id,
                                                'booking_reference' => $record->booking_reference,
                                                'status' => 'success'
                                            ])
                                        ]),
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
                        } else {
                            $error = $response->json();
                            logger()->error('PayMongo Checkout Error', $error);
                            return view('pages.payment.error', ['error' => json_encode($error)]);
                        }
                        $record->update(['status' => 'approved']);

                        $record->checkout_url = $checkoutUrl;
                        Mail::to($record->email)->send(new BookingApprovedMail($record, $checkoutUrl));
                    }),
                Action::make('delete')
                    ->label('Delete')
                    ->icon('heroicon-m-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => $record->status === 'paid')
                    ->action(function ($record) {
                        $record->delete();
                    }),
            ]);
    }
}
