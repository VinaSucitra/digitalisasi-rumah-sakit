<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DoctorDetail;
use App\Models\Patient;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ---------------------------------------------------------------- //
        // 1. ADMIN USER (BARU DITAMBAHKAN)
        // ---------------------------------------------------------------- //
        User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@hospital.com',
            'password' => Hash::make('admin123'), 
            'role' => 'admin',
        ]);

        // ---------------------------------------------------------------- //
        // 2. DOCTOR
        // ---------------------------------------------------------------- //
        $doctorUser = User::create([
            'name' => 'Dr. John',
            'email' => 'doctor@hospital.com',
            'password' => Hash::make('doctor123'),
            'role' => 'doctor',
        ]);
        
        DoctorDetail::create([
            'user_id' => $doctorUser->id,
            'poli_id' => 1, 
            'sip' => 'SIP-001',
            'bio' => 'Dokter spesialis umum.',
        ]);


        // ---------------------------------------------------------------- //
        // 3. PATIENT
        // ---------------------------------------------------------------- //
        $patientUser = User::create([
            'name' => 'Patient User',
            'email' => 'patient@hospital.com',
            'password' => Hash::make('patient123'),
            'role' => 'patient',
        ]);

        Patient::create([
            'user_id' => $patientUser->id,
            'address' => 'Jl. Kesehatan No. 10',
            'phone' => '08123456789',
            'no_rm' => 'RM-0001',
        ]);
    }
}