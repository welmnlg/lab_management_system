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
        
        $coursesMaster = Course::all();
        return view('kelola-pengguna', compact('users', 'coursesMaster'));
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
            \DB::beginTransaction();

            // Buat user baru
            $user = User::create([
                'name' => $validated['name'],
                'nim' => $validated['nim'],
                'program_studi' => $validated['program_studi'],
                'email' => $validated['email'],
                'password' => \Hash::make($validated['password']),
            ]);

            // Attach role
            $user->roles()->attach($validated['role']);

            // Function helper untuk process mata kuliah
            $this->processCourseClasses($user->user_id, $validated, 1);
            if (!empty($validated['mata_kuliah_2'])) {
                $this->processCourseClasses($user->user_id, $validated, 2);
            }
            if (!empty($validated['mata_kuliah_3'])) {
                $this->processCourseClasses($user->user_id, $validated, 3);
            }

            \DB::commit();

            return redirect()->route('kelola-pengguna.index')
                            ->with('success', 'Pengguna berhasil ditambahkan!');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            \Log::error('Error creating user: ' . $e->getMessage());
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Helper function untuk process course classes
     */
    private function processCourseClasses($userId, $validated, $mkNumber)
    {
        $courseId = $validated["mata_kuliah_{$mkNumber}"] ?? null;
        $kelasArray = $validated["kelas_{$mkNumber}"] ?? [];
        
        if (empty($courseId) || empty($kelasArray)) {
            return;
        }
        
        foreach ($kelasArray as $className) {
            // Format: A1, A2, B1, B2, C1, C2
            // Di database: Kom A1, Kom A2, Kom B1, dst
            $formattedClassName = 'Kom ' . $className;
            
            $courseClass = CourseClass::where('course_id', $courseId)
                                    ->where('class_name', $formattedClassName)
                                    ->first();
            
            if ($courseClass) {
                UserCourse::create([
                    'user_id' => $userId,
                    'class_id' => $courseClass->class_id,
                ]);
            } else {
                \Log::warning("CourseClass not found: course_id={$courseId}, class_name={$formattedClassName}");
            }
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
        $user = User::with(['roles', 'userCourses.courseClass.course'])->findOrFail($id);
        
        // Jika yang login adalah BPH, validasi hanya bisa edit user dari prodi sendiri
        if (auth()->user()->roles->contains('status', 'bph')) {
            if ($user->program_studi !== auth()->user()->program_studi) {
                abort(403, 'Unauthorized action.');
            }
        }
        
        // Ambil semua role untuk dropdown
        $roles = Role::all();
        
        // Ambil semua course
        $coursesMaster = Course::all();

        // Tampilkan form edit dengan data user
        return view('edit-pengguna', compact('user', 'roles', 'coursesMaster'));
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
            'nim' => "required|string|unique:users,nim,{$id},user_id",
            'email' => "required|email|unique:users,email,{$id},user_id",
            'password' => 'nullable|min:6', // Password opsional saat update
            'peran' => 'required|string|in:aslab,admin,bph',
            'mata_kuliah_1' => 'required|exists:courses,course_id',
            'kelas_1' => 'required|array|min:1',
            'mata_kuliah_2' => 'nullable|exists:courses,course_id',
            'kelas_2' => 'nullable|array',
            'mata_kuliah_3' => 'nullable|exists:courses,course_id',
            'kelas_3' => 'nullable|array',
        ];

        // Jika bukan BPH, wajib isi program studi
        if (!auth()->user()->roles->contains('status', 'bph')) {
            $rules['program_studi'] = 'required|string';
        }

        // Validasi input
        $validated = $request->validate($rules);

        try {
            \DB::beginTransaction();

            // Update data user
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->nim = $validated['nim'];

            // Update password hanya jika diisi
            if ($request->filled('password')) {
                $user->password = \Hash::make($validated['password']);
            }

            // Set program studi
            if (auth()->user()->roles->contains('status', 'bph')) {
                // BPH tidak bisa ubah program studi
                $user->program_studi = auth()->user()->program_studi;
            } else {
                // Admin bisa ubah program studi
                if (isset($validated['program_studi'])) {
                    $user->program_studi = $validated['program_studi'];
                }
            }

            // Simpan perubahan
            $user->save();

            // Sync role berdasarkan peran
            $role = Role::where('status', $validated['peran'])->first();
            if ($role) {
                $user->roles()->sync([$role->id]);
            }

            // Hapus semua UserCourse yang lama
            UserCourse::where('user_id', $user->user_id)->delete();

            // Tambah UserCourse baru
            $this->processCourseClasses($user->user_id, $validated, 1);
            if (!empty($validated['mata_kuliah_2'])) {
                $this->processCourseClasses($user->user_id, $validated, 2);
            }
            if (!empty($validated['mata_kuliah_3'])) {
                $this->processCourseClasses($user->user_id, $validated, 3);
            }

            \DB::commit();

            // Redirect dengan pesan sukses
            return redirect()
                ->route('kelola-pengguna.index')
                ->with('success', 'Pengguna berhasil diperbarui!');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            \Log::error('Error updating user: ' . $e->getMessage());
            
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
        
        try {
            \DB::beginTransaction();

            // Hapus relasi user_courses terlebih dahulu (jika ada)
            UserCourse::where('user_id', $user->user_id)->delete();
            
            // Hapus relasi role
            $user->roles()->detach();
            
            // Hapus user
            $user->delete();

            \DB::commit();

            // Redirect dengan pesan sukses
            return redirect()
                ->route('kelola-pengguna.index')
                ->with('success', 'Pengguna berhasil dihapus!');

        } catch (\Exception $e) {
            \DB::rollBack();
            
            \Log::error('Error deleting user: ' . $e->getMessage());
            
            return redirect()->back()
                            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete multiple users at once
     * Hapus banyak pengguna sekaligus
     */
    public function deleteMultiple(Request $request)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,user_id'
        ]);

        try {
            \DB::beginTransaction();

            foreach ($request->user_ids as $userId) {
                $user = User::findOrFail($userId);
                
                // Jika yang login adalah BPH, validasi prodi
                if (auth()->user()->roles->contains('status', 'bph')) {
                    if ($user->program_studi !== auth()->user()->program_studi) {
                        continue; // Skip user yang bukan dari prodi yang sama
                    }
                }

                // Hapus relasi
                UserCourse::where('user_id', $user->user_id)->delete();
                $user->roles()->detach();
                $user->delete();
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => count($request->user_ids) . ' pengguna berhasil dihapus!'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            
            \Log::error('Error deleting multiple users: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}