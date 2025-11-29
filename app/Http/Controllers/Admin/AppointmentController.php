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
        $patients = Patient::all(); 
        $doctors  = DoctorDetail::with('user')->get(); 
        $schedules = Schedule::orderBy('day_of_week')->orderBy('start_time')->get(); 

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
        $patients = Patient::all(); 
        // Doctors di sini adalah DoctorDetail ID (bukan User ID)
        $doctors = DoctorDetail::with('user', 'poli')->get(); 
        $schedules = Schedule::orderBy('day_of_week')->orderBy('start_time')->get(); 

        // Data appointment yang terkait
        $appointment->load([
            'patient.user',
            'doctor.user',
            'doctor.poli',
            'schedule',
        ]);

        return view('admin.appointments.edit', compact('appointment', 'patients', 'doctors', 'schedules')); 
    }

    /**
     * Update data janji temu.
     */
    public function update(Request $request, Appointment $appointment)
    {
        // 🔥 PERBAIKAN: Mengganti schedule_id dengan doctor_id dari form
        $data = $request->validate([
            'patient_id'   => 'required|exists:patients,id',
            // Kita menggunakan doctor_id yang dikirim form.
            'doctor_id'    => 'required|exists:doctor_details,id',
            // Menghapus 'after_or_equal:today' agar Admin bisa mengedit tanggal lama
            'booking_date' => 'required|date',
            'complaint'    => 'required|string|max:500',
            'status'       => 'required|in:pending,approved,rejected,done',
        ]);

        // Catatan: Karena schedule_id tidak ada di form edit, kita tidak akan mengubahnya.
        // Jika schedule_id harus diubah, Anda perlu menambahkan field schedule_id di view.

        try {
            // Perbarui data janji temu
            $appointment->update([
                'patient_id'   => $data['patient_id'],
                // doctor_id di form edit Anda merujuk ke DoctorDetail ID, 
                // yang sesuai dengan kolom doctor_id di tabel appointments.
                'doctor_id'    => $data['doctor_id'], 
                'booking_date' => $data['booking_date'],
                'complaint'    => $data['complaint'],
                'status'       => $data['status'],
                // schedule_id TIDAK DIMASUKKAN agar tetap menggunakan nilai lama
            ]);

            // Redirect ke halaman daftar janji temu dengan pesan sukses
            return redirect()->route('admin.appointments.index')->with('success', 'Janji Temu berhasil diperbarui.');

        } catch (\Exception $e) {
             // Tangani error jika terjadi masalah database atau model
             \Log::error("Appointment Update Failed: " . $e->getMessage());
             return back()->withInput()->with('error', 'Gagal menyimpan perubahan. Periksa log server.');
        }
    }
    
    // Fungsi destroy() jika ada
}