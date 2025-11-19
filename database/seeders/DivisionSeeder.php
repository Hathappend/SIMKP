<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisions = [
            ['name' => 'E-Government'],
            ['name' => 'Aplikasi Informatika'],
            ['name' => 'Informasi Komunikasi Publik'],
            ['name' => 'Persandian dan Keamanan Informasi'],
            ['name' => 'Statistik'],
            ['name' => 'Sekretariat'],
            ['name' => 'Unit Pelayanan Teknis Daerah (UPTD)'],
        ];

        DB::table('divisions')->insert($divisions);
    }
}
