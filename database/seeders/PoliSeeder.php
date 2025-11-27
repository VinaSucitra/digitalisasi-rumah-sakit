<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PoliSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('polis')->insert([
            ['name' => 'Poli Umum'],
            ['name' => 'Poli Gigi'],
            ['name' => 'Poli Anak'],
            ['name' => 'Poli THT'],
            ['name' => 'Poli Kandungan'],
        ]);
    }
}
