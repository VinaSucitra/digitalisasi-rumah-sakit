<?php

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
        $patient = Auth::user()->patient;

        if (!$patient) {
            abort(403, 'Akun ini tidak terdaftar sebagai pasien.');
        }

        $medicalRecords = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor.user', 'schedule'])  
            ->orderBy('visit_date', 'desc') 
            ->paginate(10);  

        return view('patient.medical_records.index', compact('medicalRecords'));
    }

    /**
     * Menampilkan detail rekam medis
     */
    public function show($id)
    {
        $medicalRecord = MedicalRecord::with(['doctor.user', 'schedule', 'prescriptions.items.medicine'])
            ->findOrFail($id);  

        return view('patient.medical_records.show', compact('medicalRecord'));
    }
}
