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

Route::get('/kelola-pengguna/tambah-pengguna', function () {
    return view('tambah-pengguna');
})->name('tambah-pengguna');

Route::get('/kelola-pengguna', function () {
    return view('kelola-pengguna');
})->name('admin');

Route::get('/kelola-matkul', function () {
    return view('kelola-matkul');
})->name('kelola-matkul');

Route::get('/scan-qr', function () {
    return view('scanqr');
})->name('scanqr');

Route::get('/logbook', function () {
    return view('logbook');
})->name('logbook');

Route::get('/ambil-jadwal', function () {
    return view('ambil-jadwal');
})->name('ambil-jadwal');

Route::get('/form-ambil-jadwal', function () {
    return view('form-ambil-jadwal');
})->name('form-ambil-jadwal');

Route::get('/kelola-jadwal', function () {
    return view('kelola-jadwal');
})->name('kelola-jadwal');

Route::get('/profil', function () {
    return view('profile');
})->name('profil');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::get('/notifikasi', function () {
    return view('notifikasi');
})->name('notifikasi');

