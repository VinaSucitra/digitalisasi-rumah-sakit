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
use Carbon\Carbon;

class MedicalRecordController extends Controller
{
    /**
     * HALAMAN UTAMA DOKTER:
     * - Menampilkan antrean konsultasi: janji temu yang sudah "approved" untuk HARI INI.
     * - Menampilkan beberapa rekam medis terakhir sebagai riwayat.
     */
    public function index()
    {
        $doctor = Auth::user()->doctorDetail;

        if (! $doctor) {
            abort(403, 'Akses ditolak: Akun ini bukan dokter.');
        }

        $today = Carbon::now()->toDateString();

        // Janji temu approved untuk hari ini
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'approved')                   // status sesuai enum di migration
            ->whereDate('booking_date', $today)
            ->with(['patient.user', 'schedule', 'doctor.poli'])
            ->orderBy('booking_date', 'asc')
            ->get();

        // Riwayat rekam medis terakhir dokter ini
        $records = MedicalRecord::where('doctor_id', $doctor->id)
            ->with('patient.user')
            ->orderByDesc('visit_date')
            ->orderByDesc('id')
            ->take(5)
            ->get();

        return view('doctor.medical_records.index', compact('appointments', 'today', 'records'));
    }

    /**
     * Form membuat rekam medis untuk satu appointment (harus sudah approved).
     */
    public function create(Request $request)
    {
        $doctor = Auth::user()->doctorDetail;
        if (! $doctor) {
            abort(403, 'Akses ditolak.');
        }

        $appointmentId = $request->query('appointment_id');

        $appointment = Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'approved')        // hanya untuk janji temu approved
            ->with(['patient.user', 'schedule'])
            ->firstOrFail();

        // Cegah duplikasi rekam medis
        if (MedicalRecord::where('appointment_id', $appointmentId)->exists()) {
            return redirect()
                ->route('doctor.medical_records.index')
                ->with('error', 'Rekam medis untuk janji temu ini sudah pernah dibuat.');
        }

        $medicines = Medicine::orderBy('name')->get();

        return view('doctor.medical_records.create', compact('appointment', 'medicines'));
    }

    /**
     * Simpan rekam medis (diagnosis, tindakan, catatan, resep).
     * Setelah tersimpan, status Janji Temu -> "done".
     */
    public function store(Request $request)
    {
        $doctor = Auth::user()->doctorDetail;
        if (! $doctor) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'appointment_id'        => 'required|exists:appointments,id',
            'diagnosis'             => 'required|string|max:1000',
            'treatment'             => 'nullable|string|max:1000',
            'notes'                 => 'nullable|string|max:1000',
            'visit_date'            => 'required|date',

            'medicines'             => 'array',
            'medicines.*.id'        => 'nullable|exists:medicines,id',
            'medicines.*.quantity'  => 'nullable|integer|min:1',
            'medicines.*.dosage'    => 'nullable|string|max:255',
        ]);

        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'approved')          // sesuai soal: create RM hanya untuk approved
            ->firstOrFail();

        if (MedicalRecord::where('appointment_id', $appointment->id)->exists()) {
            return redirect()
                ->route('doctor.medical_records.index')
                ->with('error', 'Rekam medis untuk janji temu ini sudah ada.');
        }

        $medicinesInput = $request->input('medicines', []);

        DB::transaction(function () use ($request, $appointment, $doctor, $medicinesInput) {
            // 1. Buat rekam medis
            $medicalRecord = MedicalRecord::create([
                'appointment_id' => $appointment->id,
                'patient_id'     => $appointment->patient_id,
                'doctor_id'      => $doctor->id,
                'diagnosis'      => $request->diagnosis,
                'treatment'      => $request->treatment,
                'notes'          => $request->notes,
                'visit_date'     => $request->visit_date,
            ]);

            // 2. Buat resep (jika ada obat valid)
            $validMedicines = collect($medicinesInput)->filter(function ($item) {
                return !empty($item['id']) && !empty($item['quantity']);
            });

            if ($validMedicines->isNotEmpty()) {
                $prescription = Prescription::create([
                    'medical_record_id' => $medicalRecord->id,
                    'status'            => 'pending',
                ]);

                foreach ($validMedicines as $item) {
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_id'     => $item['id'],
                        'quantity'        => $item['quantity'],
                        'dosage'          => $item['dosage'] ?? null,
                    ]);
                }
            }

            // 3. Ubah status appointment menjadi selesai (done)
            $appointment->update(['status' => 'done']);
        });

        return redirect()
            ->route('doctor.medical_records.index')
            ->with('success', 'Rekam medis berhasil disimpan.');
    }

    /**
     * Update rekam medis & resep.
     */
    public function update(Request $request, MedicalRecord $medical_record)
    {
        $doctor = Auth::user()->doctorDetail;

        if (! $doctor || $medical_record->doctor_id !== $doctor->id) {
            abort(403, 'Akses ditolak');
        }

        $request->validate([
            'diagnosis'             => 'required|string|max:1000',
            'treatment'             => 'nullable|string|max:1000',
            'notes'                 => 'nullable|string|max:1000',
            'visit_date'            => 'required|date',

            'medicines'             => 'array',
            'medicines.*.id'        => 'nullable|exists:medicines,id',
            'medicines.*.quantity'  => 'nullable|integer|min:1',
            'medicines.*.dosage'    => 'nullable|string|max:255',
        ]);

        $medicinesInput = $request->input('medicines', []);

        DB::transaction(function () use ($request, $medical_record, $medicinesInput) {
            // A. Update data utama rekam medis
            $medical_record->update([
                'diagnosis'  => $request->diagnosis,
                'treatment'  => $request->treatment,
                'notes'      => $request->notes,
                'visit_date' => $request->visit_date,
            ]);

            // B. Update resep (delete & recreate)
            $prescription = $medical_record->prescriptions->first();

            $validMedicines = collect($medicinesInput)->filter(function ($item) {
                return !empty($item['id']) && !empty($item['quantity']);
            });

            if ($validMedicines->isNotEmpty()) {

                if (! $prescription) {
                    $prescription = Prescription::create([
                        'medical_record_id' => $medical_record->id,
                        'status'            => 'pending',
                    ]);
                } else {
                    $prescription->items()->delete();
                }

                foreach ($validMedicines as $item) {
                    PrescriptionItem::create([
                        'prescription_id' => $prescription->id,
                        'medicine_id'     => $item['id'],
                        'quantity'        => $item['quantity'],
                        'dosage'          => $item['dosage'] ?? null,
                    ]);
                }

            } elseif ($prescription) {
                // Jika form resep dikosongkan: hapus seluruh resep
                $prescription->items()->delete();
                $prescription->delete();
            }
        });

        return redirect()
            ->route('doctor.medical_records.show', $medical_record->id)
            ->with('success', 'Rekam medis dan resep berhasil diperbarui.');
    }

    /**
     * Detail satu rekam medis.
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
     * Form edit rekam medis.
     */
    public function edit(MedicalRecord $medical_record)
    {
        $doctor = Auth::user()->doctorDetail;
        if (! $doctor || $medical_record->doctor_id !== $doctor->id) {
            abort(403, 'Akses ditolak');
        }

        $medical_record->load([
            'appointment',
            'patient.user',
            'prescriptions.items',
        ]);

        $medicines = Medicine::orderBy('name')->get();

        return view('doctor.medical_records.edit', compact('medical_record', 'medicines'));
    }

    /**
     * OPTIONAL (untuk memenuhi "Delete Medical Record" di soal):
     * Jangan lupa aktifkan route destroy jika ingin pakai.
     */
    public function destroy(MedicalRecord $medical_record)
    {
        $doctor = Auth::user()->doctorDetail;
        if (! $doctor || $medical_record->doctor_id !== $doctor->id) {
            abort(403, 'Akses ditolak');
        }

        DB::transaction(function () use ($medical_record) {
            // hapus resep beserta item
            foreach ($medical_record->prescriptions as $prescription) {
                $prescription->items()->delete();
                $prescription->delete();
            }

            $medical_record->delete();
        });

        return redirect()
            ->route('doctor.medical_records.index')
            ->with('success', 'Rekam medis berhasil dihapus.');
    }
}
