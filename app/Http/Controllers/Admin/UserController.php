<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User; // Asumsi model User
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna dengan fitur filter berdasarkan peran.
     */
    public function index(Request $request)
    {
        // Query dasar
        $users = User::orderBy('name');

        // Filter berdasarkan peran (role)
        if ($request->filled('role') && $request->role !== 'all') {
            // Asumsi kolom peran di tabel 'users' adalah 'role'
            $users->where('role', $request->role);
        }

        // Filter pencarian
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $users->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
        }

        $users = $users->paginate(15)->withQueryString();

        // Daftar peran yang tersedia (asumsi: admin, doctor, patient)
        $roles = ['admin', 'doctor', 'patient'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Menampilkan formulir untuk membuat pengguna baru.
     */
    public function create()
    {
        $roles = ['admin', 'doctor', 'patient'];
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'doctor', 'patient'])],
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            // Tambahkan kolom lain seperti address, phone, dll. jika diperlukan
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru ' . $user->name . ' berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pengguna.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Menampilkan formulir untuk mengedit pengguna.
     */
    public function edit(User $user)
    {
        $roles = ['admin', 'doctor', 'patient'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Memperbarui pengguna yang ada.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            // Pastikan email unik, kecuali email milik pengguna yang sedang diedit
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed', // Password opsional saat update
            'role' => ['required', Rule::in(['admin', 'doctor', 'patient'])],
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];

        // Jika password diisi, hash dan update password
        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Detail pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna.
     */
    public function destroy(User $user)
    {
        // Pencegahan: Jangan biarkan admin menghapus dirinya sendiri
        if (auth()->user()->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Pengguna ' . $user->name . ' berhasil dihapus.');
    }

    /**
     * Mengelola profil dokter (jika ada data tambahan yang perlu dikelola).
     * Contoh: Mengupdate spesialisasi dokter.
     */
    public function manageDoctorProfile(Request $request, User $user)
    {
        if ($user->role !== 'doctor') {
            return back()->with('error', 'Fitur ini hanya untuk pengguna dengan peran Dokter.');
        }

        // Implementasi logika manajemen profil dokter di sini
        // Misalnya, jika Anda memiliki Model DoctorProfile yang berelasi dengan User
        // $validatedData = $request->validate([...]);
        // $user->doctorProfile()->update($validatedData);

        return back()->with('success', 'Profil Dokter berhasil diperbarui.');
    }
}