<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $statusFilter = $request->query('status'); 

        $query = Appointment::with([
            'patient.user',
            'doctor.user',
            'doctor.poli',
            'schedule',
        ]);

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        } else {
            $query->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END");
        }

        $appointments = $query
            ->orderBy('booking_date', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('admin.appointments.index', compact('appointments'));
    }


    public function updateStatus(Request $request, Appointment $appointment)
    {
        $data = $request->validate([
            'status'        => 'required|in:approved,rejected',
            'reject_reason' => 'nullable|string|max:255',
        ]);

        $appointment->status = $data['status'];
        $appointment->reject_reason = $data['status'] === 'rejected'
            ? ($data['reject_reason'] ?? null)
            : null;

        $appointment->save();

        return back()->with('success', 'Status janji temu berhasil diperbarui.');
    }
}
