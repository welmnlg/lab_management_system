<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\UserCourse;
use App\Models\Course;
use App\Models\CourseClass;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Exception;

class UserController extends Controller
{
    /**
     * Helper: dapatkan pasangan kelas (A1<->A2, B1<->B2, C1<->C2)
     */
    private function getPairedClass($className)
    {
        if (preg_match('/^([ABC])([12])$/', $className, $matches)) {
            $prefix = $matches[1]; // A, B, atau C
            $num = $matches[2]; // 1 atau 2
            $pairedNum = $num === '1' ? '2' : '1';
            return $prefix . $pairedNum;
        }
        return null;
    }

    /**
     * Validasi apakah array kelas memenuhi aturan berpasangan
     */
    private function validatePairedClasses($kelasArray)
    {
        if (empty($kelasArray)) {
            return null; // Tidak ada kelas yang dipilih, tidak perlu validasi.
        }

        $selected = collect($kelasArray);
        foreach (['A', 'B', 'C'] as $kom) {
            $has1 = $selected->contains("{$kom}1");
            $has2 = $selected->contains("{$kom}2");
            if ($has1 && !$has2) {
                return "Kelas {$kom}1 harus dipilih bersamaan dengan {$kom}2.";
            }
            if ($has2 && !$has1) {
                return "Kelas {$kom}2 harus dipilih bersamaan dengan {$kom}1.";
            }
        }
        return null;
    }

    /**
     * Index - Tampilkan pengguna
     */
    public function index()
    {
        $user = auth()->user();
        if ($user->roles->contains('status', 'bph')) {
            $users = User::with(['roles', 'userCourses.courseClass.course'])
                ->whereHas('roles', fn($q) => $q->where('status', 'aslab'))
                ->where('program_studi', $user->program_studi)
                ->get();
            // Filter courses hanya prodi sendiri
            $coursesMaster = Course::where('program_id', $user->program_studi)->get();
        } else {
            $users = User::with(['roles', 'userCourses.courseClass.course'])->get();
            $coursesMaster = Course::all();
        }
        return view('kelola-pengguna', compact('users', 'coursesMaster'));
    }

    /**
     * Create - Form tambah pengguna
     */
    public function create()
    {
        session()->forget('_old_input');
        $roles = Role::all();
        // ✅ Filter courses hanya untuk prodi user yang login
        $user = auth()->user(); // Pastikan $user didefinisikan
        $user->load('program');
        $coursesMaster = Course::where('program_id', $user->program_studi)->get();
        return view('tambah-pengguna', compact('roles', 'coursesMaster', 'user'));
    }

    /**
     * Store - Simpan pengguna baru
     */
    public function store(Request $request)
    {
        \Log::info('Store Request Data:', $request->all());


        $user = auth()->user();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim',
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

        \Log::info('Validated Data:', $validated);

        // ✅ Validasi pasangan kelas
        if ($err = $this->validatePairedClasses($validated['kelas_1'])) {
            return back()->withErrors(['kelas_1' => $err])->withInput();
        }
        if (!empty($validated['kelas_2']) && ($err = $this->validatePairedClasses($validated['kelas_2']))) {
            return back()->withErrors(['kelas_2' => $err])->withInput();
        }
        if (!empty($validated['kelas_3']) && ($err = $this->validatePairedClasses($validated['kelas_3']))) {
            return back()->withErrors(['kelas_3' => $err])->withInput();
        }

        try {
            DB::beginTransaction();

            $newUser = User::create([
                'name' => $validated['name'],
                'nim' => $validated['nim'],
                'program_studi' => auth()->user()->program_studi, // selalu ikut prodi BPH
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
            ]);

            $selectedRole = Role::find($validated['role']);
            if ($selectedRole && $selectedRole->status === 'bph') {
                $aslabRole = Role::where('status', 'aslab')->first();
                $newUser->roles()->attach($aslabRole ? [$selectedRole->id, $aslabRole->id] : [$selectedRole->id]);
            } else {
                $newUser->roles()->attach($validated['role']);
            }

            // ✅ Proses dengan validasi bentrok
            $this->processCourseClasses($newUser->user_id, $validated, 1, $newUser->program_studi);
            if (!empty($validated['mata_kuliah_2'])) {
                $this->processCourseClasses($newUser->user_id, $validated, 2, $newUser->program_studi);
            }
            if (!empty($validated['mata_kuliah_3'])) {
                $this->processCourseClasses($newUser->user_id, $validated, 3, $newUser->program_studi);
            }

            DB::commit();
            return redirect()->route('kelola-pengguna.index')->with('success', 'Pengguna berhasil ditambahkan!');
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error creating user: ' . $e->getMessage());

            // Pesan error yang user-friendly
            return redirect()->back()
                            ->withInput()
                            ->with('error', $e->getMessage());
                }
    }

    /**
     * Edit - Form edit pengguna
     */
    public function edit(string $id)
    {
        $user = User::with(['roles', 'userCourses.courseClass.course'])->findOrFail($id);
        if (auth()->user()->roles->contains('status', 'bph')) {
            if ($user->program_studi !== auth()->user()->program_studi) {
                abort(403, 'Unauthorized action.');
            }
        }
        $roles = Role::all();
        // ✅ Filter courses hanya prodi yang relevan
        $coursesMaster = Course::where('program_id', $user->program_studi)->get();
        return view('edit-pengguna', compact('user', 'roles', 'coursesMaster'));
    }

