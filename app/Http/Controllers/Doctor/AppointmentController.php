<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Menampilkan daftar janji temu milik dokter yang sedang login.
     * Mendukung filter status via query string: ?status=pending|approved|rejected|done
     */
    public function index(Request $request)
    {
        // Ambil detail dokter dari user yang login (relasi user->doctorDetail)
        $doctorDetail = Auth::user()->doctorDetail;

        if (! $doctorDetail) {
            abort(403, 'Akun ini tidak terdaftar sebagai dokter.');
        }

        // status yang sedang difilter (boleh null = semua)
        $statusFilter = $request->query('status');

        // Query dasar: hanya janji temu untuk dokter ini
        $query = Appointment::where('doctor_id', $doctorDetail->id)
            ->with(['patient.user', 'schedule', 'doctor.poli']);

        // Jika ada filter status yang valid, terapkan
        if (in_array($statusFilter, ['pending', 'approved', 'rejected', 'done'])) {
            $query->where('status', $statusFilter);
        }

        // Urutkan: pending -> approved -> rejected -> done, lalu berdasarkan tanggal booking terbaru
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
            ->withQueryString(); // supaya parameter ?status tetap ada saat pindah halaman

        // Hitung jumlah per status untuk badge di tab
        $counts = [
            'all'      => Appointment::where('doctor_id', $doctorDetail->id)->count(),
            'pending'  => Appointment::where('doctor_id', $doctorDetail->id)->where('status', 'pending')->count(),
            'approved' => Appointment::where('doctor_id', $doctorDetail->id)->where('status', 'approved')->count(),
            'rejected' => Appointment::where('doctor_id', $doctorDetail->id)->where('status', 'rejected')->count(),
            'done'     => Appointment::where('doctor_id', $doctorDetail->id)->where('status', 'done')->count(),
        ];

        return view('doctor.appointments.index', compact('appointments', 'statusFilter', 'counts'));
    }

    /**
     * Menampilkan detail appointment (hanya jika milik dokter ini).
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
     * Update status appointment oleh dokter:
     * - approved : menyetujui janji temu
     * - rejected : menolak (wajib isi alasan)
     * - done     : menandai janji temu sudah selesai
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
            ])->withInput();
        }

        // (Opsional) Boleh batasi: hanya bisa ubah jika masih pending / approved
        // if (! in_array($appointment->status, ['pending', 'approved'])) {
        //     return back()->with('error', 'Status janji temu ini sudah tidak bisa diubah.');
        // }

        $appointment->update([
            'status'          => $request->status,
            'rejected_reason' => $request->status === 'rejected'
                ? $request->rejected_reason
                : $appointment->rejected_reason, // jangan dihapus kalau bukan reject
        ]);

        return redirect()
            ->route('doctor.appointments.index')
            ->with('success', 'Status janji temu berhasil diperbarui.');
    }
}
