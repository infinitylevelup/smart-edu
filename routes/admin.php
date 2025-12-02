<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin routes will be added here
|--------------------------------------------------------------------------
*/
Route::prefix('dashboard')
    ->middleware(['auth', 'role.selected', 'role:admin'])
    ->name('admin.')
    ->group(function () {
        // todo: add admin routes
    });
