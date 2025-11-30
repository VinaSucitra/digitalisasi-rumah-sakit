<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::orderBy('name');

        if ($request->filled('role') && $request->role !== 'all') {
            $users->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $users->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                  ->orWhere('email', 'like', $searchTerm);
            });
        }

        $users = $users->paginate(15)->withQueryString();
        $roles = ['admin', 'doctor', 'patient'];

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = ['admin', 'doctor', 'patient'];
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role'     => ['required', Rule::in(['admin', 'doctor', 'patient'])],
        ]);

        $user = User::create([
            'name'     => $validatedData['name'],
            'email'    => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role'     => $validatedData['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna baru ' . $user->name . ' berhasil ditambahkan.');
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = ['admin', 'doctor', 'patient'];
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role'     => ['required', Rule::in(['admin', 'doctor', 'patient'])],
        ]);

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->role = $validatedData['role'];

        if ($request->filled('password')) {
            $user->password = Hash::make($validatedData['password']);
        }

        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'Detail pengguna ' . $user->name . ' berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if (auth()->user()->id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna ' . $user->name . ' berhasil dihapus.');
    }

    public function manageDoctorProfile(Request $request, User $user)
    {
        if ($user->role !== 'doctor') {
            return back()->with('error', 'Fitur ini hanya untuk pengguna dengan peran Dokter.');
        }

        return back()->with('success', 'Profil Dokter berhasil diperbarui.');
    }
}