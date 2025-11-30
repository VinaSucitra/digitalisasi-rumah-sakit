<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\DoctorDetail; 
use Illuminate\Support\Facades\Auth;

class DoctorScheduleController extends Controller
{
    public function index()
    {
        
        $patient = Auth::user()->patient;

        
        if (!$patient) {
            abort(403, 'Akun ini tidak terdaftar sebagai pasien.');
        }

        
        $doctorDetails = DoctorDetail::with(['user', 'poli', 'schedules'])
            ->get(); 

        
        return view('patient.doctor_schedules.index', compact('doctorDetails'));
    }
}
