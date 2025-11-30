<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Poli;
use App\Models\DoctorDetail;

class ProfileController extends Controller
{
    /**
     * Tampilkan form profil dokter.
     * Data diambil dari:
     *  - users          : name, email
     *  - doctor_details : poli_id, sip, bio
     */
    public function edit()
    {
        $user = auth()->user();

        // Pastikan yang akses memang dokter
        if ($user->role !== 'doctor') {
            abort(403, 'Hanya dokter yang dapat mengubah profil dokter.');
        }

        // Relasi detail dokter (bisa null kalau belum dibuat)
        $doctor = $user->doctorDetail ?? null;

        // Daftar poli (untuk dropdown, kalau mau ditampilkan di form)
        $polis = Poli::all();

        return view('doctor.profile.edit', compact('user', 'doctor', 'polis'));
    }

    /**
     * Simpan perubahan profil dokter.
     * Mengupdate:
     *  - users          : name, email
     *  - doctor_details : poli_id (opsional), sip, bio
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role !== 'doctor') {
            abort(403, 'Hanya dokter yang dapat mengubah profil dokter.');
        }

        // Validasi mirip dengan Admin\DoctorController,
        // tapi tanpa password karena dokter tidak mengubah password di sini.
        $validated = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            // kalau poli dokter boleh diubah dari halaman profil, gunakan required
            // kalau tidak, biarkan nullable (hanya admin yang ganti)
            'poli_id' => ['nullable', 'exists:polis,id'],

            'sip' => ['nullable', 'string', 'max:255'],
            'bio' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $user) {
            // 1) Update data user (name & email)
            $user->update([
                'name'  => $validated['name'],
                'email' => $validated['email'],
            ]);

            // 2) Siapkan data untuk tabel doctor_details
            $detailData = [
                'sip' => $validated['sip'] ?? null,
                'bio' => $validated['bio'] ?? null,
            ];

            // Kalau poli_id dikirim dan tidak null, ikut di-update
            if (!empty($validated['poli_id'])) {
                $detailData['poli_id'] = $validated['poli_id'];
            }

            // 3) Update atau buat doctor_details
            $user->doctorDetail()->updateOrCreate(
                ['user_id' => $user->id],
                $detailData
            );
        });

        return redirect()
            ->route('doctor.profile')
            ->with('success', 'Profil dokter berhasil diperbarui.');
    }
}
