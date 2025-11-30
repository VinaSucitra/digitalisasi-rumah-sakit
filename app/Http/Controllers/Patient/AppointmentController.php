<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\Poli;
use App\Models\DoctorDetail;
use App\Models\Schedule;

class AppointmentController extends Controller
{
    /**
     * List semua janji temu milik pasien yang login.
     */
    public function index()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            abort(403, 'Akun ini tidak terdaftar sebagai pasien.');
        }

        $appointments = Appointment::where('patient_id', $patient->id)
            ->with(['doctor.user', 'doctor.poli', 'schedule'])
            ->latest('booking_date')
            ->paginate(10);

        return view('patient.appointments.index', compact('appointments'));
    }

    /**
     * Form buat janji temu baru.
     * (Pilih Poli, Dokter, Jadwal, Tanggal, Keluhan)
     */
    public function create()
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            abort(403, 'Akun ini tidak terdaftar sebagai pasien.');
        }

        // Semua poli
        $polis = Poli::orderBy('name')->get();

        // Detail dokter + relasi user & poli (kalau mau dipakai di tempat lain)
        $doctors = DoctorDetail::with(['user', 'poli'])
            ->whereNotNull('poli_id')   // hanya dokter yang sudah punya poli
            ->get();

        // Semua jadwal praktik, tampilkan di dropdown
        $schedules = Schedule::with(['doctor.user', 'doctor.poli'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        return view('patient.appointments.create', compact(
            'patient',
            'polis',
            'doctors',
            'schedules'
        ));
    }

    /**
     * Simpan janji temu baru.
     */
    public function store(Request $request)
    {
        $patient = Auth::user()->patient;

        if (!$patient) {
            abort(403, 'Akun ini tidak terdaftar sebagai pasien.');
        }

        $validated = $request->validate([
            'poli_id'      => 'required|exists:polis,id',
            'doctor_id'    => 'required|exists:doctor_details,id',
            'schedule_id'  => 'required|exists:schedules,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'complaint'    => 'required|string|max:500',
        ]);

        $validated['patient_id'] = $patient->id;
        $validated['status']     = 'pending';

        Appointment::create($validated);

        return redirect()
            ->route('patient.appointments.index')
            ->with('success', 'Janji temu berhasil dibuat dan menunggu persetujuan.');
    }

    /**
     * Detail satu janji temu.
     */
    public function show(Appointment $appointment)
    {
        $patient = Auth::user()->patient;

        if (!$patient || $appointment->patient_id !== $patient->id) {
            abort(403, 'Anda tidak berhak melihat janji temu ini.');
        }

        $appointment->load(['doctor.user', 'doctor.poli', 'schedule']);

        return view('patient.appointments.show', compact('appointment'));
    }

    /**
     * 🔥 AJAX: Ambil dokter berdasarkan poli yang dipilih di form pasien.
     */
    public function getDoctorsByPoli(Poli $poli)
    {
        $doctors = DoctorDetail::with(['user', 'poli'])
            ->where('poli_id', $poli->id)
            ->orderBy('id')
            ->get()
            ->map(function ($doctor) {
                return [
                    'id'        => $doctor->id,
                    'name'      => $doctor->user->name ?? 'Dokter',
                    'poli_name' => $doctor->poli->name ?? null,
                ];
            });

        return response()->json($doctors);
    }
}
