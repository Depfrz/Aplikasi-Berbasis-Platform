<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MatakuliahFactory extends Factory
{
    public function definition()
    {
        return [
            'kode_mk' => strtoupper($this->faker->unique()->bothify('??###')),
            'nama' => $this->faker->words(3, true),
            'sks' => $this->faker->numberBetween(1, 6),
        ];
    }
}
