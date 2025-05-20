<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'booking' => $this->record,
        ];
    }

    protected function getContentBlocks(): array
    {
        return [
            'details' => [
                'heading' => 'Booking Details',
                'description' => 'View the complete booking information',
                'schema' => [
                    'full_name' => [
                        'label' => 'Full Name',
                        'type' => 'text',
                    ],
                    'email' => [
                        'label' => 'Email Address',
                        'type' => 'text',
                    ],
                    'phone' => [
                        'label' => 'Phone Number',
                        'type' => 'text',
                    ],
                    'check_in' => [
                        'label' => 'Check-in Date',
                        'type' => 'date',
                    ],
                    'check_out' => [
                        'label' => 'Check-out Date',
                        'type' => 'date',
                    ],
                    'status' => [
                        'label' => 'Booking Status',
                        'type' => 'text',
                        'format' => function ($value) {
                            return match ($value) {
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'confirmed' => 'Confirmed',
                                'paid' => 'Paid',
                                'cancelled' => 'Cancelled',
                                default => 'Unknown',
                            };
                        },
                    ],
                    'total' => [
                        'label' => 'Total Amount',
                        'type' => 'text',
                        'format' => function ($value) {
                            return '₱' . number_format($value, 2);
                        },
                    ],
                    'source' => [
                        'label' => 'Booking Source',
                        'type' => 'text',
                    ],
                ],
            ],
        ];
    }
}
