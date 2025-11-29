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
        // Format 'N' Carbon mengembalikan 1 (Senin) hingga 7 (Minggu).
        // Pastikan nilai day_of_week di tabel schedules Anda menggunakan format nama hari atau angka 1-7 yang sesuai.
        // Jika Anda menggunakan nama hari (e.g., 'senin', 'selasa'), ganti format:
        $currentDayName = strtolower(Carbon::now()->isoFormat('dddd')); // Contoh: 'senin', 'selasa'

        $doctorsToday = Schedule::where('day_of_week', $currentDayName)
            ->with('doctor.user', 'doctor.poli')
            ->get();

        // 3. Total pengguna berdasarkan Model yang benar 🔥 PERBAIKAN DI SINI
        $totalAdmin   = User::where('role', 'admin')->count();
        $totalDoctor  = DoctorDetail::count(); // 🔥 Mengambil dari Model DoctorDetail
        $totalPatient = Patient::count();     // 🔥 Mengambil dari Model Patient

        // 4. Total obat
        $totalMedicines = Medicine::count();
        
        // 5. Janji temu terbaru
        $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();


        return view('admin.dashboard', compact(
            'pendingAppointments',
            'doctorsToday',
            'totalAdmin',
            'totalDoctor',
            'totalPatient',
            'totalMedicines',
            'recentAppointments' // Tambahkan variabel janji temu terbaru
        ));
    }
}