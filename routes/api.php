<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DashboardApiController;
use App\Http\Controllers\Lab\QrVerificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('dashboard')->group(function () {
    
    // Status Ruangan
    Route::get('/rooms/status', [DashboardApiController::class, 'getRoomsStatus']);
    
    // Jadwal Ruangan Hari Ini
    Route::get('/rooms/{roomId}/schedules', [DashboardApiController::class, 'getRoomSchedules']);
    
    // Kalender Mingguan
    Route::get('/rooms/{roomId}/calendar', [DashboardApiController::class, 'getRoomWeeklyCalendar']);
    
    // Data Form (dropdown options)
    Route::get('/form-data', [DashboardApiController::class, 'getFormData']);
    
    // CRUD Schedule Override (Kelas Ganti)
    Route::post('/schedule-override', [DashboardApiController::class, 'createScheduleOverride']);
    Route::put('/schedule-override/{id}', [DashboardApiController::class, 'updateScheduleOverride']);
    Route::delete('/schedule-override/{id}', [DashboardApiController::class, 'deleteScheduleOverride']);
    
});


