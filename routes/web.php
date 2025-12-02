<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

require __DIR__.'/auth.php';
require __DIR__.'/student.php';
require __DIR__.'/teacher.php';
require __DIR__.'/admin.php';
