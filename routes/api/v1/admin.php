<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\Admin\AdminAuthController;


Route::controller(AdminAuthController::class)->group(function () {
    Route::post('/login', 'login');
    Route::post('/register', 'register');
});