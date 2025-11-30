<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $fillable = [
        'medical_record_id',
        'status', 
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function items()
    {
        return $this->hasMany(PrescriptionItem::class);
    }
}
