<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'price',
        'type',       // Obat / Tindakan
        'drug_type',  // keras / biasa
        'stock',      // stok
        'image',      // path gambar
    ];
}
