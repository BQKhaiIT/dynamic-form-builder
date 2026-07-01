<?php

use App\Enums\UserRole;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = request()->user();

        return $user->role === UserRole::Admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('employee.forms.index');
    })->name('dashboard');
});

require __DIR__.'/admin.php';
require __DIR__.'/employee.php';
require __DIR__.'/auth.php';
