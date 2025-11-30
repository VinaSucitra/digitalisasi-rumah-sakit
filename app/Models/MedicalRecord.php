<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    protected $fillable = [
        'appointment_id',
        'patient_id',
        'doctor_id',
        'diagnosis',
        'treatment',
        'notes',
        'visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    // Relasi ke appointment
    public function appointment()
    {
        return $this->belongsTo(Appointment::class, 'appointment_id');
    }

    // Relasi ke tabel patients (bukan users)
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    // Relasi ke tabel doctor_details (bukan users)
    public function doctor()
    {
        return $this->belongsTo(DoctorDetail::class, 'doctor_id');
    }

    // Relasi ke tabel prescriptions
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}
