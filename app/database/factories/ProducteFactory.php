<?php

namespace Database\Factories;

use App\Models\Producte;
use App\Models\Categoria;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProducteFactory extends Factory
{
    protected $model = \App\Models\Producte::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->word(),
            'referencia' => strtoupper($this->faker->bothify('??###')),
            'descripcio' => $this->faker->sentence(),
            'quantitat' => $this->faker->numberBetween(0, 200),
            'categoria_id' => Categoria::factory(),
        ];
    }
}
