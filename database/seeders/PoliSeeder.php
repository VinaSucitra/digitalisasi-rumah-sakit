<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Poli;

class PoliSeeder extends Seeder
{
    /**
     * Jalankan database seed.
     */
    public function run(): void
    {
        $polis = [
            [
                'name' => 'Poli Umum',
                'description' => 'Layanan pemeriksaan umum dan keluhan dasar.',
                'icon' => 'stethoscope',
            ],
            [
                'name' => 'Poli Gigi',
                'description' => 'Layanan perawatan dan pemeriksaan kesehatan gigi.',
                'icon' => 'tooth',
            ],
            [
                'name' => 'Poli Anak',
                'description' => 'Layanan kesehatan khusus untuk anak-anak.',
                'icon' => 'baby',
            ],
            [
                'name' => 'Poli THT',
                'description' => 'Pemeriksaan telinga, hidung, dan tenggorokan.',
                'icon' => 'ear-listen',
            ],
            [
                'name' => 'Poli Jantung',
                'description' => 'Pemeriksaan dan konsultasi masalah kardiovaskular.',
                'icon' => 'heart-pulse',
            ],
        ];

        foreach ($polis as $poli) {
            Poli::firstOrCreate(
                ['name' => $poli['name']], // agar tidak double kalau seeder dijalankan ulang
                $poli
            );
        }
    }
}
