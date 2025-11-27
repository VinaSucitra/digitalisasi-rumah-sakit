<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Poli;
use Illuminate\Http\Request;

class DoctorListController extends Controller
{
    /**
     * Menampilkan daftar semua dokter dengan filter.
     */
    public function index(Request $request)
    {
        $polis = Poli::all();

        $doctors = Doctor::with(['user', 'poli'])
            ->whereHas('user', function($query) {
                $query->where('is_active', true); // Hanya tampilkan dokter yang aktif
            });
        
        // Filter berdasarkan Poli
        if ($request->filled('poli_id') && $request->poli_id !== 'all') {
            $doctors->where('poli_id', $request->poli_id);
        }

        // Filter pencarian berdasarkan nama dokter
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $doctors->whereHas('user', function ($query) use ($searchTerm) {
                $query->where('name', 'like', $searchTerm);
            });
        }

        $doctors = $doctors->paginate(12)->withQueryString();

        return view('doctors.index', compact('doctors', 'polis'));
    }

    /**
     * Menampilkan halaman profil detail dokter.
     */
    public function show(Doctor $doctor)
    {
        // Muat relasi yang dibutuhkan
        $doctor->load(['user', 'poli', 'schedules']);

        // Jika Anda memiliki sistem review, Anda bisa memuatnya di sini
        // $reviews = $doctor->reviews()->latest()->limit(5)->get();

        return view('doctors.show', compact('doctor'));
    }
}
