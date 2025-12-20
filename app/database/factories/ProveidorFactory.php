<?php

namespace Database\Factories;

use App\Models\Proveidor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProveidorFactory extends Factory
{
    protected $model = Proveidor::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->company(),
            'correu' => $this->faker->unique()->companyEmail(),
            'telefon' => $this->faker->phoneNumber(),
        ];
    }
}
