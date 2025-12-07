<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use Carbon\Carbon;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $materiaux = [
            ['nom' => 'Battant Porte', 'prix_unitaire' => 230000, 'unite' => 'barre'],
            ['nom' => 'Butée', 'prix_unitaire' => 130000, 'unite' => 'barre'],
            ['nom' => 'Cadre', 'prix_unitaire' => 260000, 'unite' => 'barre'],
            ['nom' => 'Cadre Porte', 'prix_unitaire' => 160000, 'unite' => 'barre'],
            ['nom' => 'Division', 'prix_unitaire' => 210000, 'unite' => 'barre'],
            ['nom' => 'Montant', 'prix_unitaire' => 240000, 'unite' => 'barre'],
            ['nom' => 'Moustiquaire', 'prix_unitaire' => 90000, 'unite' => 'barre'],
            ['nom' => 'Moustiquaire 3R', 'prix_unitaire' => 90000, 'unite' => 'barre'],
            ['nom' => 'Poignée', 'prix_unitaire' => 130000, 'unite' => 'barre'],
            ['nom' => 'Rail 3R', 'prix_unitaire' => 260000, 'unite' => 'barre'],
            ['nom' => 'Roulette', 'prix_unitaire' => 130000, 'unite' => 'barre'],
            ['nom' => 'Sikane', 'prix_unitaire' => 110000, 'unite' => 'barre'],
            ['nom' => 'Tête', 'prix_unitaire' => 90000, 'unite' => 'barre'],
            ['nom' => 'Vento', 'prix_unitaire' => 160000, 'unite' => 'barre'],
        ];

        foreach ($materiaux as $materiau) {
            Material::updateOrCreate(
                ['nom' => $materiau['nom']],
                [
                    'prix_unitaire' => $materiau['prix_unitaire'],
                    'unite' => $materiau['unite'],
                    'date_mise_a_jour' => Carbon::now(),
                ]
            );
        }
    }
}
