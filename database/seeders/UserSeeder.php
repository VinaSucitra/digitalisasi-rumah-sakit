<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ADMIN
        User::create([
            'name' => 'Admin',
            'email' => 'admin@hospital.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
        ]);

        // DOCTOR
        User::create([
            'name' => 'Dr. John',
            'email' => 'doctor@hospital.com',
            'password' => bcrypt('doctor123'),
            'role' => 'doctor',
        ]);

        // PATIENT
        User::create([
            'name' => 'Patient User',
            'email' => 'patient@hospital.com',
            'password' => bcrypt('patient123'),
            'role' => 'patient',
        ]);
    }
}
