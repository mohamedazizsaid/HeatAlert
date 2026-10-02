<?php

namespace Database\Seeders;

use App\Models\AlerteMeteo;
use App\Models\Zone;
use Illuminate\Database\Seeder;

/**
 * Seeder pour les alertes météo.
 */
class AlerteMeteoSeeder extends Seeder
{
    public function run(): void
    {
        $zones = Zone::all();

        if ($zones->isEmpty()) {
            $this->command->warn('Aucune zone trouvée, création des zones d\'abord...');
            $this->call(ZoneSeeder::class);
            $zones = Zone::all();
        }

        // Alertes active prioritaires (rouge/orange)
        $alertesActives = [
            [
                'titre'               => 'Vague de chaleur extrême sur Tunis',
                'type'                => 'canicule',
                'niveau'              => 'rouge',
                'statut'              => 'active',
                'description'         => 'Une vague de chaleur exceptionnelle touche la région de Tunis avec des températures dépassant les 45°C. Risque élevé pour la santé des personnes vulnérables.',
                'temperature_min'     => 34.0,
                'temperature_max'     => 46.5,
                'temperature_ressentie' => 50.0,
                'humidite'            => 25,
                'indice_uv'           => 11,
                'vitesse_vent'        => 15.5,
                'risque_coupure'      => true,
                'source'              => 'INM Tunisie',
                'date_debut'          => now()->subDays(2),
                'date_fin'            => now()->addDays(3),
                'ville_zone'          => 'Tunis',
            ],
            [
                'titre'               => 'Alerte canicule - Sfax et environs',
                'type'                => 'canicule',
                'niveau'              => 'orange',
                'statut'              => 'active',
                'description'         => 'Températures très élevées attendues sur le gouvernorat de Sfax. Populations vulnérables et travailleurs en extérieur particulièrement exposés.',
                'temperature_min'     => 31.0,
                'temperature_max'     => 43.0,
                'temperature_ressentie' => 47.0,
                'humidite'            => 35,
                'indice_uv'           => 10,
                'vitesse_vent'        => 20.0,
                'risque_coupure'      => false,
                'source'              => 'INM Tunisie',
                'date_debut'          => now()->subDay(),
                'date_fin'            => now()->addDays(2),
                'ville_zone'          => 'Sfax',
            ],
            [
                'titre'               => 'Sécheresse sévère - Sud tunisien',
                'type'                => 'secheresse',
                'niveau'              => 'orange',
                'statut'              => 'active',
                'description'         => 'Déficit pluviométrique important enregistré dans les gouvernorats du sud. Restriction d\'eau en vigueur. Population invitée à économiser l\'eau.',
                'temperature_min'     => 28.0,
                'temperature_max'     => 42.0,
                'temperature_ressentie' => 45.0,
                'humidite'            => 15,
                'indice_uv'           => 9,
                'vitesse_vent'        => 30.0,
                'risque_coupure'      => false,
                'source'              => 'SONEDE',
                'date_debut'          => now()->subWeeks(2),
                'date_fin'            => null,
                'ville_zone'          => 'Gabès',
            ],
        ];

        foreach ($alertesActives as $data) {
            $villeZone = $data['ville_zone'];
            unset($data['ville_zone']);
            $zone = $zones->firstWhere('ville', $villeZone) ?? $zones->random();
            $data['zone_id'] = $zone->id;
            AlerteMeteo::create($data);
        }

        // Générer 27 alertes supplémentaires avec la factory
        AlerteMeteo::factory(27)->create();

        $this->command->info('✅ 30 alertes météo créées.');
    }
}
