<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Poli;
use App\Models\DoctorDetail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    /**
     * Menampilkan daftar semua Dokter.
     */
    public function index()
    {
        // Ambil semua dokter (doctor_details) beserta user & poli
        $doctors = DoctorDetail::with(['user', 'poli'])
            ->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

    /**
     * Menampilkan form tambah Dokter baru.
     */
    public function create()
    {
        $polis = Poli::all(); // Untuk dropdown poli
        return view('admin.doctors.create', compact('polis'));
    }

    /**
     * Menyimpan Dokter baru (User + DoctorDetail).
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'poli_id'  => 'required|exists:polis,id',

            // opsional
            'sip' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat akun user dokter
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'dokter',
            ]);

            // 2. Buat detail dokter
            DoctorDetail::create([
                'user_id' => $user->id,
                'poli_id' => $request->poli_id,
                'sip'     => $request->sip,
                'bio'     => $request->bio,
            ]);
        });

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Akun Dokter baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit Dokter.
     * Route model binding: parameter {doctor} = id di tabel users
     */
    public function edit(User $doctor)
    {
        // Pastikan benar-benar user dengan role dokter
        if ($doctor->role !== 'dokter') {
            abort(403, 'Akses ditolak. Pengguna ini bukan Dokter.');
        }

        $polis = Poli::all();
        $doctor->load('doctorDetail');

        return view('admin.doctors.edit', compact('doctor', 'polis'));
    }

    /**
     * Memperbarui data Dokter.
     */
    public function update(Request $request, User $doctor)
    {
        if ($doctor->role !== 'dokter') {
            return redirect()
                ->route('admin.doctors.index')
                ->with('error', 'Gagal memperbarui: Pengguna ini bukan Dokter.');
        }

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($doctor->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'poli_id'  => 'required|exists:polis,id',

            // opsional
            'sip' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $doctor) {
            // 1. Update akun user
            $doctor->update([
                'name'  => $request->name,
                'email' => $request->email,
                'password' => $request->filled('password')
                    ? Hash::make($request->password)
                    : $doctor->password,
            ]);

            // 2. Update / buat detail dokter
            $doctor->doctorDetail()->updateOrCreate(
                ['user_id' => $doctor->id],
                [
                    'poli_id' => $request->poli_id,
                    'sip'     => $request->sip,
                    'bio'     => $request->bio,
                ]
            );
        });

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Data Dokter berhasil diperbarui.');
    }

    /**
     * Menghapus Dokter.
     */
    public function destroy(User $doctor)
    {
        if ($doctor->role !== 'dokter') {
            return redirect()
                ->route('admin.doctors.index')
                ->with('error', 'Gagal menghapus: Pengguna ini bukan Dokter.');
        }

        // DoctorDetail terhapus otomatis jika FK di migrasi pakai onDelete('cascade')
        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Akun Dokter berhasil dihapus.');
    }
}
