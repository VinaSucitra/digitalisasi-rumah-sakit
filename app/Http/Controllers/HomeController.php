<?php

namespace App\Http\Controllers;

use App\Models\Poli; 
use App\Models\DoctorDetail; 
use App\Models\Schedule; // Tidak dibutuhkan di sini jika method listDoctors dihapus
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda (index) dengan data ringkasan.
     */
    public function index()
    {
        // Ambil data Poli/Layanan untuk ditampilkan (Section LAYANAN)
        $polis = Poli::withCount('doctors')->limit(6)->get();

        // Ambil beberapa dokter unggulan atau dokter acak (Section PROFIL DOKTER PILIHAN)
        $featuredDoctors = DoctorDetail::with('user', 'poli')->inRandomOrder()->limit(4)->get();

        // return view('home.index', compact('polis', 'featuredDoctors'));

        // CATATAN: Karena view index.blade.php Anda menggunakan placeholder, 
        // pastikan Anda mengirimkan data yang dibutuhkan view tersebut, yaitu $polis.
        return view('home.index', compact('polis')); 
    }

    /* |--------------------------------------------------------------------------
    | METHOD listDoctors() DIHAPUS (Dipindahkan ke Public\DoctorController)
    |-------------------------------------------------------------------------- */
}