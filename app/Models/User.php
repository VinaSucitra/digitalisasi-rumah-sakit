<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use App\Models\DoctorDetail;
use App\Models\Patient;
use App\Models\Schedule;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    /**
     * Helper role.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor'; 
    }

    public function isPatient(): bool
    {
        return $this->role === 'patien';
    }

    /**
     * Relasi One-to-One dengan DoctorDetail.
     * (Satu user dengan role 'dokter' punya satu detail dokter.)
     */
    public function doctorDetail(): HasOne
    {
        return $this->hasOne(DoctorDetail::class, 'user_id');
    }

    /**
     * Relasi One-to-One dengan Patient.
     * (Satu user dengan role 'pasien' punya satu profil patient.)
     */
    public function patient(): HasOne
    {
        return $this->hasOne(Patient::class, 'user_id');
    }

    
    public function schedules(): HasManyThrough
    {
        return $this->hasManyThrough(
            Schedule::class,    
            DoctorDetail::class, 

            'user_id',  
            'doctor_id',

            'id',     
            'id'        
        );
    }
}
