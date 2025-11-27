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
        $patient = Auth::user()->patient;

        // 1. Ambil janji temu terbaru
        $latestAppointment = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'schedule', 'doctor.poli'])
            ->latest('booking_date')
            ->first();

        // 2. Ambil janji temu yang sudah disetujui (notifikasi)
        $approvedAppointments = Appointment::where('patient_id', $patient->id)
            ->where('status', 'approved')
            ->whereDate('booking_date', '>=', Carbon::now()->toDateString())
            ->with(['doctor.user', 'schedule'])
            ->get();

        // 3. Resep siap diambil (jika ada fitur farmasi)
        $readyPrescriptions = MedicalRecord::where('patient_id', $patient->id)
            ->whereHas('prescriptions', function ($query) {
                $query->where('status', 'ready');
            })
            ->with('prescriptions.items.medicine')
            ->get();

        // 4. Riwayat rekam medis singkat
        $latestRecords = MedicalRecord::where('patient_id', $patient->id)
            ->latest('visit_date')
            ->limit(5)
            ->get();

        return view('patient.dashboard', compact(
            'latestAppointment',
            'approvedAppointments',
            'readyPrescriptions',
            'latestRecords'
        ));
    }
}
