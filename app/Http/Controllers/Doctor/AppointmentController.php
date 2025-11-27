<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Menampilkan semua appointment untuk dokter yang sedang login.
     * Pending dan Approved ditampilkan paling atas.
     */
    public function index()
    {
        // Ambil detail dokter dari user yang login
        $doctorDetail = Auth::user()->doctorDetail;

        if (! $doctorDetail) {
            abort(403, 'Akun ini tidak terdaftar sebagai dokter.');
        }

        $appointments = Appointment::where('doctor_id', $doctorDetail->id)
            ->with(['patient.user', 'schedule', 'doctor.poli'])
            ->orderByRaw("
                CASE 
                    WHEN status = 'pending' THEN 0 
                    WHEN status = 'approved' THEN 1
                    WHEN status = 'rejected' THEN 2
                    ELSE 3 
                END
            ")
            ->orderBy('booking_date', 'asc')
            ->get();

        return view('doctor.appointments.index', compact('appointments'));
    }

    /**
     * Menampilkan detail appointment untuk dokter.
     */
    public function show(Appointment $appointment)
    {
        $doctorDetail = Auth::user()->doctorDetail;

        if (! $doctorDetail || $appointment->doctor_id !== $doctorDetail->id) {
            abort(403, 'Akses ditolak');
        }

        $appointment->load(['patient.user', 'schedule', 'doctor.poli']);

        return view('doctor.appointments.show', compact('appointment'));
    }

    /**
     * Update status appointment (approved / rejected / done) oleh dokter.
     */
    public function update(Request $request, Appointment $appointment)
    {
        $doctorDetail = Auth::user()->doctorDetail;

        if (! $doctorDetail || $appointment->doctor_id !== $doctorDetail->id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'status'          => 'required|in:approved,rejected,done',
            'rejected_reason' => 'nullable|string|max:255',
        ]);

        // Jika dokter menolak, wajib isi alasan
        if ($request->status === 'rejected' && empty($request->rejected_reason)) {
            return back()->withErrors([
                'rejected_reason' => 'Alasan penolakan wajib diisi jika status ditolak.',
            ]);
        }

        $appointment->update([
            'status'          => $request->status,
            'rejected_reason' => $request->rejected_reason,
        ]);

        return redirect()
            ->route('doctor.appointments.index')
            ->with('success', 'Status janji temu berhasil diperbarui.');
    }
}
