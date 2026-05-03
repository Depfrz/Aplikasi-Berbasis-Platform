<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Matakuliah;

class MatakuliahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Matakuliah::create([
            'kode_mk' => 'MK001',
            'nama_mk' => 'Pemrograman Web',
            'sks' => 3,
            'semester' => 4
        ]);
        Matakuliah::create([
            'kode_mk' => 'MK002',
            'nama_mk' => 'Basis Data',
            'sks' => 3,
            'semester' => 3
        ]);
    }
}
