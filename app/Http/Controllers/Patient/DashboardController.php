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

        // Jika akun bukan pasien (belum punya relasi patient)
        if (! $patient) {
            abort(403, 'Akun ini tidak terdaftar sebagai pasien.');
        }

        // 1. Jumlah janji temu dengan status pending
        $pendingAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'pending')
            ->count();

        // 2. Total rekam medis
        $totalRecords = MedicalRecord::where('patient_id', $patient->id)->count();

        // 3. Janji temu terakhir yang sudah disetujui (untuk kartu "Status Janji Temu Terakhir")
        $latestAppointment = Appointment::where('patient_id', $patient->id)
            ->where('status', 'approved')
            ->with(['doctor.user', 'schedule', 'doctor.poli'])
            ->orderByDesc('booking_date')
            ->orderByDesc('id')
            ->first();

        // 4. Daftar janji temu approved yang akan datang (>= hari ini)
        $approvedAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'approved')
            ->whereDate('booking_date', '>=', Carbon::today()->toDateString())
            ->with(['doctor.user', 'schedule', 'doctor.poli'])
            ->orderBy('booking_date')
            ->get();

        // 5. Rekam medis yang punya resep berstatus "ready" (siap diambil)
        $readyPrescriptions = MedicalRecord::where('patient_id', $patient->id)
            ->whereHas('prescriptions', function ($query) {
                $query->where('status', 'ready');
            })
            ->with(['doctor.user', 'prescriptions.items.medicine'])
            ->orderByDesc('visit_date')
            ->get();

        // 6. Rekam medis terbaru (limit 5)
        $latestRecords = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor.user', 'doctor.poli', 'prescriptions'])
            ->orderByDesc('visit_date')
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
