<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Poli extends Model
{
    use HasFactory;

    protected $table = 'polis';

    // Tambahkan 'icon' di sini
    protected $fillable = ['name', 'description', 'icon'];

    // Relasi 1:N ke DoctorDetail
    public function doctors()
    {
        return $this->hasMany(DoctorDetail::class);
    }

    // Relasi 1:N ke Appointment
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
