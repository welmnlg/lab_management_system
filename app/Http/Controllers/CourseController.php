<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    /**
     * Display a listing of courses
     */
    public function index(Request $request)
    {
        try {
            $user = auth()->user();
            $programId = $user->program_studi;

            // Get courses GANJIL
            $coursesGanjil = DB::table('courses')
                ->leftJoin('course_classes', 'courses.course_id', '=', 'course_classes.course_id')
                ->select(
                    'courses.course_id',
                    'courses.course_code',
                    'courses.course_name',
                    'courses.semester',
                    'course_classes.class_id',
                    'course_classes.class_name',
                    'course_classes.lecturer'
                )
                ->where('courses.semester', 'Ganjil')
                ->where('courses.program_id', $programId)
                ->orderBy('courses.course_code')
                ->orderBy('course_classes.class_name')
                ->get()
                ->groupBy('course_id')
                ->map(function ($classes) {
                    $first = $classes->first();
                    if (!$first->class_id) {
                        return null;
                    }
                    return (object)[
                        'course_id' => $first->course_id,
                        'course_code' => $first->course_code,
                        'course_name' => $first->course_name,
                        'semester' => $first->semester,
                        'courseClasses' => $classes->filter(fn($c) => $c->class_id)->map(function ($class) {
                            return (object)[
                                'class_id' => $class->class_id,
                                'class_name' => $class->class_name,
                                'lecturer' => $class->lecturer,
                                'kom' => $class->class_name
                            ];
                        })
                    ];
                })
                ->filter();

            // Get courses GENAP
            $coursesGenap = DB::table('courses')
                ->leftJoin('course_classes', 'courses.course_id', '=', 'course_classes.course_id')
                ->select(
                    'courses.course_id',
                    'courses.course_code',
                    'courses.course_name',
                    'courses.semester',
                    'course_classes.class_id',
                    'course_classes.class_name',
                    'course_classes.lecturer'
                )
                ->where('courses.semester', 'Genap')
                ->where('courses.program_id', $programId)
                ->orderBy('courses.course_code')
                ->orderBy('course_classes.class_name')
                ->get()
                ->groupBy('course_id')
                ->map(function ($classes) {
                    $first = $classes->first();
                    if (!$first->class_id) {
                        return null;
                    }
                    return (object)[
                        'course_id' => $first->course_id,
                        'course_code' => $first->course_code,
                        'course_name' => $first->course_name,
                        'semester' => $first->semester,
                        'courseClasses' => $classes->filter(fn($c) => $c->class_id)->map(function ($class) {
                            return (object)[
                                'class_id' => $class->class_id,
                                'class_name' => $class->class_name,
                                'lecturer' => $class->lecturer,
                                'kom' => $class->class_name
                            ];
                        })
                    ];
                })
                ->filter();

            return view('kelola-matkul', compact('coursesGanjil', 'coursesGenap'));
        } catch (\Exception $e) {
            Log::error('Error loading courses: ' . $e->getMessage());
            return view('kelola-matkul')->with([
                'coursesGanjil' => collect(),
                'coursesGenap' => collect()
            ]);
        }
    }

    /**
     * Get paired class (A1<->A2, B1<->B2, C1<->C2)
     */
    private function getPairedClass($className)
    {
        if (preg_match('/^(Kom [ABC])([12])$/', $className, $matches)) {
            $prefix = $matches[1]; // "Kom A", "Kom B", "Kom C"
            $number = $matches[2]; // "1" or "2"
            $pairedNumber = $number === '1' ? '2' : '1';
            return $prefix . $pairedNumber;
        }
        return null;
    }

    /**
     * Store a newly created course
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'course_code' => 'required|string|max:20',
                'course_name' => 'required|string|max:255',
                'kom' => 'required|string|max:50',
                'semester' => 'required|in:Ganjil,Genap',
                'lecturer' => 'required|string|max:255'
            ]);

            DB::beginTransaction();

            $user = auth()->user();
            $programId = $user->program_studi;

            // VALIDASI 1: Cek apakah sudah ada mata kuliah dengan course_code yang sama tapi course_name berbeda
            $existingCourseWithDifferentName = DB::table('courses')
                ->where('course_code', $validated['course_code'])
                ->where('semester', $validated['semester'])
                ->where('program_id', $programId)
                ->where('course_name', '!=', $validated['course_name'])
                ->first();

            if ($existingCourseWithDifferentName) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Kode mata kuliah {$validated['course_code']} sudah digunakan untuk mata kuliah: {$existingCourseWithDifferentName->course_name}. Silakan gunakan kode yang berbeda atau pastikan nama mata kuliah sama."
                ], 422);
            }

            // VALIDASI 2: Cek apakah mata kuliah dengan nama sama sudah punya kode berbeda
            $existingCourseWithDifferentCode = DB::table('courses')
                ->where('course_name', $validated['course_name'])
                ->where('semester', $validated['semester'])
                ->where('program_id', $programId)
                ->where('course_code', '!=', $validated['course_code'])
                ->first();

            if ($existingCourseWithDifferentCode) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Mata kuliah '{$validated['course_name']}' sudah ada dengan kode: {$existingCourseWithDifferentCode->course_code}. Untuk mata kuliah yang sama, semua kelas (A, B, C) harus menggunakan kode yang sama."
                ], 422);
            }

            // VALIDASI BARU: Cek apakah data sudah ada lengkap (semua field sama)
            $existingCompleteData = DB::table('courses')
                ->join('course_classes', 'courses.course_id', '=', 'course_classes.course_id')
                ->where('courses.course_code', $validated['course_code'])
                ->where('courses.course_name', $validated['course_name'])
                ->where('courses.semester', $validated['semester'])
                ->where('courses.program_id', $programId)
                ->where('course_classes.class_name', $validated['kom'])
                ->where('course_classes.lecturer', $validated['lecturer'])
                ->exists();

            if ($existingCompleteData) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Data sudah ada. Mata kuliah dengan kode, nama, semester, KOM, dan dosen yang sama sudah terdaftar."
                ], 422);
            }

            // VALIDASI BARU: Cek apakah kode dan nama sama tapi semester beda
            $existingCourseDifferentSemester = DB::table('courses')
                ->where('course_code', $validated['course_code'])
                ->where('course_name', $validated['course_name'])
                ->where('program_id', $programId)
                ->where('semester', '!=', $validated['semester'])
                ->first();

            if ($existingCourseDifferentSemester) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Mata kuliah '{$validated['course_name']}' dengan kode {$validated['course_code']} sudah ada pada semester {$existingCourseDifferentSemester->semester}. Silakan ubah data tersebut terlebih dahulu sebelum menambahkan data baru yang sama."
                ], 422);
            }

            // Cari atau buat course
            $course = DB::table('courses')
                ->where('course_code', $validated['course_code'])
                ->where('semester', $validated['semester'])
                ->where('program_id', $programId)
                ->where('semester', $request->semester)
                ->first();

            if (!$course) {
                $courseId = DB::table('courses')->insertGetId([
                    'course_code' => $validated['course_code'],
                    'course_name' => $validated['course_name'],
                    'semester' => $validated['semester'],
                    'program_id' => $programId,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            } else {
                $courseId = $course->course_id;
                
                // Update nama course jika berbeda
                if ($course->course_name !== $validated['course_name']) {
                    DB::table('courses')
                        ->where('course_id', $courseId)
                        ->update([
                            'course_name' => $validated['course_name'],
                            'updated_at' => now()
                        ]);
                }
            }

            // VALIDASI 3: Cek apakah kelas sudah ada
            $existingClass = DB::table('course_classes')
                ->where('course_id', $courseId)
                ->where('class_name', $validated['kom'])
                ->first();

            if ($existingClass) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => "Kelas {$validated['kom']} sudah ada untuk mata kuliah ini."
                ], 422);
            }

            // VALIDASI 4: Cek pairing rules (A1<->A2, B1<->B2, C1<->C2)
            $pairedClass = $this->getPairedClass($validated['kom']);
            
            if ($pairedClass) {
                $pairedClassData = DB::table('course_classes')
                    ->where('course_id', $courseId)
                    ->where('class_name', $pairedClass)
                    ->first();

                if ($pairedClassData && $pairedClassData->lecturer !== $validated['lecturer']) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Tidak dapat menambahkan kelas ini. Kelas {$pairedClass} sudah memiliki dosen: {$pairedClassData->lecturer}. Kelas berpasangan ({$validated['kom']} dan {$pairedClass}) harus memiliki dosen yang sama."
                    ], 422);
                }
            }

            // Insert course class
            DB::table('course_classes')->insert([
                'course_id' => $courseId,
                'class_name' => $validated['kom'],
                'lecturer' => $validated['lecturer'],
                'created_at' => now(),
                'updated_at' => now()
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil ditambahkan',
                'semester' => $validated['semester']
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing course: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan mata kuliah: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified course class
     */
    public function edit($classId)
    {
        try {
            $data = DB::table('course_classes')
                ->join('courses', 'course_classes.course_id', '=', 'courses.course_id')
                ->select(
                    'course_classes.class_id',
                    'courses.course_id',
                    'courses.course_code',
                    'courses.course_name',
                    'courses.semester',
                    'course_classes.class_name as kom',
                    'course_classes.lecturer'
                )
                ->where('course_classes.class_id', $classId)
                ->first();

            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            // Cek apakah ada kelas berpasangan
            $pairedClass = $this->getPairedClass($data->kom);
            $hasPairedClass = false;
            
            if ($pairedClass) {
                $pairedClassData = DB::table('course_classes')
                    ->where('course_id', $data->course_id)
                    ->where('class_name', $pairedClass)
                    ->exists();
                
                $hasPairedClass = $pairedClassData;
            }

            // Hitung berapa banyak kelas untuk mata kuliah ini
            $classCount = DB::table('course_classes')
                ->where('course_id', $data->course_id)
                ->count();

            return response()->json([
                'success' => true,
                'data' => $data,
                'hasPairedClass' => $hasPairedClass,
                'pairedClassName' => $pairedClass,
                'classCount' => $classCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching course: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data'
            ], 500);
        }
    }

    /**
     * Update the specified course class
     */
    public function update(Request $request, $classId)
    {
        try {
            // Validasi input
            $validator = Validator::make($request->all(), [
                'course_code' => 'required|string|max:20',
                'course_name' => 'required|string|max:255',
                'class_name' => 'required|string|max:50',
                'semester' => 'required|in:Ganjil,Genap',
                'lecturer' => 'required|string|max:255'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();

            DB::beginTransaction();

            // Get current course class data
            $courseClass = DB::table('course_classes')
                ->join('courses', 'course_classes.course_id', '=', 'courses.course_id')
                ->select(
                    'course_classes.*', 
                    'courses.course_id',
                    'courses.course_code as old_course_code', 
                    'courses.course_name as old_course_name', 
                    'courses.semester as old_semester'
                )
                ->where('course_classes.class_id', $classId)
                ->first();

            if (!$courseClass) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $user = auth()->user();
            $programId = $user->program_studi;

            // VALIDASI: Cek apakah mengubah kode mata kuliah
            if ($courseClass->old_course_code !== $validated['course_code']) {
                // Cek apakah kode baru sudah digunakan oleh mata kuliah lain
                $existingCourseWithCode = DB::table('courses')
                    ->where('course_code', $validated['course_code'])
                    ->where('semester', $validated['semester'])
                    ->where('program_id', $programId)
                    ->where('course_id', '!=', $courseClass->course_id)
                    ->first();

                if ($existingCourseWithCode) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Kode mata kuliah {$validated['course_code']} sudah digunakan untuk mata kuliah: {$existingCourseWithCode->course_name}. Silakan gunakan kode yang berbeda."
                    ], 422);
                }
            }

            // VALIDASI: Cek apakah mengubah semester
            if ($courseClass->old_semester !== $validated['semester']) {
                // Cek apakah mata kuliah dengan kode dan nama sama sudah ada di semester lain
                $existingCourseDifferentSemester = DB::table('courses')
                    ->where('course_code', $validated['course_code'])
                    ->where('course_name', $validated['course_name'])
                    ->where('program_id', $programId)
                    ->where('semester', $validated['semester'])
                    ->where('course_id', '!=', $courseClass->course_id)
                    ->first();

                if ($existingCourseDifferentSemester) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Mata kuliah '{$validated['course_name']}' dengan kode {$validated['course_code']} sudah ada pada semester {$validated['semester']}. Tidak dapat memindahkan ke semester yang sama."
                    ], 422);
                }
            }

            // VALIDASI 1: Jika mengubah KOM, cek apakah kelas baru sudah ada
            if ($courseClass->class_name !== $validated['class_name']) {
                $existingClass = DB::table('course_classes')
                    ->where('course_id', $courseClass->course_id)
                    ->where('class_name', $validated['class_name'])
                    ->where('class_id', '!=', $classId)
                    ->exists();

                if ($existingClass) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Kelas {$validated['class_name']} sudah ada untuk mata kuliah ini."
                    ], 422);
                }
            }

            // Update course data (kode, nama, semester) - ini akan mempengaruhi semua kelas
            DB::table('courses')
                ->where('course_id', $courseClass->course_id)
                ->update([
                    'course_code' => $validated['course_code'],
                    'course_name' => $validated['course_name'],
                    'semester' => $validated['semester'],
                    'updated_at' => now()
                ]);

            // Update course class (KOM dan dosen)
            DB::table('course_classes')
                ->where('class_id', $classId)
                ->update([
                    'class_name' => $validated['class_name'],
                    'lecturer' => $validated['lecturer'],
                    'updated_at' => now()
                ]);

            // Update paired class lecturer if exists (OTOMATIS update dosen untuk kelas pasangan)
            $pairedClass = $this->getPairedClass($validated['class_name']);
            if ($pairedClass) {
                DB::table('course_classes')
                    ->where('course_id', $courseClass->course_id)
                    ->where('class_name', $pairedClass)
                    ->update([
                        'lecturer' => $validated['lecturer'],
                        'updated_at' => now()
                    ]);
            }

            DB::commit();

            Log::info('Course updated successfully', [
                'class_id' => $classId,
                'course_code' => $validated['course_code'],
                'course_name' => $validated['course_name'],
                'class_name' => $validated['class_name'],
                'semester' => $validated['semester'],
                'lecturer' => $validated['lecturer']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil diupdate',
                'semester' => $validated['semester']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error in update: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating course: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan perubahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified course class
     */
    public function destroy($classId)
    {
        try {
            DB::beginTransaction();

            $courseClass = DB::table('course_classes')
                ->join('courses', 'course_classes.course_id', '=', 'courses.course_id')
                ->select('course_classes.*', 'courses.semester')
                ->where('course_classes.class_id', $classId)
                ->first();

            if (!$courseClass) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Data tidak ditemukan'
                ], 404);
            }

            $semester = $courseClass->semester;

            // Delete course class
            DB::table('course_classes')->where('class_id', $classId)->delete();

            // Check if course has no more classes, delete the course too
            $remainingClasses = DB::table('course_classes')
                ->where('course_id', $courseClass->course_id)
                ->count();

            if ($remainingClasses === 0) {
                DB::table('courses')->where('course_id', $courseClass->course_id)->delete();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus',
                'semester' => $semester
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting course: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Submit all courses
     */
    public function submit(Request $request)
    {
        try {
            return response()->json([
                'success' => true,
                'message' => 'Data mata kuliah berhasil disubmit'
            ]);
        } catch (\Exception $e) {
            Log::error('Error submitting courses: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal submit data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get classes by course ID (untuk AJAX)
     */
    public function getClassesByCourse($courseId)
    {
        try {
            $classes = DB::table('course_classes')
                ->where('course_id', $courseId)
                ->select('class_id', 'class_name', 'lecturer')
                ->orderBy('class_name')
                ->get();

            return response()->json([
                'success' => true,
                'classes' => $classes
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting classes by course: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kelas'
            ], 500);
        }
    }

    /**
     * Check if course code exists
     */
    public function checkCourseCode(Request $request)
    {
        try {
            $user = auth()->user();
            $programId = $user->program_studi;

            $exists = DB::table('courses')
                ->where('course_code', $request->course_code)
                ->where('semester', $request->semester)
                ->where('program_id', $programId)
                ->exists();

            return response()->json([
                'exists' => $exists
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking course code: ' . $e->getMessage());
            return response()->json([
                'exists' => false
            ]);
        }
    }
    
}