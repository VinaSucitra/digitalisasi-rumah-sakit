<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Poli;
use App\Models\DoctorDetail;
use App\Models\Schedule;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;

class AppointmentController extends Controller
{
    /**
     * List janji temu pasien
     */
    public function index()
    {
        $patient = auth()->user()->patient;

        $appointments = Appointment::where('patient_id', $patient->id)
                        ->with(['doctor.user', 'schedule', 'doctor.poli'])
                        ->latest()
                        ->get();

        return view('patient.appointments.index', compact('appointments'));
    }


    /**
     * Step 1: Pilih Poli
     */
    public function create()
    {
        $polis = Poli::all();

        return view('patient.appointments.create_poli', compact('polis'));
    }


    /**
     * Step 2: Pilih Dokter berdasarkan Poli
     */
    public function selectDoctor($poli_id)
    {
        $poli = Poli::findOrFail($poli_id);

        $doctors = DoctorDetail::where('poli_id', $poli_id)
                    ->with('user')
                    ->get();

        return view('patient.appointments.select_doctor', compact('poli', 'doctors'));
    }


    /**
     * Step 3: Pilih Jadwal Dokter
     */
    public function selectSchedule($doctor_id)
    {
        $doctor = DoctorDetail::findOrFail($doctor_id);

        $schedules = Schedule::where('doctor_id', $doctor_id)
                        ->orderBy('day_of_week')
                        ->orderBy('start_time')
                        ->get();

        return view('patient.appointments.select_schedule', compact('doctor', 'schedules'));
    }


    /**
     * Step 4: Simpan Janji Temu
     */
    public function store(Request $request)
    {
        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'complaint' => 'required|string|max:255',
        ]);

        $patient = auth()->user()->patient;
        $schedule = Schedule::findOrFail($request->schedule_id);

        DB::transaction(function () use ($request, $patient, $schedule) {

            Appointment::create([
                'patient_id'   => $patient->id,
                'doctor_id'    => $schedule->doctor_id,
                'schedule_id'  => $schedule->id,
                'booking_date' => $request->booking_date,
                'complaint'    => $request->complaint,
                'status'       => 'pending',
            ]);
        });

        return redirect()
                ->route('patient.appointments.index')
                ->with('success', 'Janji temu berhasil dibuat! Harap menunggu persetujuan dokter.');
    }


    /**
     * Detail Janji Temu
     */
    public function show(Appointment $appointment)
    {
        if ($appointment->patient_id !== auth()->user()->patient->id) {
            abort(403);
        }

        $appointment->load(['doctor.user', 'schedule', 'doctor.poli']);

        return view('patient.appointments.show', compact('appointment'));
    }
}