    /**
     * Update - Perbarui pengguna
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        if (auth()->user()->roles->contains('status', 'bph')) {
            if ($user->program_studi !== auth()->user()->program_studi) {
                abort(403, 'Unauthorized action.');
            }
        }

        $rules = [
            'name' => 'required|string|max:255',
            'nim' => "required|string|unique:users,nim,{$id},user_id",
            'email' => "required|email|unique:users,email,{$id},user_id",
            'peran' => 'required|in:aslab,bph',
            'mata_kuliah_1' => 'required|exists:courses,course_id',
            'kelas_1' => 'required|array|min:1',
            'mata_kuliah_2' => 'nullable|exists:courses,course_id',
            'kelas_2' => 'nullable|array',
            'mata_kuliah_3' => 'nullable|exists:courses,course_id',
            'kelas_3' => 'nullable|array',
        ];

        $validated = $request->validate($rules);

        // ✅ Validasi pasangan kelas
        if ($err = $this->validatePairedClasses($validated['kelas_1'])) {
            return response()->json(['success' => false, 'message' => $err], 422);
        }
        if (!empty($validated['kelas_2']) && ($err = $this->validatePairedClasses($validated['kelas_2']))) {
            return response()->json(['success' => false, 'message' => $err], 422);
        }
        if (!empty($validated['kelas_3']) && ($err = $this->validatePairedClasses($validated['kelas_3']))) {
            return response()->json(['success' => false, 'message' => $err], 422);
        }

        try {
            DB::beginTransaction();

            $user->update([
                'name' => $validated['name'],
                'nim' => $validated['nim'],
                'email' => $validated['email'],
                // password tidak diupdate karena di-form tidak diedit
            ]);

            // Sync role
            if ($validated['peran'] === 'bph') {
                $bph = Role::where('status', 'bph')->first();
                $aslab = Role::where('status', 'aslab')->first();
                $ids = [];
                if ($bph) $ids[] = $bph->id;
                if ($aslab) $ids[] = $aslab->id;
                $user->roles()->sync($ids);
            } else {
                $aslab = Role::where('status', 'aslab')->first();
                $user->roles()->sync($aslab ? [$aslab->id] : []);
            }

            // Hapus relasi lama
            UserCourse::where('user_id', $user->user_id)->delete();

            // ✅ Proses ulang dengan validasi bentrok
            $this->processCourseClasses($user->user_id, $validated, 1, $user->program_studi);
            if (!empty($validated['mata_kuliah_2'])) {
                $this->processCourseClasses($user->user_id, $validated, 2, $user->program_studi);
            }
            if (!empty($validated['mata_kuliah_3'])) {
                $this->processCourseClasses($user->user_id, $validated, 3, $user->program_studi);
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Pengguna berhasil diperbarui!']);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error updating user: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Helper: Proses kelas dengan validasi bentrok & eksistensi
     */
    private function processCourseClasses($userId, $validated, $mkNumber, $programStudi)
    {
        $courseId = $validated["mata_kuliah_{$mkNumber}"] ?? null;
        $kelasArray = $validated["kelas_{$mkNumber}"] ?? [];

        if (empty($courseId) || empty($kelasArray)) {
            return;
        }

        // Pastikan mata kuliah ini milik prodi yang benar
        $course = Course::where('course_id', $courseId)
            ->where('program_id', $programStudi)
            ->first();

        if (!$course) {
            throw new Exception("Mata kuliah tidak valid atau bukan milik program studi Anda.");
        }

        foreach ($kelasArray as $cls) {
            $formatted = 'Kom ' . $cls;

            // Cek eksistensi kelas
            $courseClass = CourseClass::where('course_id', $courseId)
                ->where('class_name', $cls)
                ->first();

            if (!$courseClass) {
                // Jika kelas tidak ditemukan, cari nama mata kuliah untuk pesan error
                $courseName = $course->course_name;
                throw new Exception("Mata Kuliah '{$courseName}' tidak memiliki kelas '{$cls}'. Kelas ini tidak tersedia.");
            }

            // ✅ Cek apakah sudah diambil aslab lain
            $existing = UserCourse::where('class_id', $courseClass->class_id)->exists();

            if ($existing) {
                // Jika sudah diambil, cari nama aslab yang mengambilnya
                $takenBy = UserCourse::with('user')->where('class_id', $courseClass->class_id)->first();
                $name = $takenBy?->user?->name ?? 'aslab lain';

                // Jika aslab yang sama (untuk kasus edit), biarkan
                if ($takenBy?->user?->user_id == $userId) {
                    // Ini adalah kasus edit, aslab yang sama ingin menambahkan kelas yang sudah dia ambil.
                    // Tidak apa-apa, lanjutkan.
                    continue;
                }

                // Jika aslab lain, tolak
                throw new Exception("Mata Kuliah '{$course->course_name}' Kelas '{$cls}' sudah diambil oleh {$name}. Tidak bisa digunakan lagi.");
            }

            // Jika lolos semua validasi, simpan
            UserCourse::create([
                'user_id' => $userId,
                'class_id' => $courseClass->class_id,
            ]);
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

    public function getUsers(Request $request)
    {
        try {
            $role = $request->get('role', 'aslab');
            $users = User::whereHas('roles', function($q) use ($role) {
                $q->where('status', $role);
            })->select('user_id', 'name', 'nim', 'email')
            ->get();
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        } catch (\Exception $e) {
            Log::error('Error getting users: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data user'
            ], 500);
        }
    }
}
