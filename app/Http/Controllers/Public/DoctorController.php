<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\DoctorDetail;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    /**
     * Display a listing of doctors.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Fetch all doctors from the database
        $doctors = DoctorDetail::all();

        // Return the view with the list of doctors
        return view('public.doctors', compact('doctors'));
    }

    /**
     * Show the profile of a specific doctor.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Find a specific doctor by their ID
        $doctor = Doctor::findOrFail($id);

        // Return the doctor profile view
        return view('public.doctors.show', compact('doctor'));
    }
}
