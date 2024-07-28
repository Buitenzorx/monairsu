<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaterLevelController;

Route::get('/water-level', [WaterLevelController::class, 'getWaterLevel']);
Route::post('/water-level', [WaterLevelController::class, 'store']);
