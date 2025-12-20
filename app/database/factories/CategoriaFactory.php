<?php

namespace Database\Factories;

use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'descripcio' => $this->faker->sentence(),
        ];
    }
}


