<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HotelsController;
use App\Http\Controllers\Api\RoomsController;


Route::apiResource('hotels', HotelsController::class);

Route::apiResource('hotels/{id}/rooms', RoomsController::class);

Route::get('rooms', [RoomsController::class, 'all']);
