<?php

use App\Enums\UserRole;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\FormFieldController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:'.UserRole::Admin->value])
    ->prefix('admin')
    ->name('admin.')
    ->scopeBindings()
    ->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::patch('/forms/{form}/status', [FormController::class, 'updateStatus'])->name('forms.status');
        Route::resource('forms', FormController::class);
        Route::resource('forms.fields', FormFieldController::class);
    });
