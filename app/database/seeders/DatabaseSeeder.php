<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Categoria;
use App\Models\Client;
use App\Models\Producte;
use App\Models\Proveidor;
use App\Models\Comanda;
use App\Models\DetallComanda;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Users
        User::factory(8)->create();

        // Categories, providers, clients
        $categories = Categoria::factory(6)->create();
        $proveidors = Proveidor::factory(6)->create();
        $clients = Client::factory(20)->create();

        // Products: create models and assign existing categories to avoid creating extra categories
        $products = Producte::factory(80)->make()->each(function ($p) use ($categories) {
            $p->categoria_id = $categories->random()->id;
            $p->save();
        });

        // Orders (comandes) and lines
        $users = User::all();
        for ($i = 0; $i < 30; $i++) {
            $comanda = Comanda::factory()->make([
                'user_id' => $users->random()->id,
                'client_id' => $clients->random()->id,
                'proveidor_id' => $proveidors->random()->id,
            ]);
            $comanda->save();

            $lines = rand(1, 5);
            for ($j = 0; $j < $lines; $j++) {
                $line = DetallComanda::factory()->make([
                    'comanda_id' => $comanda->id,
                    'producte_id' => $products->random()->id,
                ]);
                $line->save();
            }
        }
    }
}
