<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    // Menggunakan HasFactory agar bisa membuat data dummy/seeder
    use HasFactory;

    // Properti $fillable mendefinisikan kolom mana yang boleh diisi
    // secara massal (mass assignment) menggunakan metode create() atau update().
    protected $fillable = [
        'name',
        'description',
        'price',
        'type',
    ];

    // Jika Anda ingin menonaktifkan mass assignment protection, gunakan:
    // protected $guarded = []; 
    // Namun, disarankan menggunakan $fillable atau $guarded untuk keamanan.
}