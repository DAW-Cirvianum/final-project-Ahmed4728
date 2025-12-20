<?php

namespace Database\Factories;

use App\Models\DetallComanda;
use App\Models\Comanda;
use App\Models\Producte;
use Illuminate\Database\Eloquent\Factories\Factory;

class DetallComandaFactory extends Factory
{
    protected $model = DetallComanda::class;

    public function definition(): array
    {
        return [
            'comanda_id' => Comanda::factory(),
            'producte_id' => Producte::factory(),
            'quantitat' => $this->faker->numberBetween(1, 20),
        ];
    }
}
