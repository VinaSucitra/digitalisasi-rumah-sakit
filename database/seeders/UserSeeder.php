<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DoctorDetail; // <-- Tetap diperlukan untuk Dokter
use App\Models\Patient; // <-- GANTI DARI PatientDetail menjadi Model Patient
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; 

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ... (Buat Admin)

        // ---------------------------------------------------------------- //
        // DOCTOR
        // ---------------------------------------------------------------- //
        $doctorUser = User::create([
            'name' => 'Dr. John',
            'email' => 'doctor@hospital.com',
            'password' => Hash::make('doctor123'),
            'role' => 'doctor',
        ]);
        
        // 🔥 Tambahkan detail dokter (Mengatasi Error 403 Dokter)
        DoctorDetail::create([
            'user_id' => $doctorUser->id,
            'poli_id' => 1, 
            'sip' => 'SIP-001',
            'bio' => 'Dokter spesialis umum.',
        ]);


        // ---------------------------------------------------------------- //
        // PATIENT: Simpan User, lalu buat entri di tabel 'patients'
        // ---------------------------------------------------------------- //
        $patientUser = User::create([
            'name' => 'Patient User',
            'email' => 'patient@hospital.com',
            'password' => Hash::make('patient123'),
            'role' => 'patient',
        ]);

        // 🔥 Tambahkan entri di tabel 'patients' dan sediakan 'no_rm'
        Patient::create([
            'user_id' => $patientUser->id,
            'address' => 'Jl. Kesehatan No. 10',
            'phone' => '08123456789',
            'no_rm' => 'RM-0001', // Wajib: Mengatasi error 'no_rm'
        ]);
    }
}