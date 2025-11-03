<?php

use App\Http\Controllers\Api\VerifyCfdiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/verify-cfdi', VerifyCfdiController::class);
