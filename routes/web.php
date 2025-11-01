<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

// Root route
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// ========================================
// SEMUA ROUTE YANG BUTUH LOGIN
// ========================================
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Scan QR
    Route::get('/scan-qr', function () {
        return view('scanqr');
    })->name('scanqr');
    
    // Logbook
    Route::get('/logbook', function () {
        return view('logbook');
    })->name('logbook');

    // Ambil Jadwal - UNTUK ASLAB (User biasa)
    Route::get('/ambil-jadwal', function () {
        return view('ambil-jadwal');
    })->name('ambil-jadwal');

    // Ambil Jadwal - UNTUK ADMIN/BPH
    Route::get('/ambil-jadwal-admin', function () {
        return view('ambil-jadwal-admin');
    })->name('ambil-jadwal-admin');

    // Form Ambil Jadwal
    Route::get('/form-ambil-jadwal', function () {
        return view('form-ambil-jadwal');
    })->name('form-ambil-jadwal');
    
    // Kelola Jadwal
    Route::get('/kelola-jadwal', function () {
        return view('kelola-jadwal');
    })->name('kelola-jadwal');
    
    // Profil
    Route::get('/profil', function () {
        return view('profile');
    })->name('profil');
    
    // Notifikasi
    Route::get('/notifikasi', function () {
        return view('notifikasi');
    })->name('notifikasi');
    
    // ========================================
    // KELOLA PENGGUNA (Hanya untuk Admin/BPH)
    // ========================================
    Route::post('/kelola-pengguna/delete-multiple', [UserController::class, 'deleteMultiple'])
        ->name('kelola-pengguna.delete-multiple');
    Route::resource('kelola-pengguna', UserController::class);
    
    // ========================================
    // PROFILE ROUTES
    // ========================================
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // ========================================
    // KELOLA MATA KULIAH (Hanya untuk Admin/BPH)
    // ========================================
    Route::get('/kelola-matkul', [CourseController::class, 'index'])->name('kelola-matkul');
    Route::post('/kelola-matkul', [CourseController::class, 'store'])->name('kelola-matkul.store');
    Route::get('/kelola-matkul/{classId}/edit', [CourseController::class, 'edit'])->name('kelola-matkul.edit');
    Route::put('/kelola-matkul/{classId}', [CourseController::class, 'update'])->name('kelola-matkul.update');
    Route::delete('/kelola-matkul/{classId}', [CourseController::class, 'destroy'])->name('kelola-matkul.destroy');
    Route::post('/kelola-matkul/submit', [CourseController::class, 'submit'])->name('kelola-matkul.submit');
});

// Load auth routes (login, register, dll)
require __DIR__.'/auth.php';
