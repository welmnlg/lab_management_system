<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin
     */
    public function dashboard()
    {
        $totalUsers = User::count();
        $totalAdmin = User::where('peran', 'Admin')->count();
        $totalAslab = User::where('peran', 'Aslab')->count();
        
        return view('admin.dashboard', compact('totalUsers', 'totalAdmin', 'totalAslab'));
    }

    /**
     * Menampilkan daftar semua user
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form tambah user
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan user baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'peran' => 'required|in:Admin,Aslab',
            'mata_kuliah' => 'required|string|max:255'
        ]);

        // Enkripsi password
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan!');
    }

    /**
     * Menampilkan detail user
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan form edit user
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update data user
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'nim' => 'required|string|unique:users,nim,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'peran' => 'required|in:Admin,Aslab',
            'mata_kuliah' => 'required|string|max:255'
        ]);

        // Jika password diisi, update password
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diupdate!');
    }

    /**
     * Hapus user
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus!');
    }

    /**
     * Fitur pencarian user
     */
    public function search(Request $request)
    {
        $keyword = $request->get('search');
        
        $users = User::search($keyword)->paginate(10);
        
        return view('admin.users.index', compact('users', 'keyword'));
    }

    /**
     * Filter user berdasarkan peran
     */
    public function filterByRole($role)
    {
        $users = User::where('peran', $role)->paginate(10);
        $filterRole = $role;
        
        return view('admin.users.index', compact('users', 'filterRole'));
    }

    /**
     * Ubah status user (contoh: aktif/non-aktif)
     */
    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status == 'active' ? 'inactive' : 'active';
        $user->save();

        $status = $user->status == 'active' ? 'diaktifkan' : 'dinonaktifkan';
        
        return back()->with('success', "User berhasil $status!");
    }
}