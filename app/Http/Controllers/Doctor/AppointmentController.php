<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $doctorDetail = Auth::user()->doctorDetail;

        if (! $doctorDetail) {
            abort(403, 'Akun ini tidak terdaftar sebagai dokter.');
        }

        $statusFilter = $request->query('status');

        $query = Appointment::where('doctor_id', $doctorDetail->id)
            ->with(['patient.user', 'schedule', 'doctor.poli']);

        if (in_array($statusFilter, ['pending', 'approved', 'rejected', 'done'])) {
            $query->where('status', $statusFilter);
        }

        $appointments = $query
            ->orderByRaw("
                CASE 
                    WHEN status = 'pending'  THEN 0 
                    WHEN status = 'approved' THEN 1
                    WHEN status = 'rejected' THEN 2
                    ELSE 3 
                END
            ")
            ->orderBy('booking_date', 'desc')
            ->paginate(10)
            ->withQueryString();

        $counts = [
            'all'      => Appointment::where('doctor_id', $doctorDetail->id)->count(),
            'pending'  => Appointment::where('doctor_id', $doctorDetail->id)->where('status', 'pending')->count(),
            'approved' => Appointment::where('doctor_id', $doctorDetail->id)->where('status', 'approved')->count(),
            'rejected' => Appointment::where('doctor_id', $doctorDetail->id)->where('status', 'rejected')->count(),
            'done'     => Appointment::where('doctor_id', $doctorDetail->id)->where('status', 'done')->count(),
        ];

        return view('doctor.appointments.index', compact('appointments', 'statusFilter', 'counts'));
    }

    public function show(Appointment $appointment)
    {
        $doctorDetail = Auth::user()->doctorDetail;

        if (! $doctorDetail || $appointment->doctor_id !== $doctorDetail->id) {
            abort(403, 'Akses ditolak');
        }

        $appointment->load(['patient.user', 'schedule', 'doctor.poli']);

        return view('doctor.appointments.show', compact('appointment'));
    }

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

        if ($request->status === 'rejected' && empty($request->rejected_reason)) {
            return back()->withErrors([
                'rejected_reason' => 'Alasan penolakan wajib diisi jika status ditolak.',
            ])->withInput();
        }

        $appointment->update([
            'status'          => $request->status,
            'rejected_reason' => $request->status === 'rejected'
                ? $request->rejected_reason
                : $appointment->rejected_reason,
        ]);

        return redirect()
            ->route('doctor.appointments.index')
            ->with('success', 'Status janji temu berhasil diperbarui.');
    }
}
