<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $patient = $user->patient;

        // Kalau user belum punya profil pasien
        if (!$patient) {
            abort(403, 'Akun ini tidak terdaftar sebagai pasien.');
        }

        // 1. Janji temu pending (untuk kartu atas)
        $pendingAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->count();

        // 2. Total rekam medis (untuk kartu atas)
        $totalRecords = MedicalRecord::where('patient_id', $patient->id)->count();

        // 3. Janji temu approved terakhir
        $latestAppointment = Appointment::where('patient_id', $patient->id)
            ->where('status', 'approved')
            ->with(['doctor.user', 'schedule', 'doctor.poli'])
            ->latest('booking_date')
            ->first();

        // 4. Janji temu approved ke depan (notifikasi)
        $approvedAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'approved')
            ->whereDate('booking_date', '>=', Carbon::now()->toDateString())
            ->with(['doctor.user', 'schedule'])
            ->get();

        // 5. Resep siap diambil (jika fitur farmasi aktif)
        $readyPrescriptions = MedicalRecord::where('patient_id', $patient->id)
            ->whereHas('prescriptions', function ($query) {
                $query->where('status', 'ready');
            })
            ->with('prescriptions.items.medicine')
            ->get();

        // 6. Riwayat rekam medis singkat
        $latestRecords = MedicalRecord::where('patient_id', $patient->id)
            ->latest('visit_date')
            ->limit(5)
            ->get();

        return view('patient.dashboard', compact(
            'pendingAppointments',
            'totalRecords',
            'latestAppointment',
            'approvedAppointments',
            'readyPrescriptions',
            'latestRecords'
        ));
    }
}
