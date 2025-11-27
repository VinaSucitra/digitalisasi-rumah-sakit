<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use App\Models\Prescription;
use App\Models\PrescriptionItem;

class MedicalRecordController extends Controller
{
    /**
     * HALAMAN UTAMA DOKTER:
     * Menampilkan antrean konsultasi (janji temu yang sudah APPROVED untuk hari ini).
     */
    public function index()
    {
        $doctor = Auth::user()->doctorDetail;

        if (! $doctor) {
            abort(403, 'Akun ini bukan dokter.');
        }

        $today = now()->toDateString();

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->whereDate('booking_date', $today)
            ->with(['patient.user', 'schedule', 'doctor.poli'])
            ->orderBy('booking_date', 'asc')
            ->get();

        return view('doctor.medical_records.index', compact('appointments', 'today'));
    }

    /**
     * Form membuat rekam medis untuk sebuah appointment yang sudah approved.
     *
     * Route resource bawaan: /doctor/medical_records/create?appointment_id=XX
     */
    public function create(Request $request)
    {
        $doctor = Auth::user()->doctorDetail;

        if (! $doctor) {
            abort(403, 'Akun ini bukan dokter.');
        }

        $appointmentId = $request->query('appointment_id');

        $appointment = Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'approved')
            ->with(['patient.user', 'schedule'])
            ->firstOrFail();

        $medicines = Medicine::orderBy('name')->get();

        return view('doctor.medical_records.create', compact('appointment', 'medicines'));
    }

    /**
     * Simpan rekam medis (Diagnosis, Tindakan, Catatan, Resep).
     */
    public function store(Request $request)
    {
        $doctor = Auth::user()->doctorDetail;

        if (! $doctor) {
            abort(403, 'Akun ini bukan dokter.');
        }

        $request->validate([
            'appointment_id'      => 'required|exists:appointments,id',
            'diagnosis'           => 'required|string',
            'treatment'           => 'nullable|string',
            'notes'               => 'nullable|string',
            'visit_date'          => 'required|date',

            // Resep (opsional)
            'medicines'           => 'array',
            'medicines.*.id'      => 'nullable|exists:medicines,id',
            'medicines.*.quantity'=> 'nullable|integer|min:1',
            'medicines.*.dosage'  => 'nullable|string|max:255',
        ]);

        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['approved', 'pending']) // jaga-jaga
            ->with('patient')
            ->firstOrFail();

        DB::transaction(function () use ($request, $appointment, $doctor) {

            // 1️⃣ Buat rekam medis
            $medicalRecord = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'patient_id'     => $appointment->patient_id,
                'doctor_id'      => $doctor->id,
                'diagnosis'      => $request->diagnosis,
                'treatment'      => $request->treatment,
                'notes'          => $request->notes,
                'visit_date'     => $request->visit_date,
            ]);

            // 2️⃣ Buat resep (jika ada obat yang diisi)
            $hasMedicines = $request->filled('medicines');

            if ($hasMedicines) {
                $prescription = Prescription::create([
                    'medical_record_id' => $medicalRecord->id,
                    'status'            => 'pending', // atau 'ready' sesuai alurmu
                ]);

                foreach ($request->medicines as $item) {
                    if (!empty($item['id']) && !empty($item['quantity'])) {
                        PrescriptionItem::create([
                            'prescription_id' => $prescription->id,
                            'medicine_id'     => $item['id'],
                            'quantity'        => $item['quantity'],
                            'dosage'          => $item['dosage'] ?? null,
                        ]);
                    }
                }
            }

            // 3️⃣ Ubah status appointment jadi DONE
            $appointment->update([
                'status' => 'done',
            ]);
        });

        return redirect()
            ->route('doctor.medical_records.index')
            ->with('success', 'Rekam medis berhasil disimpan dan janji temu ditandai selesai.');
    }

    /**
     * Menampilkan detail rekam medis (untuk dokter).
     */
    public function show(MedicalRecord $medical_record)
    {
        $doctor = Auth::user()->doctorDetail;

        if (! $doctor || $medical_record->doctor_id !== $doctor->id) {
            abort(403, 'Akses ditolak');
        }

        $medical_record->load([
            'patient.user',
            'doctor.user',
            'appointment',
            'prescriptions.items.medicine',
        ]);

        return view('doctor.medical_records.show', compact('medical_record'));
    }

    /**
     * Form edit rekam medis (opsional).
     */
    public function edit(MedicalRecord $medical_record)
    {
        $doctor = Auth::user()->doctorDetail;

        if (! $doctor || $medical_record->doctor_id !== $doctor->id) {
            abort(403, 'Akses ditolak');
        }

        $medical_record->load(['appointment', 'patient.user']);
        $medicines = Medicine::orderBy('name')->get();

        return view('doctor.medical_records.edit', compact('medical_record', 'medicines'));
    }

    /**
     * Update rekam medis (tanpa mengubah appointment).
     */
    public function update(Request $request, MedicalRecord $medical_record)
    {
        $doctor = Auth::user()->doctorDetail;

        if (! $doctor || $medical_record->doctor_id !== $doctor->id) {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
            'diagnosis'  => 'required|string',
            'treatment'  => 'nullable|string',
            'notes'      => 'nullable|string',
            'visit_date' => 'required|date',
        ]);

        $medical_record->update([
            'diagnosis'  => $request->diagnosis,
            'treatment'  => $request->treatment,
            'notes'      => $request->notes,
            'visit_date' => $request->visit_date,
        ]);

        return redirect()
            ->route('doctor.medical_records.show', $medical_record->id)
            ->with('success', 'Rekam medis berhasil diperbarui.');
    }
}
