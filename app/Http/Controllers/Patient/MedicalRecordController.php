<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\MedicalRecord;

class MedicalRecordController extends Controller
{
    /**
     * List semua rekam medis untuk pasien yang login.
     */
    public function index()
    {
        $patient = Auth::user()->patient;

        $records = MedicalRecord::where('patient_id', $patient->id)
            ->with(['doctor.user', 'appointment', 'prescriptions.items.medicine'])
            ->latest('visit_date')
            ->get();

        return view('patient.medical_records.index', compact('records'));
    }

    /**
     * Detail rekam medis.
     */
    public function show(MedicalRecord $medical_record)
    {
        $patient = Auth::user()->patient;

        if ($medical_record->patient_id !== $patient->id) {
            abort(403, 'Akses ditolak');
        }

        $medical_record->load([
            'doctor.user',
            'appointment',
            'prescriptions.items.medicine'
        ]);

        return view('patient.medical_records.show', compact('medical_record'));
    }
}
