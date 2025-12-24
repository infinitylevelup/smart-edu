<?php

use App\Http\Controllers\Parent\ParentDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('dashboard')
    ->middleware(['auth', 'role.selected'])
    ->group(function () {

        Route::prefix('parent')
            ->name('parent.')
            ->middleware('role:parent')
            ->group(function () {

                // Parent Dashboard
                Route::get('/', [ParentDashboardController::class, 'index'])
                    ->name('dashboard');

                // Help
                Route::get('/help', [ParentDashboardController::class, 'help'])
                    ->name('help');
            });
    });
