<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Medicine;

class MedicineSeeder extends Seeder
{
    public function run(): void
    {
        $medicines = [
            [
                'name' => 'Paracetamol',
                'description' => 'Obat penurun panas dan pereda nyeri',
                'price' => 5000,
                'type' => 'Obat',
            ],
            [
                'name' => 'Amoxicillin',
                'description' => 'Antibiotik untuk infeksi bakteri',
                'price' => 8000,
                'type' => 'Obat',
            ],
            [
                'name' => 'Ibuprofen',
                'description' => 'Pereda nyeri dan anti inflamasi',
                'price' => 7000,
                'type' => 'Obat',
            ],
            [
                'name' => 'Pemeriksaan Dokter Umum',
                'description' => 'Biaya jasa konsultasi dokter umum',
                'price' => 25000,
                'type' => 'Tindakan',
            ],
            [
                'name' => 'Pembersihan Karang Gigi',
                'description' => 'Tindakan scaling gigi',
                'price' => 150000,
                'type' => 'Tindakan',
            ],
        ];

        foreach ($medicines as $med) {
            Medicine::create($med);
        }
    }
}
