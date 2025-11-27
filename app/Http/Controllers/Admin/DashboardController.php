<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorDetail;
use App\Models\Patient;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Schedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Janji temu pending
        $pendingAppointments = Appointment::where('status', 'pending')->count();

        // 2. Dokter yang bertugas hari ini
        $today = Carbon::now()->format('N'); // 1 = Senin, 7 = Minggu
        $doctorsToday = Schedule::where('day_of_week', $today)
            ->with('doctor.user', 'doctor.poli')
            ->get();

        // 3. Total pengguna berdasarkan role
        $totalAdmin  = User::where('role', 'admin')->count();
        $totalDoctor = User::where('role', 'dokter')->count();
        $totalPatient = User::where('role', 'pasien')->count();

        // 4. Total obat
        $totalMedicines = Medicine::count();

        return view('admin.dashboard', compact(
            'pendingAppointments',
            'doctorsToday',
            'totalAdmin',
            'totalDoctor',
            'totalPatient',
            'totalMedicines'
        ));
    }
}
