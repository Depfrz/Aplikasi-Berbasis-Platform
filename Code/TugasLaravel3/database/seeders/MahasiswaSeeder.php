<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Mahasiswa;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Mahasiswa::create([
            'nama' => 'Daffa',
            'nim' => '12345678',
            'jurusan' => 'Informatika'
        ]);
        Mahasiswa::create([
            'nama' => 'Budi',
            'nim' => '87654321',
            'jurusan' => 'Sistem Informasi'
        ]);
    }
}
