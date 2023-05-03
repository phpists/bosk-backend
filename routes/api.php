<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\InvoiceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

Route::prefix('/users')->group(function () {
    Route::post('/', [\App\Http\Controllers\AuthController::class, 'register']);
    Route::post('/sign_in', [\App\Http\Controllers\AuthController::class, 'login']);
    Route::middleware('auth:api')->group( function () {
        Route::post('/refresh_token', [\App\Http\Controllers\AuthController::class, 'refresh_token']);
        Route::post('/sign_out', [\App\Http\Controllers\AuthController::class, 'logout']);
    });
});
Route::controller(CustomerController::class)->group(function() {
    Route::get('/customers', 'index');
    Route::post('/customers', 'store');
    Route::get('/customers/{customer_id}', 'show');
    Route::delete('/customers/{customer_id}', 'destroy');
    Route::put('/customers/{customer_id}', 'update');
    Route::get('/count_customers', 'count_customers');
});

Route::middleware('auth:api')->get('/countries', function () {
    $countries = DB::table('countries')->select('country_code', 'country_name')->get();
    $country_data = [];
    foreach ($countries as $country) {
        $country_data[] = [$country->country_code, $country->country_name];
    }
   return response()->json($country_data);
});

Route::controller(InvoiceController::class)->group(function() {
    Route::get('/invoices', 'index');
    Route::post('/invoices', 'store');
    Route::get('/invoices/{id}', 'show');
    Route::put('/invoices/{id}', 'update');
    Route::get('/count_invoices', 'count_invoices');
    Route::put('/change_status/{id}', 'change_status');
    Route::put('/invoice/change_status/{id}', 'change_status');
    Route::get('/invoices_summary', 'invoices_summary');
    Route::get('/invoices_chart', 'invoices_chart');
});

//Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);
Route::middleware('auth:api')->delete('/sign_out', [\App\Http\Controllers\AuthController::class, 'logout']);
//Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
Route::get('/profile', [\App\Http\Controllers\UserController::class, 'profile']);
Route::put('/profile', [\App\Http\Controllers\UserController::class, 'update_profile']);
