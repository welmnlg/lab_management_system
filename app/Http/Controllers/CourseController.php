<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseClass;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseController extends Controller
{
    /**
     * Display a listing of the courses based on user's program
     */
    public function index()
    {
        $user = Auth::user();
        $programId = $user->program_studi;

        // Get courses dengan relasi ke course_classes dan filter by program
        // Ordered by course_code untuk konsistensi
        $coursesGanjil = Course::with(['courseClasses' => function($query) {
                $query->orderBy('class_name', 'asc');
            }])
            ->where('program_id', $programId)
            ->where('semester', 'Ganjil')
            ->orderBy('course_code', 'asc')
            ->get();

        $coursesGenap = Course::with(['courseClasses' => function($query) {
                $query->orderBy('class_name', 'asc');
            }])
            ->where('program_id', $programId)
            ->where('semester', 'Genap')
            ->orderBy('course_code', 'asc')
            ->get();

        return view('kelola-matkul', compact('coursesGanjil', 'coursesGenap'));
    }

    /**
     * Store a newly created course with its class
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'course_code' => 'required|string|max:10',
                'course_name' => 'required|string|max:255',
                'kom' => 'required|string|max:10',
                'semester' => 'required|in:Ganjil,Genap',
                'lecturer' => 'required|string|max:255',
            ]);

            $user = Auth::user();
            $programId = $user->program_studi;

            DB::beginTransaction();

            // Check if course with same code already exists for this program
            $course = Course::where('course_code', $request->course_code)
                ->where('program_id', $programId)
                ->first();

            // If course doesn't exist, create it
            if (!$course) {
                $course = Course::create([
                    'course_code' => $request->course_code,
                    'course_name' => $request->course_name,
                    'semester' => $request->semester,
                    'program_id' => $programId,
                ]);
            }

            // Check if class with same KOM already exists for this course
            $existingClass = CourseClass::where('course_id', $course->course_id)
                ->where('class_name', $request->kom)
                ->first();

            if ($existingClass) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'KOM ' . $request->kom . ' sudah ada untuk mata kuliah ini!'
                ], 422);
            }

            // Create course class
            CourseClass::create([
                'course_id' => $course->course_id,
                'class_name' => $request->kom,
                'lecturer' => $request->lecturer,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil ditambahkan!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing course: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get course class data for editing
     */
    public function edit($classId)
    {
        try {
            $courseClass = CourseClass::with('course')->findOrFail($classId);

            return response()->json([
                'success' => true,
                'data' => [
                    'class_id' => $courseClass->class_id,
                    'course_id' => $courseClass->course_id,
                    'course_code' => $courseClass->course->course_code,
                    'course_name' => $courseClass->course->course_name,
                    'kom' => $courseClass->class_name,
                    'semester' => $courseClass->course->semester,
                    'lecturer' => $courseClass->lecturer,
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading course for edit: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }

    /**
     * Update the specified course class
     */
    public function update(Request $request, $classId)
    {
        try {
            $request->validate([
                'course_name' => 'required|string|max:255',
                'kom' => 'required|string|max:10',
                'semester' => 'required|in:Ganjil,Genap',
                'lecturer' => 'required|string|max:255',
            ]);

            DB::beginTransaction();

            $courseClass = CourseClass::findOrFail($classId);
            $course = Course::findOrFail($courseClass->course_id);

            // Check if KOM is being changed and if new KOM already exists
            if ($courseClass->class_name !== $request->kom) {
                $existingClass = CourseClass::where('course_id', $course->course_id)
                    ->where('class_name', $request->kom)
                    ->where('class_id', '!=', $classId)
                    ->first();

                if ($existingClass) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'KOM ' . $request->kom . ' sudah ada untuk mata kuliah ini!'
                    ], 422);
                }
            }

            // Update course
            $course->update([
                'course_name' => $request->course_name,
                'semester' => $request->semester,
            ]);

            // Update course class
            $courseClass->update([
                'class_name' => $request->kom,
                'lecturer' => $request->lecturer,
            ]);

            // Update lecturer for all classes with same prefix
            // Contoh: Jika update Kom A1, maka Kom A2 juga ikut update dosennya
            // Extract prefix (Kom A, Kom B, Kom C)
            if (preg_match('/^(Kom [A-C])/', $request->kom, $matches)) {
                $komPrefix = $matches[1]; // Will be "Kom A", "Kom B", or "Kom C"
                
                CourseClass::where('course_id', $course->course_id)
                    ->where('class_name', 'LIKE', $komPrefix . '%')
                    ->update(['lecturer' => $request->lecturer]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data mata kuliah berhasil diperbarui!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating course: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified course class from storage
     */
    public function destroy($classId)
    {
        try {
            DB::beginTransaction();

            $courseClass = CourseClass::findOrFail($classId);
            $courseId = $courseClass->course_id;
            
            // Delete the class
            $courseClass->delete();

            // Check if there are any remaining classes for this course
            $remainingClasses = CourseClass::where('course_id', $courseId)->count();

            // If no classes left, delete the course too
            if ($remainingClasses === 0) {
                Course::where('course_id', $courseId)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting course: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit all courses (if needed for approval workflow)
     */
    public function submit(Request $request)
    {
        try {
            // Add your submit logic here
            // For example, update status, send notification, etc.

            return response()->json([
                'success' => true,
                'message' => 'Data mata kuliah berhasil dikirim!'
            ]);

        } catch (\Exception $e) {
            Log::error('Error submitting courses: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}