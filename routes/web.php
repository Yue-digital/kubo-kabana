<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\PaymentController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about-us', [HomeController::class, 'about'])->name('about');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/rooms', [RoomController::class, 'index'])->name('rooms');
Route::get('/rooms/booked-dates', [RoomController::class, 'getBookedDates'])->name('rooms.booked-dates');
Route::post('/rooms/import-airbnb', [RoomController::class, 'importAirbnbCalendar'])->name('rooms.import-airbnb');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');
Route::get('/kubo-room', [RoomController::class, 'checkAvailability'])->name('kubo-room');

// Payment routes
Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
Route::post('/payment/book', [PaymentController::class, 'book'])->name('payment.book');
Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
Route::get('/payment/failure', [PaymentController::class, 'failure'])->name('payment.failure');
Route::post('validate-discount', [PaymentController::class, 'validateDiscount'])->name('validate.discount');

Route::post('/rooms/airbnb-dates', [RoomController::class, 'getAirbnbDates'])->name('rooms.airbnb-dates');