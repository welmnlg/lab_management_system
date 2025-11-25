<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\Lab\QrVerificationController;
use App\Http\Controllers\Api\ScheduleController;
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
    
    // ✅ Logbook API & Export
    Route::get('/logbook', function () {
        return view('logbook');
    })->name('logbook');
    Route::get('/api/logbook/data', [App\Http\Controllers\LogbookController::class, 'getLogbookData'])->name('api.logbook.data');
    Route::get('/api/logbook/filters', [App\Http\Controllers\LogbookController::class, 'getFilterOptions'])->name('api.logbook.filters');
    Route::get('/logbook/export', [App\Http\Controllers\LogbookController::class, 'exportLogbook'])->name('logbook.export');

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
    Route::post('/profile', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    
    // ========================================
    // KELOLA MATA KULIAH (Hanya untuk Admin/BPH)
    // ========================================
    Route::get('/kelola-matkul', [CourseController::class, 'index'])->name('kelola-matkul');
    Route::post('/kelola-matkul', [CourseController::class, 'store'])->name('kelola-matkul.store');
    Route::get('/kelola-matkul/{classId}/edit', [CourseController::class, 'edit'])->name('kelola-matkul.edit');
    Route::put('/kelola-matkul/{classId}', [CourseController::class, 'update'])->name('kelola-matkul.update');
    Route::delete('/kelola-matkul/{classId}', [CourseController::class, 'destroy'])->name('kelola-matkul.destroy');
    Route::post('/kelola-matkul/submit', [CourseController::class, 'submit'])->name('kelola-matkul.submit');

    Route::prefix('api/lab')->group(function () {
        Route::post('/qr-verify', [QrVerificationController::class, 'verifyQrCode'])->name('api.lab.qr-verify');
        Route::post('/confirm-entry', [QrVerificationController::class, 'confirmEntry'])->name('api.lab.confirm-entry');
        Route::post('/exit-room', [QrVerificationController::class, 'exitRoom'])->name('api.lab.exit-room');
    });
    Route::prefix('api/schedules')->group(function () {
        // Get user's schedules
        Route::get('/my-schedules', [ScheduleController::class, 'getMySchedules']);
        
        // Get schedule detail
        Route::get('/{id}', [ScheduleController::class, 'getScheduleDetail']);
        
        // Cancel schedule
        Route::post('/{id}/cancel', [ScheduleController::class, 'cancelSchedule']);
        
        // Confirm schedule
        Route::post('/{id}/confirm', [ScheduleController::class, 'confirmSchedule']);
        
        // Complete schedule
        Route::post('/{id}/complete', [ScheduleController::class, 'completeSchedule']);
        
        // Move to different room
        Route::post('/{id}/move-room', [ScheduleController::class, 'moveToRoom']);
    });
});

// Load auth routes (login, register, dll)
require __DIR__.'/auth.php';
