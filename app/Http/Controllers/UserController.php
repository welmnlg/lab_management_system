<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UserCourse;
use App\Models\Course;
use App\Models\CourseClass;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * Menampilkan daftar pengguna berdasarkan role yang login
     */
    public function index()
    {
        $user = auth()->user();
        
        // Jika yang login adalah BPH
        if ($user->roles->contains('status', 'bph')) {
            // BPH hanya bisa lihat aslab dari prodi sendiri
            $users = User::with(['roles', 'userCourses.courseClass.course'])
                    ->whereHas('roles', function($q) {
                        $q->where('status', 'aslab');
                    })
                    ->where('program_studi', $user->program_studi)
                    ->get();
        } else {
            // Admin bisa lihat semua user
            $users = User::with(['roles', 'userCourses.courseClass.course'])->get();
        }
        
        return view('kelola-pengguna', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     * Menampilkan form tambah pengguna baru
     */
    public function create()
    {
        // Clear old input untuk mencegah autofill dari session sebelumnya
        session()->forget('_old_input');
        
        // Ambil semua role untuk pilihan di form
        $roles = Role::all();
        
        return view('tambah-pengguna', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     * Menyimpan pengguna baru ke database
     */
public function store(Request $request)
{
    // Validasi input
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'nim' => 'required|string|unique:users,nim',
        'program_studi' => 'required|string',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
        'mata_kuliah_1' => 'required|exists:courses,course_id',
        'kelas_1' => 'required|array|min:1',
        'mata_kuliah_2' => 'nullable|exists:courses,course_id',
        'kelas_2' => 'nullable|array',
        'mata_kuliah_3' => 'nullable|exists:courses,course_id',
        'kelas_3' => 'nullable|array',
        'role' => 'required|exists:roles,id',
    ]);

    try {
        DB::beginTransaction();

        // Buat user baru
        $user = User::create([
            'name' => $validated['name'],
            'nim' => $validated['nim'],
            'program_studi' => $validated['program_studi'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Attach role
        $user->roles()->attach($validated['role']);

        // Array untuk menampung semua relasi user-course-class
        $userCourseClasses = [];

        // Process Mata Kuliah 1
        if (!empty($validated['mata_kuliah_1']) && !empty($validated['kelas_1'])) {
            foreach ($validated['kelas_1'] as $className) {
                // Cari class_id berdasarkan course_id dan class_name
                // Misalnya className = "A1", "A2", "B1", dst
                $courseClass = CourseClass::where('course_id', $validated['mata_kuliah_1'])
                                         ->where('class_name', 'Kom ' . $className) // Sesuaikan format: "Kom A1"
                                         ->first();
                
                if ($courseClass) {
                    $userCourseClasses[] = [
                        'user_id' => $user->user_id,
                        'course_id' => $validated['mata_kuliah_1'],
                        'class_id' => $courseClass->class_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Process Mata Kuliah 2
        if (!empty($validated['mata_kuliah_2']) && !empty($validated['kelas_2'])) {
            foreach ($validated['kelas_2'] as $className) {
                $courseClass = CourseClass::where('course_id', $validated['mata_kuliah_2'])
                                         ->where('class_name', 'Kom ' . $className)
                                         ->first();
                
                if ($courseClass) {
                    $userCourseClasses[] = [
                        'user_id' => $user->user_id,
                        'course_id' => $validated['mata_kuliah_2'],
                        'class_id' => $courseClass->class_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Process Mata Kuliah 3
        if (!empty($validated['mata_kuliah_3']) && !empty($validated['kelas_3'])) {
            foreach ($validated['kelas_3'] as $className) {
                $courseClass = CourseClass::where('course_id', $validated['mata_kuliah_3'])
                                         ->where('class_name', 'Kom ' . $className)
                                         ->first();
                
                if ($courseClass) {
                    $userCourseClasses[] = [
                        'user_id' => $user->user_id,
                        'course_id' => $validated['mata_kuliah_3'],
                        'class_id' => $courseClass->class_id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        // Insert semua data ke tabel user_courses (bukan sync, karena perlu multiple rows)
        if (!empty($userCourseClasses)) {
            DB::table('user_courses')->insert($userCourseClasses);
        }

        DB::commit();

        return redirect()->route('kelola-pengguna.index')
                        ->with('success', 'Pengguna berhasil ditambahkan!');

    } catch (\Exception $e) {
        DB::rollBack();
        
        return redirect()->back()
                        ->withInput()
                        ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     * Menampilkan detail pengguna (belum diimplementasikan)
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Menampilkan form edit pengguna
     */
    public function edit(string $id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);
        
        // Jika yang login adalah BPH, validasi hanya bisa edit user dari prodi sendiri
        if (auth()->user()->roles->contains('status', 'bph')) {
            if ($user->program_studi !== auth()->user()->program_studi) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        // Ambil semua role untuk dropdown
        $roles = Role::all();

        // Tampilkan form edit dengan data user
        return view('edit-pengguna', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     * Update data pengguna di database
     */
    public function update(Request $request, string $id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);
        
        // Jika yang login adalah BPH, validasi hanya bisa edit user dari prodi sendiri
        if (auth()->user()->roles->contains('status', 'bph')) {
            if ($user->program_studi !== auth()->user()->program_studi) {
                abort(403, 'Unauthorized action.');
            }
        }

        // Setup rules validasi
        $rules = [
            'name' => 'required|string|max:255',
            'nim' => "required|string|unique:users,nim,$id",
            'email' => "required|email|unique:users,email,$id",
            'password' => 'nullable|min:6', // Password opsional saat update
            'role' => 'required|exists:roles,id',
        ];

        // Jika bukan BPH, wajib isi program studi
        if (!auth()->user()->roles->contains('status', 'bph')) {
            $rules['program_studi'] = 'required|string';
        }

        // Validasi input
        $request->validate($rules);

        // Update data user
        $user->name = $request->name;
        $user->email = $request->email;
        $user->nim = $request->nim;

        // Update password hanya jika diisi
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // Set program studi
        if (auth()->user()->roles->contains('status', 'bph')) {
            // BPH tidak bisa ubah program studi
            $user->program_studi = auth()->user()->program_studi;
        } else {
            // Admin bisa ubah program studi
            $user->program_studi = $request->program_studi;
        }

        // Simpan perubahan
        $user->save();

        // Sync role (replace role lama dengan role baru)
        $user->roles()->sync([$request->role]);

        // Redirect dengan pesan sukses
        return redirect()
            ->route('kelola-pengguna.index')
            ->with('success', 'Pengguna berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     * Hapus pengguna dari database
     */
    public function destroy(string $id)
    {
        // Cari user berdasarkan ID
        $user = User::findOrFail($id);
        
        // Jika yang login adalah BPH, validasi hanya bisa hapus user dari prodi sendiri
        if (auth()->user()->roles->contains('status', 'bph')) {
            if ($user->program_studi !== auth()->user()->program_studi) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        // Hapus relasi user_courses terlebih dahulu (jika ada)
        try {
            if (method_exists($user, 'userCourses')) {
                $user->userCourses()->delete();
            }
        } catch (\Exception $e) {
            \Log::error('Error deleting user courses: ' . $e->getMessage());
        }
        
        // Hapus relasi role
        $user->roles()->detach();
        
        // Hapus user
        $user->delete();

        // Redirect dengan pesan sukses
        return redirect()
            ->route('kelola-pengguna.index')
            ->with('success', 'Pengguna berhasil dihapus!');
    }

    /**
     * Helper method untuk convert nama mata kuliah dari value ke nama yang sesuai di database
     * 
     * @param string $courseValue
     * @return string
     */
    private function getCourseName($courseValue)
    {
        $courseMap = [
            'kecerdasan_buatan' => 'Kecerdasan Buatan',
            'desain_interaksi' => 'Desain Interaksi',
            'pemrograman_web' => 'Pemrograman Web',
            'web_semantik' => 'Web Semantik',
        ];

        return $courseMap[$courseValue] ?? $courseValue;
    }
}