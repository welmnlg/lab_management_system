<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/scan-qr', function () {
    return view('scanqr');
})->name('scanqr');

Route::get('/logbook', function () {
    return view('logbook');
})->name('logbook');

Route::get('/profile', function () {
    return view('profile'); // Langsung tampilkan view profile.blade.php
})->name('profile.edit');


Route::middleware('auth')->group(function () {
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
