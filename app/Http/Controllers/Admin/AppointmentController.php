<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorDetail;
use App\Models\Schedule;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Admin melihat semua janji temu.
     * Pending ditampilkan paling atas.
     */
    public function index()
    {
        $appointments = Appointment::with([
            'patient.user',
            'doctor.user',
            'doctor.poli',
            'schedule',
        ])
        ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
        ->orderBy('booking_date', 'asc')
        ->paginate(10);

        return view('admin.appointments.index', compact('appointments'));
    }

    public function create()
    {
        $patients = Patient::all();  // Menambah data pasien
        $doctors  = DoctorDetail::with('user')->get();  // Menambah data dokter
        $schedules = Schedule::orderBy('day_of_week')->orderBy('start_time')->get();  // Menambah data jadwal

        return view('admin.appointments.create', compact('patients', 'doctors', 'schedules'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'schedule_id'  => 'required|exists:schedules,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'complaint'    => 'required|string|max:500',
        ]);

        $schedule = Schedule::with('doctor')->findOrFail($data['schedule_id']);

        Appointment::create([
            'patient_id'   => $data['patient_id'],
            'doctor_id'    => $schedule->doctor_id,   // ambil dari schedule
            'schedule_id'  => $schedule->id,
            'booking_date' => $data['booking_date'],
            'complaint'    => $data['complaint'],
            'status'       => 'pending',
        ]);

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Janji Temu berhasil dibuat.');
    }

    public function show(Appointment $appointment)
    {
        $appointment->load([
            'patient.user',
            'doctor.user',
            'doctor.poli',
            'schedule',
        ]);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function edit(Appointment $appointment)
    {
        // Load data yang diperlukan untuk form edit
        $patients = Patient::all(); // Menambah data pasien
        $doctors = DoctorDetail::with('user')->get(); // Menambah data dokter
        $schedules = Schedule::orderBy('day_of_week')->orderBy('start_time')->get(); // Menambah data jadwal

        // Data appointment yang terkait
        $appointment->load([
            'patient.user',
            'doctor.user',
            'doctor.poli',
            'schedule',
        ]);

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors', 'schedules'));  // Kirimkan data pasien, dokter, dan jadwal
    }

    /**
     * Update data janji temu.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // Validasi input dari form
        $data = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            'schedule_id'  => 'required|exists:schedules,id',  // Pastikan schedule id valid
            'booking_date' => 'required|date|after_or_equal:today',
            'complaint'    => 'required|string|max:500',
            'status'       => 'required|in:pending,approved,rejected,done',
        ]);

        // Ambil schedule yang sesuai dan dokter yang terkait
        $schedule = Schedule::with('doctor')->findOrFail($data['schedule_id']);

        // Perbarui data janji temu
        $appointment->update([
            'patient_id'   => $data['patient_id'],
            'doctor_id'    => $schedule->doctor_id, // Ambil dokter dari schedule
            'schedule_id'  => $schedule->id,
            'booking_date' => $data['booking_date'],
            'complaint'    => $data['complaint'],
            'status'       => $data['status'],
        ]);

        // Redirect ke halaman daftar janji temu dengan pesan sukses
        return redirect()->route('admin.appointments.index')->with('success', 'Janji Temu berhasil diperbarui.');
    }
}
