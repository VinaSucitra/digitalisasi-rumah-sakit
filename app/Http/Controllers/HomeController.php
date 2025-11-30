<?php

namespace App\Http\Controllers;

use App\Models\Poli;
use App\Models\DoctorDetail;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Halaman beranda publik (Guest).
     * Menampilkan ringkasan poli dan beberapa dokter unggulan (opsional).
     */
    public function index()
    {
        // Data poli untuk section "Layanan / Spesialisasi"
        $polis = Poli::withCount('doctors')
            ->orderBy('name')
            ->get();

        // Jika mau dipakai nanti untuk section dokter pilihan
        $featuredDoctors = DoctorDetail::with(['user', 'poli'])
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('home.index', compact('polis', 'featuredDoctors'));
    }
}
