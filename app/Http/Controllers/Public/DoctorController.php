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
       
        $doctors = DoctorDetail::all();

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
        $doctor = Doctor::findOrFail($id);

        return view('public.doctors.show', compact('doctor'));
    }
}
