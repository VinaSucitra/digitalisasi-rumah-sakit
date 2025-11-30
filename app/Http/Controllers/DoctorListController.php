<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Poli;
use Illuminate\Http\Request;

class DoctorListController extends Controller
{
    /**
     * Halaman daftar dokter & jadwal (public).
     */
    public function index(Request $request)
    {
        $search = $request->search;
        $poliId = $request->poli_id;

        // Data poli untuk dropdown filter
        $polis = Poli::orderBy('name')->get();

        // Query dokter + relasi
        $doctors = Doctor::with(['user', 'poli', 'schedules'])
            ->whereHas('user', function ($query) {
                $query->where('is_active', true);
            })
            ->when($poliId, function ($query) use ($poliId) {
                // kalau poli_id tidak kosong, filter
                $query->where('poli_id', $poliId);
            })
            ->when($search, function ($query) use ($search) {
                $query->whereHas('user', function ($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })
            ->paginate(12)
            ->withQueryString();

        return view('doctors.index', compact('doctors', 'polis', 'search', 'poliId'));
    }

    /**
     * Detail profil dokter (kalau dibutuhkan).
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'poli', 'schedules']);

        return view('doctors.show', compact('doctor'));
    }
}
