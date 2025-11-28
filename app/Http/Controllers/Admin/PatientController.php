<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PatientController extends Controller
{
    /**
     * LIST PASIEN
     */
    public function index()
    {
        // Ambil semua user dengan role 'pasien' + relasi detail pasien
        $patients = User::where('role', 'patient')
            ->with('patient')
            ->get();

        return view('admin.patients.index', compact('patients'));
    }

    /**
     * FORM TAMBAH PASIEN
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * SIMPAN PASIEN BARU
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|string|email|max:255|unique:users',
            'password'   => 'required|string|min:8|confirmed',

            'birth_date' => 'nullable|date',
            'gender'     => ['nullable', Rule::in(['L','P'])],
            'address'    => 'nullable|string',
            'phone'      => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request) {
            // 1. Buat user dengan role pasien
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'patient',
            ]);

            // 2. Buat detail pasien (no_rm + biodata)
            Patient::create([
                'user_id'    => $user->id,
                'no_rm'      => 'RM-' . str_pad($user->id, 5, '0', STR_PAD_LEFT),
                'birth_date' => $request->birth_date,
                'gender'     => $request->gender,
                'address'    => $request->address,
                'phone'      => $request->phone,
            ]);
        });

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Pasien baru berhasil ditambahkan.');
    }

    /**
     * FORM EDIT PASIEN
     */
    public function edit($id)
    {
        // Cari user dengan role = pasien, jika tidak ketemu => 404
        $patient = User::with('patient')
            ->where('role', 'patient')
            ->findOrFail($id);

        return view('admin.patients.edit', compact('patient'));
    }

    /**
     * UPDATE PASIEN
     */
    public function update(Request $request, $id)
    {
        $patient = User::with('patient')
            ->where('role', 'patient')
            ->findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($patient->id),
            ],
            'password'   => 'nullable|string|min:8|confirmed',

            'birth_date' => 'nullable|date',
            'gender'     => ['nullable', Rule::in(['L','P'])],
            'address'    => 'nullable|string',
            'phone'      => 'nullable|string|max:20',
        ]);

        DB::transaction(function () use ($request, $patient) {

            // 1. Update user
            $dataUser = [
                'name'  => $request->name,
                'email' => $request->email,
            ];

            if ($request->filled('password')) {
                $dataUser['password'] = Hash::make($request->password);
            }

            $patient->update($dataUser);

            // 2. Update / buat detail pasien
            $patient->patient()->updateOrCreate(
                ['user_id' => $patient->id],
                [
                    'no_rm'      => $patient->patient->no_rm ?? 'RM-' . str_pad($patient->id, 5, '0', STR_PAD_LEFT),
                    'birth_date' => $request->birth_date,
                    'gender'     => $request->gender,
                    'address'    => $request->address,
                    'phone'      => $request->phone,
                ]
            );
        });

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * HAPUS PASIEN
     */
    public function destroy($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        DB::transaction(function () use ($patient) {
            // hapus detail pasien dulu (jika FK belum cascade)
            Patient::where('user_id', $patient->id)->delete();
            // lalu hapus user
            $patient->delete();
        });

        return redirect()
            ->route('admin.patients.index')
            ->with('success', 'Pasien berhasil dihapus.');
    }
}
