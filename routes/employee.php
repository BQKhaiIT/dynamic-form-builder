<?php

use App\Enums\UserRole;
use App\Http\Controllers\Employee\FormController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:'.UserRole::Employee->value])
    ->prefix('employee')
    ->name('employee.')
    ->group(function () {
        Route::get('/forms', [FormController::class, 'index'])->name('forms.index');
        Route::get('/forms/{form}', [FormController::class, 'show'])->name('forms.show');
        Route::post('/forms/{form}/submit', [FormController::class, 'store'])->name('forms.submit');
    });
