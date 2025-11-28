<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SemesterPeriodController;
// use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\LogbookController;
use App\Http\Controllers\QRController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Lab\QrVerificationController;
use Illuminate\Support\Facades\Route;

// Redirect root
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Routes yang butuh login
Route::middleware(['auth'])->group(function () {
    
    // ========================================
    // DASHBOARD & MAIN PAGES
    // ========================================
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Scan QR
    Route::get('/scan-qr', function () {
        return view('scanqr');
    })->name('scanqr');
    
    // Logbook API & Export
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
    // KELOLA PENGGUNA (Admin/BPH only)
    // ========================================
    Route::prefix('kelola-pengguna')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('kelola-pengguna.index');
        Route::post('/', [UserController::class, 'store'])->name('kelola-pengguna.store');
        Route::get('/create', [UserController::class, 'create'])->name('kelola-pengguna.create');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('kelola-pengguna.edit');
        Route::put('/{user}', [UserController::class, 'update'])->name('kelola-pengguna.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->name('kelola-pengguna.destroy');
        Route::post('/delete-multiple', [UserController::class, 'deleteMultiple'])->name('kelola-pengguna.delete-multiple');
        Route::get('/{user}', [UserController::class, 'show'])->name('kelola-pengguna.show');
    });

    // ========================================
    // SEMESTER PERIOD ROUTES
    // ========================================
    Route::prefix('semester-periods')->group(function () {
        Route::get('/active', [SemesterPeriodController::class, 'getActivePeriod'])->name('semester-periods.active');
        Route::post('/', [SemesterPeriodController::class, 'store'])->name('semester-periods.store');
        Route::post('/close', [SemesterPeriodController::class, 'closeSemester'])->name('semester-periods.close');
        Route::get('/', [SemesterPeriodController::class, 'index'])->name('semester-periods.index');
        Route::post('/open-schedule-taking', [SemesterPeriodController::class, 'openScheduleTaking'])->name('semester-periods.open-schedule-taking');
        Route::post('/close-schedule-taking', [SemesterPeriodController::class, 'closeScheduleTaking'])->name('semester-periods.close-schedule-taking');
    });

    // ========================================
    // Profile routes
    // ========================================
    Route::prefix('profile')->group(function () {
        Route::put('/password', [ProfileController::class, 'updatePassword']);
        Route::get('/', [ProfileController::class, 'getProfile']);
        Route::put('/', [ProfileController::class, 'updateProfile']);
    });

    // ========================================
    // NOTIFICATION ROUTES
    // ========================================
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
        Route::post('/confirm', [NotificationController::class, 'confirm'])->name('notifications.confirm');
        Route::post('/confirm-all', [NotificationController::class, 'confirmAll'])->name('notifications.confirm-all');
        Route::delete('/{notification_id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });

    // ========================================
    // ROOM ROUTES
    // ========================================
    Route::prefix('rooms')->group(function () {
        Route::get('/{room}/schedules', [RoomController::class, 'getSchedules'])->name('rooms.schedules');
        Route::post('/', [RoomController::class, 'store'])->name('rooms.store');
        Route::get('/', [RoomController::class, 'index'])->name('rooms.index');
    });

    // ========================================
    // SCHEDULE ROUTES (LENGKAP)
    // ========================================
    Route::prefix('schedules')->group(function () {
        // Get schedules
        Route::get('/room/{roomId}', [ScheduleController::class, 'getSchedulesByRoom'])->name('schedules.room');
        Route::get('/user-courses', [ScheduleController::class, 'getUserCourses'])->name('schedules.user-courses');
        Route::get('/user-courses-active', [ScheduleController::class, 'getUserCoursesActive'])->name('schedules.user-courses-active');
        Route::get('/rooms', [ScheduleController::class, 'getRoomsByFaculty'])->name('schedules.rooms');
        Route::get('/buildings-rooms', [ScheduleController::class, 'getBuildingsWithRooms'])->name('schedules.buildings-rooms');
        Route::get('/my-schedules', [ScheduleController::class, 'getUserSchedules'])->name('schedules.my');
        Route::get('/all', [ScheduleController::class, 'getAllSchedules'])->name('schedules.all');
        Route::get('/statistics', [ScheduleController::class, 'getStatistics'])->name('schedules.statistics');
        
        // Time slots
        Route::get('/available-slots', [ScheduleController::class, 'getAvailableTimeSlots'])->name('schedules.available-slots');
        
        // CRUD operations
        Route::post('/book', [ScheduleController::class, 'store'])->name('schedules.book');
        Route::delete('/{scheduleId}', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
        Route::post('/', [ScheduleController::class, 'store'])->name('schedules.store');
        Route::get('/{scheduleId}', [ScheduleController::class, 'show'])->name('schedules.show');
        Route::put('/{scheduleId}', [ScheduleController::class, 'update'])->name('schedules.update');
    });

    // ========================================
    // COURSE ROUTES
    // ========================================
    Route::prefix('courses')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('courses.index');
        Route::post('/', [CourseController::class, 'store'])->name('courses.store');
        Route::get('/{courseId}/classes', [CourseController::class, 'getClassesByCourse'])->name('courses.classes');
        Route::get('/check-code', [CourseController::class, 'checkCourseCode'])->name('courses.check-code');
    });

    // ========================================
    // LOGBOOK ROUTES
    // ========================================
    Route::prefix('logbooks')->group(function () {
        Route::get('/', [LogbookController::class, 'index'])->name('logbooks.index');
        Route::post('/', [LogbookController::class, 'store'])->name('logbooks.store');
        Route::put('/{logbook}', [LogbookController::class, 'update'])->name('logbooks.update');
        Route::get('/user/{userId}', [LogbookController::class, 'getUserLogbooks'])->name('logbooks.user');
        Route::get('/today', [LogbookController::class, 'getTodayLogbooks'])->name('logbooks.today');
    });

    // ========================================
    // QR CODE ROUTES
    // ========================================
    Route::prefix('qr')->group(function () {
        Route::post('/scan', [QRController::class, 'scan'])->name('qr.scan');
        Route::post('/login', [QRController::class, 'login'])->name('qr.login');
        Route::post('/logout', [QRController::class, 'logout'])->name('qr.logout');
        Route::get('/generate/{roomId}', [QRController::class, 'generate'])->name('qr.generate');
    });

    // ========================================
    // API ROUTES (untuk AJAX calls)
    // ========================================
    Route::prefix('api')->group(function () {
        // Notifications
        Route::get('/notifications', [NotificationController::class, 'index']);
        Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
        Route::post('/notifications/confirm', [NotificationController::class, 'confirm']);
        Route::post('/notifications/confirm-all', [NotificationController::class, 'confirmAll']);
        Route::delete('/notifications/{notification_id}', [NotificationController::class, 'destroy']);

        // Rooms API
        Route::get('/rooms', [RoomController::class, 'index']);
        Route::post('/rooms', [RoomController::class, 'store']);
        Route::get('/rooms/{room}/schedules', [RoomController::class, 'getSchedules']);
        
        // Semester Periods API
        Route::get('/semester-periods/active', [SemesterPeriodController::class, 'getActivePeriod']);
        Route::get('/semester-periods', [SemesterPeriodController::class, 'index']);
        Route::post('/semester-periods', [SemesterPeriodController::class, 'store']);
        Route::post('/semester-periods/close', [SemesterPeriodController::class, 'closeSemester']);
        Route::post('/semester-periods/open-schedule-taking', [SemesterPeriodController::class, 'openScheduleTaking']);
        Route::post('/semester-periods/close-schedule-taking', [SemesterPeriodController::class, 'closeScheduleTaking']);
        
        // Schedules API
        Route::get('/schedules/room/{roomId}', [ScheduleController::class, 'getSchedulesByRoom']);
        Route::get('/schedules/user-courses', [ScheduleController::class, 'getUserCourses']);
        Route::get('/schedules/user-courses-active', [ScheduleController::class, 'getUserCoursesActive']);
        Route::get('/schedules/buildings-rooms', [ScheduleController::class, 'getBuildingsWithRooms']);
        Route::get('/schedules/my-schedules', [ScheduleController::class, 'getUserSchedules']);
        Route::get('/schedules/all', [ScheduleController::class, 'getAllSchedules']);
        Route::get('/schedules/available-slots', [ScheduleController::class, 'getAvailableTimeSlots']);
        Route::get('/schedules/statistics', [ScheduleController::class, 'getStatistics']);
        Route::post('/schedules', [ScheduleController::class, 'store']);
        Route::delete('/schedules/{scheduleId}', [ScheduleController::class, 'destroy']);
        Route::get('/schedules/{scheduleId}', [ScheduleController::class, 'show']);
        Route::put('/schedules/{scheduleId}', [ScheduleController::class, 'update']);
        
        // Courses API
        Route::get('/courses/{courseId}/classes', [CourseController::class, 'getClassesByCourse']);
        Route::post('/courses/check-code', [CourseController::class, 'checkCourseCode']);
        
        // User Courses API
        Route::get('/user-courses', [UserController::class, 'getUserCourses']);
        Route::post('/user-courses', [UserController::class, 'storeUserCourse']);
        Route::delete('/user-courses/{userCourseId}', [UserController::class, 'deleteUserCourse']);
        
        // Logbooks API
        Route::get('/logbooks', [LogbookController::class, 'index']);
        Route::get('/logbooks/today', [LogbookController::class, 'getTodayLogbooks']);
        Route::get('/logbooks/user/{userId}', [LogbookController::class, 'getUserLogbooks']);
        Route::post('/logbooks', [LogbookController::class, 'store']);
        Route::put('/logbooks/{logbook}', [LogbookController::class, 'update']);
        
        // Users API
        Route::get('/users', [UserController::class, 'getUsers']);
        Route::get('/users/{userId}', [UserController::class, 'show']);
        Route::post('/users', [UserController::class, 'store']);
        Route::put('/users/{userId}', [UserController::class, 'update']);
        Route::delete('/users/{userId}', [UserController::class, 'destroy']);
        Route::post('/users/delete-multiple', [UserController::class, 'deleteMultiple']);
        
        // Profile API
        Route::get('/profile', [ProfileController::class, 'getProfile']);
        Route::put('/profile', [ProfileController::class, 'updateProfile']);
        Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password.update');
    });

    // ========================================
    // KELOLA MATA KULIAH (Admin/BPH only)
    // ========================================
    Route::prefix('kelola-matkul')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('kelola-matkul');
        Route::post('/', [CourseController::class, 'store'])->name('kelola-matkul.store');
        Route::post('/submit', [CourseController::class, 'submit'])->name('kelola-matkul.submit');
        Route::get('/{classId}/edit', [CourseController::class, 'edit'])->name('kelola-matkul.edit');
        Route::put('/{classId}', [CourseController::class, 'update'])->name('kelola-matkul.update');
        Route::delete('/{classId}', [CourseController::class, 'destroy'])->name('kelola-matkul.destroy');
        Route::get('/create', [CourseController::class, 'create'])->name('kelola-matkul.create');
    });

    // ========================================
    // MANAGE ROOMS (Admin/BPH only)
    // ========================================
    Route::prefix('manage')->group(function () {
        Route::get('/rooms', [RoomController::class, 'manage'])->name('manage.rooms');
    });

    // ========================================
    // REPORTS & STATISTICS
    // ========================================
    Route::prefix('reports')->group(function () {
        Route::get('/schedules', [ScheduleController::class, 'report'])->name('reports.schedules');
        Route::get('/logbooks', [LogbookController::class, 'report'])->name('reports.logbooks');
        Route::get('/usage', [RoomController::class, 'usageReport'])->name('reports.usage');
    });

    // ========================================
    // SETTINGS
    // ========================================
    Route::prefix('settings')->group(function () {
        Route::get('/', function () {
            return view('settings.index');
        })->name('settings.index');
        
        Route::get('/semester', function () {
            return view('settings.semester');
        })->name('settings.semester');
    });

    // ========================================
    // LAB API ROUTES
    // ========================================
    Route::prefix('api/lab')->group(function () {
        Route::post('/qr-verify', [QrVerificationController::class, 'verifyQrCode'])->name('api.lab.qr-verify');
        Route::post('/confirm-entry', [QrVerificationController::class, 'confirmEntry'])->name('api.lab.confirm-entry');
        Route::post('/exit-room', [QrVerificationController::class, 'exitRoom'])->name('api.lab.exit-room');
    });

    // ========================================
    // SCHEDULES API ROUTES (Additional)
    // ========================================
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
        
        // Complete override (Kelas Ganti)
        Route::post('/override/{id}/complete', [ScheduleController::class, 'completeOverride']);
        
        // Confirm override
        Route::post('/override/{id}/confirm', [ScheduleController::class, 'confirmOverride']);
        
        // Cancel override
        Route::post('/override/{id}/cancel', [ScheduleController::class, 'cancelOverride']);
        
        // Move to different room
        Route::post('/{id}/move-room', [ScheduleController::class, 'moveToRoom']);
    });

    // ========================================
    // DASHBOARD API ROUTES
    // ========================================
    Route::prefix('api/dashboard')->group(function () {
        Route::get('/rooms/status', [App\Http\Controllers\Api\DashboardApiController::class, 'getRoomsStatus']);
        Route::get('/rooms/{roomId}/schedules', [App\Http\Controllers\Api\DashboardApiController::class, 'getRoomSchedules']);
        Route::get('/rooms/{roomId}/calendar', [App\Http\Controllers\Api\DashboardApiController::class, 'getRoomWeeklyCalendar']);
        Route::get('/form-data', [App\Http\Controllers\Api\DashboardApiController::class, 'getFormData']);
        Route::get('/available-slots', [App\Http\Controllers\Api\DashboardApiController::class, 'getAvailableTimeSlots']);
        Route::post('/schedule-override', [App\Http\Controllers\Api\DashboardApiController::class, 'createScheduleOverride']);
    });
});

// ========================================
// PUBLIC ROUTES (Tidak butuh auth)
// ========================================

// QR Code Public Access (untuk scan)
Route::post('/public/qr/scan', [QRController::class, 'publicScan'])->name('public.qr.scan');

// Forgot Password OTP Routes (Public - tidak perlu auth)
Route::post('/forgot-password/send-otp', [ForgotPasswordController::class, 'sendOTP'])->name('forgot-password.send-otp');
Route::post('/forgot-password/verify-otp', [ForgotPasswordController::class, 'verifyOTP'])->name('forgot-password.verify-otp');
Route::post('/forgot-password/reset', [ForgotPasswordController::class, 'resetPassword'])->name('forgot-password.reset');
Route::post('/forgot-password/resend-otp', [ForgotPasswordController::class, 'resendOTP'])->name('forgot-password.resend-otp');

// Health Check
Route::get('/health', function () {
    return response()->json(['status' => 'OK', 'timestamp' => now()]);
});

// ========================================
// AUTH ROUTES (Laravel Breeze)
// ========================================
require __DIR__.'/auth.php';
