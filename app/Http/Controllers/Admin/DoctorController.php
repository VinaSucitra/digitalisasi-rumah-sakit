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
    public function index()
    {
        $doctors = DoctorDetail::with(['user', 'poli'])
            ->paginate(10);

        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $polis = Poli::all(); 
        return view('admin.doctors.create', compact('polis'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'poli_id'  => 'required|exists:polis,id',
            'sip' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'doctor',
            ]);

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

    public function edit(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            abort(403, 'Akses ditolak. Pengguna ini bukan Dokter.');
        }

        $polis = Poli::all();
        $doctor->load('doctorDetail');

        return view('admin.doctors.edit', compact('doctor', 'polis'));
    }

    public function update(Request $request, User $doctor)
    {
        if ($doctor->role !== 'doctor') {
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
            'sip' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $doctor) {
            $doctor->update([
                'name'  => $request->name,
                'email' => $request->email,
                'password' => $request->filled('password')
                    ? Hash::make($request->password)
                    : $doctor->password,
            ]);

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

    public function destroy(User $doctor)
    {
        if ($doctor->role !== 'doctor') {
            return redirect()
                ->route('admin.doctors.index')
                ->with('error', 'Gagal menghapus: Pengguna ini bukan Dokter.');
        }

        $doctor->delete();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Akun Dokter berhasil dihapus.');
    }
}
