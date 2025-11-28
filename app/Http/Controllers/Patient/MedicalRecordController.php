<?php

// app/Http/Controllers/Patient/MedicalRecordController.php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Auth;

class MedicalRecordController extends Controller
{
    /**
     * Menampilkan riwayat pemeriksaan pasien
     */
    public function index()
    {
        // Ambil data pasien yang sedang login
        $patient = Auth::user()->patient;

        // Jika pasien tidak ada, tampilkan error
        if (!$patient) {
            abort(403, 'Akun ini tidak terdaftar sebagai pasien.');
        }

        // Ambil semua rekam medis pasien
        $medicalRecords = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor.user', 'schedule'])  // Menambahkan relasi untuk dokter dan jadwal
            ->orderBy('visit_date', 'desc') // Urutkan berdasarkan tanggal kunjungan terbaru
            ->paginate(10);  // Gunakan pagination jika data terlalu banyak

        return view('patient.medical_records.index', compact('medicalRecords'));
    }

    /**
     * Menampilkan detail rekam medis
     */
    public function show($id)
    {
        $medicalRecord = MedicalRecord::with(['doctor.user', 'schedule', 'prescriptions.items.medicine'])
            ->findOrFail($id);  // Ambil rekam medis berdasarkan ID

        return view('patient.medical_records.show', compact('medicalRecord'));
    }
}
