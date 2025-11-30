<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PatientProfileController extends Controller
{
    // TAMPILKAN HALAMAN PROFIL PASIEN
    public function edit()
    {
        $user   = Auth::user();      // user yang login
        $detail = $user->patient;    // relasi ke detail pasien (model Patient)

        return view('patient.profile', compact('user', 'detail'));
    }

    // UPDATE PROFIL PASIEN
    public function update(Request $request)
    {
        $user   = Auth::user();
        $detail = $user->patient;

        // VALIDASI
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'email'        => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password'     => ['nullable', 'confirmed', 'min:8'],
            'birth_date'   => ['nullable', 'date'],
            'gender'       => ['nullable', Rule::in(['L', 'P'])],
            'phone'        => ['nullable', 'string', 'max:30'],
            'address'      => ['nullable', 'string', 'max:500'],
        ]);

        // UPDATE USER
        $user->name  = $validated['name'];
        $user->email = $validated['email'];

        if (!empty($validated['password'])) {
            $user->password = bcrypt($validated['password']);
        }
        $user->save();

        // PASTIKAN DETAIL PASIEN ADA
        if (!$detail) {
            $detail = $user->patient()->create([
                'no_rm' => null, // ganti sesuai logika generate RM kalau ada
            ]);
        }

        // UPDATE DETAIL PASIEN
        $detail->birth_date = $validated['birth_date'] ?? null;
        $detail->gender     = $validated['gender'] ?? null;
        $detail->phone      = $validated['phone'] ?? null;
        $detail->address    = $validated['address'] ?? null;
        $detail->save();

        return redirect()
            ->route('patient.profile.edit')
            ->with('success', 'Profil Anda berhasil diperbarui.');
    }
}
