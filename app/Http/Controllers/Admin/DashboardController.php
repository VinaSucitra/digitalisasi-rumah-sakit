<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\DoctorDetail;
use App\Models\Patient;
use App\Models\User;
use App\Models\Medicine;
use App\Models\Schedule;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $pendingAppointments = Appointment::where('status', 'pending')->count();
        $currentDayName = strtolower(Carbon::now()->isoFormat('dddd')); 

        $doctorsToday = Schedule::where('day_of_week', $currentDayName)
            ->with('doctor.user', 'doctor.poli')
            ->get();

        $totalAdmin   = User::where('role', 'admin')->count();
        $totalDoctor  = DoctorDetail::count(); 
        $totalPatient = Patient::count();     
        $totalMedicines = Medicine::count();

        $recentAppointments = Appointment::with(['patient.user', 'doctor.user'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();


        return view('admin.dashboard', compact(
            'pendingAppointments',
            'doctorsToday',
            'totalAdmin',
            'totalDoctor',
            'totalPatient',
            'totalMedicines',
            'recentAppointments' 
        ));
    }
}