<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Matakuliah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Admin User
        User::factory()->create([
            'name' => 'Admin Akademik',
            'email' => 'admin@akademik.test',
            'username' => 'admin',
            'role' => 'admin',
            'password' => Hash::make('password'),
        ]);

        // Mahasiswa
        Mahasiswa::factory(50)->create();

        // Dosen
        $dosens = Dosen::factory(20)->create();

        // Matakuliah
        $matakuliahs = Matakuliah::factory(30)->create();

        // Assign some matakuliahs to dosens (many-to-many)
        foreach ($matakuliahs as $mk) {
            $mk->dosens()->attach(
                $dosens->random(rand(1, 3))->pluck('id')->toArray()
            );
        }
    }
}
