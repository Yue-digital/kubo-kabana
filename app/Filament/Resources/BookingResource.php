<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers;
use App\Models\Booking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\BookingResource\Pages\ViewBooking;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('full_name')
                    ->label('Full Name')
                    ->required()
                    ->disabled(fn ($livewire) => $livewire instanceof ViewBooking),
                Forms\Components\TextInput::make('email')
                    ->label('Email Address')
                    ->email()
                    ->required()
                    ->disabled(fn ($livewire) => $livewire instanceof ViewBooking),
                Forms\Components\TextInput::make('phone')
                    ->label('Phone Number')
                    ->required()
                    ->disabled(fn ($livewire) => $livewire instanceof ViewBooking),
                Forms\Components\DatePicker::make('check_in')
                    ->label('Check-in Date')
                    ->required()
                    ->disabled(fn ($livewire) => $livewire instanceof ViewBooking),
                Forms\Components\DatePicker::make('check_out')
                    ->label('Check-out Date')
                    ->required()
                    ->disabled(fn ($livewire) => $livewire instanceof ViewBooking),
                Forms\Components\Select::make('status')
                    ->label('Booking Status')
                    ->options([
                        'pending' => 'Pending',
                        'approved' => 'Approved',
                        'confirmed' => 'Confirmed',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->required()
                    ->disabled(fn ($livewire) => $livewire instanceof ViewBooking),
                Forms\Components\TextInput::make('total')
                    ->label('Total Amount')
                    ->numeric()
                    ->disabled(fn ($livewire) => $livewire instanceof ViewBooking),
                Forms\Components\TextInput::make('source')
                    ->label('Booking Source')
                    ->disabled(fn ($livewire) => $livewire instanceof ViewBooking),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'view' => Pages\ViewBooking::route('/{record}'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
