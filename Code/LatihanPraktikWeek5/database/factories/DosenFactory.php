<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DosenFactory extends Factory
{
    public function definition()
    {
        $email = $this->faker->unique()->safeEmail();
        return [
            'nip' => $this->faker->unique()->numerify('##################'),
            'nama' => $this->faker->name(),
            'email' => $email,
            'username' => Str::before($email, '@'),
        ];
    }
}
