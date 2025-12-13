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
            ['nom' => 'Cadre', 'prix_unitaire' => 250, 'unite' => 'barre'],
            ['nom' => 'Vento', 'prix_unitaire' => 220, 'unite' => 'barre'],
            ['nom' => 'Sikane', 'prix_unitaire' => 180, 'unite' => 'barre'],
            ['nom' => 'Moustiquaire', 'prix_unitaire' => 200, 'unite' => 'barre'],
            ['nom' => 'Rail 3R', 'prix_unitaire' => 260, 'unite' => 'barre'],
            ['nom' => 'Montant', 'prix_unitaire' => 240, 'unite' => 'barre'],
            ['nom' => 'Butée', 'prix_unitaire' => 150, 'unite' => 'barre'],
            ['nom' => 'Poignée', 'prix_unitaire' => 150, 'unite' => 'barre'],
            ['nom' => 'Roulette', 'prix_unitaire' => 170, 'unite' => 'barre'],
            ['nom' => 'Tête', 'prix_unitaire' => 170, 'unite' => 'barre'],
            ['nom' => 'Moustiquaire 3R', 'prix_unitaire' => 200, 'unite' => 'barre'],
            ['nom' => 'Cadre Porte', 'prix_unitaire' => 250, 'unite' => 'barre'],
            ['nom' => 'Battant Porte', 'prix_unitaire' => 230, 'unite' => 'barre'],
            ['nom' => 'Division', 'prix_unitaire' => 190, 'unite' => 'barre'],
            ['nom' => 'Pomelles', 'prix_unitaire' => 0, 'unite' => 'unité'],
            // Nouveaux matériaux pour fenêtres ALU A82
            ['nom' => 'Fermeture A82', 'prix_unitaire' => 0, 'unite' => 'Paire'],
            ['nom' => 'Roulette A82', 'prix_unitaire' => 0, 'unite' => 'Paire'],
            ['nom' => 'Roulette Moustiquaire', 'prix_unitaire' => 0, 'unite' => 'Paire'],
            ['nom' => 'Equaire Moustiquaire', 'prix_unitaire' => 0, 'unite' => 'Paire'],
            ['nom' => 'Brosse A82', 'prix_unitaire' => 0, 'unite' => 'm'],
            ['nom' => 'Joint Vitrage', 'prix_unitaire' => 0, 'unite' => 'm'],
            ['nom' => 'Joint Moustiquaire', 'prix_unitaire' => 0, 'unite' => 'm'],
            ['nom' => 'Vitre', 'prix_unitaire' => 0, 'unite' => 'feuille'],
            // Nouveaux matériaux pour fenêtres 3 RAILS
            ['nom' => 'Fermeture 3R', 'prix_unitaire' => 0, 'unite' => 'Paire'],
            ['nom' => 'Roulette Vento 3R', 'prix_unitaire' => 0, 'unite' => 'Paire'],
            ['nom' => 'Roulette Moustiquaire 3R', 'prix_unitaire' => 0, 'unite' => 'Paire'],
            ['nom' => 'Equaire Moustiquaire 3R', 'prix_unitaire' => 0, 'unite' => 'Paire'],
            ['nom' => 'Brosse Libanais', 'prix_unitaire' => 0, 'unite' => 'm'],
            ['nom' => 'Joint Vitrage 3R', 'prix_unitaire' => 0, 'unite' => 'm'],
            ['nom' => 'Rouleaux Joint Vitrage 3R', 'prix_unitaire' => 0, 'unite' => 'rouleau'],
            ['nom' => 'Joint Moustiquaire 3R', 'prix_unitaire' => 0, 'unite' => 'm'],
            ['nom' => 'Grillage Moustiquaire 3R', 'prix_unitaire' => 0, 'unite' => 'm'],
            ['nom' => 'Vitre 3R', 'prix_unitaire' => 0, 'unite' => 'feuille'],
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
