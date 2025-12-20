<?php

namespace Database\Factories;

use App\Models\Comanda;
use App\Models\User;
use App\Models\Client;
use App\Models\Proveidor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComandaFactory extends Factory
{
    protected $model = Comanda::class;

    public function definition(): array
    {
        $tipus = $this->faker->randomElement(['entrada', 'sortida']);

        return [
            'data' => $this->faker->dateTimeBetween('-1 years', 'now'),
            'tipus' => $tipus,
            'user_id' => User::factory(),
            'client_id' => Client::factory(),
            'proveidor_id' => Proveidor::factory(),
        ];
    }
}
