<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorDetail extends Model
{
    protected $fillable = [
        'user_id',
        'poli_id',
        'sip',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function poli()
    {
        return $this->belongsTo(Poli::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'doctor_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'doctor_id');
    }
}
