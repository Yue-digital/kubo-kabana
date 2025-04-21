<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/rooms', [RoomController::class, 'index'])->name('rooms');
Route::get('/rooms/{room}', [RoomController::class, 'show'])->name('rooms.show');

// Payment route
Route::get('/pay', function () {
    try {
        $response = Http::accept('application/json')->withBasicAuth('xnd_development_2nQJ3teo6aUVknsR74NjiIps4mmNHXfz2I8csL05uIEXJBmlJXp3ncYw5M0f','')->post('https://api.xendit.co/v2/invoices', [
            "external_id" => "payment-link-example",
            "amount" => 100000,
            "items" => [
              [
                "name" => "Air Conditioner",
                "quantity" => 1,
                "price" => 100000,
                "category" => "Electronic",
                "url" => "https://yourcompany.com/example_item"
              ]
              ],
              "success_redirect_url" => "https://yourcompany.com/success",
              "failure_redirect_url" => "https://yourcompany.com/failure",
          ]);

          return $response->json();
       } catch (\Throwable $th) {
         return $th->getMessage();
       }
});
