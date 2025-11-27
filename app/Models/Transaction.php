<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'total_amount',
        'paid_amount',
        'payment_method',
        'status',
        'reference_number',
        'notes',
    ];

    /**
     * Relasi ke Appointment (Setiap Transaction dimiliki oleh satu Appointment).
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
