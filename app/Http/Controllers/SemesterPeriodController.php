<?php

namespace App\Http\Controllers;

use App\Models\SemesterPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SemesterPeriodController extends Controller
{
    /**
     * Get active semester period - FIXED: Better data formatting
     */
    public function getActivePeriod()
    {
        try {
            $period = SemesterPeriod::getActivePeriod();
            
            if (!$period) {
                return response()->json([
                    'success' => false,
                    'message' => 'Belum ada periode aktif'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'period_id' => $period->period_id,
                    'semester_type' => $period->semester_type,
                    'academic_year' => $period->academic_year,
                    'start_date' => $period->start_date->format('Y-m-d'),
                    'end_date' => $period->end_date->format('Y-m-d'),
                    'schedule_start_date' => $period->schedule_start_date ? $period->schedule_start_date->format('Y-m-d') : null,
                    'schedule_end_date' => $period->schedule_end_date ? $period->schedule_end_date->format('Y-m-d') : null,
                    'formatted_start_date' => $period->start_date->locale('id')->translatedFormat('j M Y'),
                    'formatted_end_date' => $period->end_date->locale('id')->translatedFormat('j M Y'),
                    'date_range' => $period->date_range,
                    'schedule_date_range' => $period->schedule_date_range,
                    'formatted_period' => $period->formatted_period,
                    'remaining_days' => $period->remaining_days,
                    'remaining_schedule_days' => $period->remaining_schedule_days,
                    'is_active' => $period->is_active,
                    'is_schedule_open' => $period->is_schedule_open,
                    'is_schedule_taking_open' => $period->is_schedule_taking_open,
                    'allowed_users' => $period->allowed_users
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting active period: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data periode'
            ], 500);
        }
    }

    /**
     * Store new semester period
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'semester_type' => 'required|in:Ganjil,Genap',
                'academic_year' => 'required|string|regex:/^\d{4}\/\d{4}$/',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after:start_date',
                'schedule_start_date' => 'required|date',
                'schedule_end_date' => 'required|date|after:schedule_start_date'
            ], [
                'semester_type.required' => 'Semester harus dipilih',
                'semester_type.in' => 'Semester harus Ganjil atau Genap',
                'academic_year.required' => 'Tahun akademik harus diisi',
                'academic_year.regex' => 'Format tahun akademik harus YYYY/YYYY (contoh: 2024/2025)',
                'start_date.required' => 'Tanggal mulai semester harus diisi',
                'start_date.date' => 'Format tanggal mulai semester tidak valid',
                'end_date.required' => 'Tanggal selesai semester harus diisi',
                'end_date.date' => 'Format tanggal selesai semester tidak valid',
                'end_date.after' => 'Tanggal selesai semester harus setelah tanggal mulai',
                'schedule_start_date.required' => 'Tanggal mulai pengambilan jadwal harus diisi',
                'schedule_start_date.date' => 'Format tanggal mulai pengambilan jadwal tidak valid',
                'schedule_end_date.required' => 'Tanggal selesai pengambilan jadwal harus diisi',
                'schedule_end_date.date' => 'Format tanggal selesai pengambilan jadwal tidak valid',
                'schedule_end_date.after' => 'Tanggal selesai pengambilan jadwal harus setelah tanggal mulai'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Validasi tambahan untuk tahun akademik
            $years = explode('/', $request->academic_year);
            if (count($years) !== 2 || (int)$years[1] !== (int)$years[0] + 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tahun akademik tidak valid. Contoh yang benar: 2024/2025'
                ], 422);
            }

            DB::beginTransaction();

            // Check if there's already an active period
            $existingActive = SemesterPeriod::where('is_active', true)->first();
            if ($existingActive) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sudah ada periode aktif. Silakan tutup periode tersebut terlebih dahulu.'
                ], 422);
            }

            // Check if semester with same type and academic year already exists
            $existingSemester = SemesterPeriod::where('semester_type', $request->semester_type)
                ->where('academic_year', $request->academic_year)
                ->first();

            if ($existingSemester) {
                return response()->json([
                    'success' => false,
                    'message' => "Semester {$request->semester_type} {$request->academic_year} sudah ada."
                ], 422);
            }

            // Create new period
            $period = SemesterPeriod::create([
                'semester_type' => $request->semester_type,
                'academic_year' => $request->academic_year,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'schedule_start_date' => $request->schedule_start_date,
                'schedule_end_date' => $request->schedule_end_date,
                'is_active' => true,
                'is_schedule_open' => false,
                'allowed_users' => null
            ]);

            DB::commit();

            Log::info('New semester period created', [
                'period_id' => $period->period_id,
                'semester_type' => $period->semester_type,
                'academic_year' => $period->academic_year
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Semester berhasil dibuka',
                'data' => $period
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating semester period: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuka semester: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Open schedule taking for ALL users (Manual override)
     */
    public function openScheduleTaking(Request $request)
    {
        try {
            $activePeriod = SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada periode aktif'
                ], 404);
            }

            // ✅ SIMPLIFIED: Set is_schedule_open = true for ALL users
            $activePeriod->update([
                'is_schedule_open' => true
            ]);

            Log::info('Schedule taking opened manually for ALL users', [
                'period_id' => $activePeriod->period_id,
                'admin_user' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengambilan jadwal berhasil dibuka untuk SEMUA aslab',
                'data' => $activePeriod
            ]);

        } catch (\Exception $e) {
            Log::error('Error opening schedule taking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuka pengambilan jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Close schedule taking
     */
    public function closeScheduleTaking(Request $request)
    {
        try {
            $activePeriod = SemesterPeriod::getActivePeriod();
            
            if (!$activePeriod) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada periode aktif'
                ], 404);
            }

            // ✅ SIMPLIFIED: Set is_schedule_open = false
            $activePeriod->update([
                'is_schedule_open' => false
            ]);

            Log::info('Schedule taking closed manually', [
                'period_id' => $activePeriod->period_id,
                'admin_user' => auth()->id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengambilan jadwal berhasil ditutup',
                'data' => $activePeriod
            ]);

        } catch (\Exception $e) {
            Log::error('Error closing schedule taking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menutup pengambilan jadwal: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Close current semester period
     */
    public function closeSemester(Request $request)
    {
        try {
            DB::beginTransaction();

            $period = SemesterPeriod::where('is_active', true)->first();
            
            if (!$period) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada periode aktif'
                ], 404);
            }

            $period->update(['is_active' => false]);

            DB::commit();

            Log::info('Semester period closed', [
                'period_id' => $period->period_id,
                'semester_type' => $period->semester_type,
                'academic_year' => $period->academic_year
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Semester berhasil ditutup'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error closing semester: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menutup semester: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all periods (history)
     */
    public function index()
    {
        try {
            $periods = SemesterPeriod::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $periods
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting periods: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data periode'
            ], 500);
        }
    }
}