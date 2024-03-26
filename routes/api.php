<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ExpensesController;

//Open Routes
Route::post('/register', [ApiController::class, 'register']); //http://127.0.0.1:8000/api/register
Route::post('/login', [ApiController::class, 'login']);

//Protected Routes
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/profile', [ApiController::class, 'profile']);
    Route::apiResource('/expenses', (ExpensesController::class));
    Route::post('/logout', [ApiController::class, 'logout']);
});



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
