<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DoctorDetail; 
use App\Models\User;
use App\Models\Schedule;
use App\Models\Poli;

class DoctorScheduleController extends Controller
{
    /**
     * Tampilkan daftar dokter beserta jadwal mereka untuk Pasien.
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Ambil semua dokter, lalu load relasi yang dibutuhkan (User, Poli, Schedules)
        $doctors = DoctorDetail::with('user', 'poli', 'schedules')->get();

        // View yang dibutuhkan: resources/views/patient/doctor_schedules/index.blade.php
        return view('patient.doctor_schedules.index', [ 'doctors' => $doctorDetails ]);
    }
}