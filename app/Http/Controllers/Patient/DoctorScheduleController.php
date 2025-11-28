<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DoctorDetail; // Pastikan model Doctor diimport
use Illuminate\Support\Facades\Auth;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        // Ambil data pasien yang sedang login
        $patient = Auth::user()->patient;

        // Jika data pasien tidak ditemukan, hentikan proses
        if (!$patient) {
            abort(403, 'Akun ini tidak terdaftar sebagai pasien.');
        }

        // Ambil data dokter dan jadwalnya
        $doctorDetails = DoctorDetail::with(['user', 'poli', 'schedules']) // Memuat relasi yang diperlukan
            ->get(); // Ambil seluruh data dokter yang ada

        // Kirim data dokter ke view
        return view('patient.doctor_schedules.index', compact('doctorDetails'));
    }
}
