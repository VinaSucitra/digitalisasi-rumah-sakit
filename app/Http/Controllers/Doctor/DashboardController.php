<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $doctor = $user->doctorDetail;

        if (!$doctor) {
            abort(403, 'Akun ini tidak terdaftar sebagai dokter.');
        }

        $today = Carbon::now()->toDateString();

        // 1. Jumlah janji temu pending untuk dokter ini
        $pendingCount = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'pending')
            ->count();

        // 2. Janji temu approved untuk hari ini
        $todayApproved = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->whereDate('booking_date', $today)
            ->with(['patient.user', 'schedule'])
            ->orderBy('booking_date', 'asc')
            ->get();

        // 3. 5 pasien terbaru yang telah diperiksa (rekam medis)
        $latestPatients = MedicalRecord::where('doctor_id', $doctor->id)
            ->with(['patient.user'])
            ->latest('visit_date')
            ->limit(5)
            ->get();

        // 4. Total jadwal praktik dokter ini
        $totalSchedules = $doctor->schedules()->count();

        // 5. Daftar antrian hari ini (QUEUE SYSTEM)
        $todayQueue = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->whereDate('booking_date', $today)
            ->orderBy('queue_number', 'asc')
            ->with('patient.user')
            ->get();

        return view('doctor.dashboard', compact(
            'user',
            'pendingCount',
            'todayApproved',
            'latestPatients',
            'totalSchedules',
            'today',
            'todayQueue'
        ));
    }
}
