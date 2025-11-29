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
     * Menampilkan antrean konsultasi (janji temu yang sudah APPROVED untuk hari ini) dan Riwayat RM.
     */
    public function index()
    {
        $doctor = Auth::user()->doctorDetail; // Ambil detail dokter yang sedang login

        if (! $doctor) {
            abort(403, 'Akses ditolak: Akun ini bukan dokter.');
        }

        $today = Carbon::now()->toDateString();
        
        // Perbaikan: Menggunakan 'booking_date' atau 'appointment_date' sesuai skema DB Anda.
        // Asumsi menggunakan 'booking_date' karena 'appointment_date' memicu error sebelumnya.
        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('status', 'Approved')
            ->whereDate('booking_date', $today) 
            ->with(['patient.user', 'schedule', 'doctor.poli'])
            ->orderBy('booking_date', 'asc')
            ->get();
            
        // Riwayat Rekam Medis terakhir (untuk sidebar/section riwayat)
        $records = MedicalRecord::where('doctor_id', $doctor->id)
                                ->with('patient.user')
                                ->latest()
                                ->take(5)
                                ->get();

        return view('doctor.medical_records.index', compact('appointments', 'today', 'records'));
    }

    /**
     * Form membuat rekam medis.
     */
    public function create(Request $request)
    {
        $doctor = Auth::user()->doctorDetail;
        if (! $doctor) abort(403, 'Akses ditolak.');
        
        $appointmentId = $request->query('appointment_id');
        
        $appointment = Appointment::where('id', $appointmentId)
            ->where('doctor_id', $doctor->id)
            ->where('status', 'Approved')
            ->with(['patient.user', 'schedule'])
            ->firstOrFail();

        // Pencegahan duplikasi
        if (MedicalRecord::where('appointment_id', $appointmentId)->exists()) {
            return redirect()->route('doctor.medical_records.index')->with('error', 'Rekam medis untuk janji temu ini sudah pernah dibuat.');
        }

        $medicines = Medicine::orderBy('name')->get();

        return view('doctor.medical_records.create', compact('appointment', 'medicines'));
    }

    /**
     * Simpan rekam medis (Diagnosis, Tindakan, Catatan, Resep).
     */
    public function store(Request $request)
    {
        $doctor = Auth::user()->doctorDetail;
        if (! $doctor) abort(403, 'Akses ditolak.');
        
        $request->validate([
            'appointment_id' => 'required|exists:appointments,id',
            'diagnosis'      => 'required|string|max:1000',
            'treatment'      => 'nullable|string|max:1000',
            'notes'          => 'nullable|string|max:1000',
            'visit_date'     => 'required|date',
            'medicines'      => 'array',
            'medicines.*.id' => 'nullable|exists:medicines,id',
            'medicines.*.quantity' => 'nullable|integer|min:1',
            'medicines.*.dosage'   => 'nullable|string|max:255',
        ]);

        $appointment = Appointment::where('id', $request->appointment_id)
            ->where('doctor_id', $doctor->id)
            ->whereIn('status', ['Approved', 'Pending'])
            ->firstOrFail();

        if (MedicalRecord::where('appointment_id', $appointment->id)->exists()) {
             return redirect()->route('doctor.medical_records.index')->with('error', 'Rekam medis untuk janji temu ini sudah ada.');
        }

        DB::transaction(function () use ($request, $appointment, $doctor) {
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

            // 2. Buat resep (jika ada obat)
            $hasMedicines = collect($request->medicines)->filter(fn($item) => !empty($item['id']) && !empty($item['quantity']))->isNotEmpty();

            if ($hasMedicines) {
                $prescription = Prescription::create([
                    'medical_record_id' => $medicalRecord->id,
                    'status'            => 'pending',
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

            // 3. Ubah status appointment jadi Selesai
            // 🔥 PERBAIKAN DI SINI: Mengubah 'Selesai' menjadi 'Done'
            $appointment->update(['status' => 'Done']);
        });

        return redirect()->route('doctor.medical_records.index')->with('success', 'Rekam medis berhasil disimpan.');
    }

    /**
     * Update rekam medis (termasuk Resep).
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

        DB::transaction(function () use ($request, $medical_record) {

            // A. Update data utama Rekam Medis
            $medical_record->update([
                'diagnosis'  => $request->diagnosis,
                'treatment'  => $request->treatment,
                'notes'      => $request->notes,
                'visit_date' => $request->visit_date,
            ]);

            // B. LOGIKA UPDATE RESEP (DELETE & RECREATE)
            $prescription = $medical_record->prescriptions->first();
            $validMedicines = collect($request->medicines)->filter(fn($item) => !empty($item['id']) && !empty($item['quantity']));
            $hasValidMedicines = $validMedicines->isNotEmpty();
            
            if ($hasValidMedicines) {
                
                if (!$prescription) {
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
                // Hapus resep jika form dikosongkan
                $prescription->items()->delete();
                $prescription->delete();
            }
        });

        return redirect()->route('doctor.medical_records.show', $medical_record->id)->with('success', 'Rekam medis dan resep berhasil diperbarui.');
    }

    // ... (Fungsi show dan edit lainnya, tetap sama)
    public function show(MedicalRecord $medical_record)
    {
        $doctor = Auth::user()->doctorDetail;
        if (! $doctor || $medical_record->doctor_id !== $doctor->id) abort(403, 'Akses ditolak');
        
        $medical_record->load(['patient.user', 'doctor.user', 'appointment', 'prescriptions.items.medicine']);
        return view('doctor.medical_records.show', compact('medical_record'));
    }
    
    public function edit(MedicalRecord $medical_record)
    {
        $doctor = Auth::user()->doctorDetail;
        if (! $doctor || $medical_record->doctor_id !== $doctor->id) abort(403, 'Akses ditolak');
        
        $medical_record->load(['appointment', 'patient.user', 'prescriptions.items']);
        $medicines = Medicine::orderBy('name')->get();
        
        return view('doctor.medical_records.edit', compact('medical_record', 'medicines'));
    }
}