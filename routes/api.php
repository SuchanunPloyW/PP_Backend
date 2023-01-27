<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DB_CustomerController;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::group(['middleware' => ['auth:sanctum']], function () {
    //! <--------- logout ---------->
    Route::post('/logout', [AuthController::class, 'logout']);

    //! <--------- customer ---------->
    Route::resource('customer', DB_CustomerController::class);
    Route::post('customer/uid', [DB_CustomerController::class, 'get_customer']);
});

Route::get('/memo', [DB_CustomerController::class, 'get_customer_memo']);