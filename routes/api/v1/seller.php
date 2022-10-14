<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Seller\SellerAuthController;


Route::controller(SellerAuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});