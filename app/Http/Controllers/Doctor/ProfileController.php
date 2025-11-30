<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * Tampilkan form profil dokter.
     */
    public function edit()
    {
        $user = auth()->user();          // akun user dokter
        $doctor = $user->doctorDetail ?? null; // detail dokter (kalau ada)

        return view('doctor.profile.edit', compact('user', 'doctor'));
    }

    /**
     * Simpan perubahan profil dokter.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        // Validasi sederhana (nama & email)
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
        ]);

        // Update data user
        $user->update($data);

        // Kalau nanti mau tambah field lain (no hp, alamat, dll)
        // bisa update di $user->doctorDetail di sini.

        return redirect()
            ->route('doctor.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
