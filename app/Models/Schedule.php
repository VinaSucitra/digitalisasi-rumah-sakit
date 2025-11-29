<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
    ];

    public function doctor()
    {
        return $this->belongsTo(DoctorDetail::class, 'doctor_id');
    }

    public function poli()
    {
        return $this->doctor->poli();
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    // Pastikan end_time lebih besar dari start_time
    public static function boot()
    {
        parent::boot();

        static::saving(function ($schedule) {
            // Periksa apakah end_time lebih besar dari start_time
            if ($schedule->start_time >= $schedule->end_time) {
                throw new \Exception('Jam selesai harus lebih besar dari jam mulai.');
            }
        });
    }
}
