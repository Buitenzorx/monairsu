<?php

use App\Http\Controllers\WaterLevelController;

// Route untuk halaman dashboard
Route::get('/dashboard', [WaterLevelController::class, 'index'])->name('dashboard');


// Route untuk halaman statis lainnya
Route::view('/', 'home')->name('home');
Route::view('/about', 'about')->name('about');
Route::get('/history', [WaterLevelController::class, 'history'])->name('history');
Route::view('/contact', 'contact')->name('contact');
Route::get('/api/water-level-data', [WaterLevelController::class, 'getWaterLevelData']);
Route::get('/download-report', [WaterLevelController::class, 'downloadReport'])->name('downloadReport');
// Route to get chart data
Route::get('/get-chart-data', [WaterLevelController::class, 'getChartData'])->name('getChartData');





