<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MahasiswaFactory extends Factory
{
    public function definition()
    {
        return [
            'nim' => $this->faker->unique()->numerify('##########'),
            'nama' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'prodi' => $this->faker->randomElement(['Informatika', 'Sistem Informasi', 'Teknik Elektro']),
            'kelas' => $this->faker->randomElement(['IF-01', 'SI-02', 'TE-03']),
        ];
    }
}
