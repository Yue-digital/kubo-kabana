<?php
use App\Http\Controllers\PaymentController;

Route::post('/webhooks/paymongo', [PaymentController::class, 'handle']);
