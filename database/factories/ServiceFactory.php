<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'nama' => $this->faker->word(),
            'stok' => $this->faker->randomElement(['Ada', 'Kosong']),
        ];
    }
}
