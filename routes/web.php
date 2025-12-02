<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
// Landing Page
Route::get('/', [LandingController::class, 'index'])->name('landing');
// Fallback Route
Route::fallback(function () {
    return redirect()->route('landing');
});
// Authentication Routes
require __DIR__.'/auth.php';
// Dashboard Routes
require __DIR__.'/student.php';
// Teacher Routes
require __DIR__.'/teacher.php';
// Admin Routes
require __DIR__.'/admin.php';
