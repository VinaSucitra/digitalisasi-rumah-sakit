<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',      
        'doctor_id',      
        'schedule_id',   
        'booking_date',   
        'complaint',      
        'status',
        'rejected_reason',
        'queue_number',
    ];

    protected $casts = [
        'booking_date' => 'date',
    ];

    /**
     * RELASI
     */

    // Pasien
    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    // Dokter
    public function doctor()
    {
        return $this->belongsTo(DoctorDetail::class, 'doctor_id');
    }

    // Jadwal dokter
    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'schedule_id');
    }

    // Medical record (1 janji temu -> 1 rekam medis)
    public function medicalRecord()
    {
        return $this->hasOne(MedicalRecord::class);
    }

   
}
