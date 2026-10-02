<?php

namespace Database\Factories;

use App\Models\AlerteMeteo;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory pour les alertes météo tunisiennes.
 */
class AlerteMeteoFactory extends Factory
{
    private static array $titres = [
        'canicule'        => ['Vague de chaleur intense sur %s', 'Alerte canicule - %s', 'Températures extrêmes à %s'],
        'vague_de_chaleur' => ['Vague de chaleur persistante sur %s', 'Forte chaleur attendue à %s', 'Vigilance chaleur - %s'],
        'orage'           => ['Orages violents sur %s', 'Risque d\'orage élevé à %s', 'Alerte orage et pluie - %s'],
        'secheresse'      => ['Sécheresse sévère - %s', 'Manque de précipitations à %s', 'Alerte sécheresse - %s'],
        'autre'           => ['Phénomène météo exceptionnel - %s', 'Alerte météo spéciale à %s'],
    ];

    private static array $sources = [
        'INM Tunisie',
        'Institut National de la Météorologie',
        'ANPE Tunisie',
        'Météo Tunisie',
        'Ministère de l\'Environnement',
    ];

    public function definition(): array
    {
        $zone  = Zone::inRandomOrder()->first() ?? Zone::factory()->create();
        $type  = $this->faker->randomElement(AlerteMeteo::TYPES ?? ['canicule', 'vague_de_chaleur', 'orage', 'secheresse', 'autre']);
        $niveau = $this->faker->randomElement(['vert', 'jaune', 'orange', 'rouge']);
        $titreTemplate = $this->faker->randomElement(self::$titres[$type] ?? ['Alerte - %s']);
        $debut = $this->faker->dateTimeBetween('-3 months', '+1 month');
        $fin   = (clone $debut)->modify('+' . $this->faker->numberBetween(1, 7) . ' days');

        // Températures réalistes pour la Tunisie
        $tempMin = $this->faker->randomFloat(1, 22, 38);
        $tempMax = $this->faker->randomFloat(1, $tempMin + 2, 48);

        return [
            'titre'               => sprintf($titreTemplate, $zone->ville),
            'type'                => $type,
            'niveau'              => $niveau,
            'statut'              => $this->faker->randomElement(['brouillon', 'active', 'terminee', 'annulee']),
            'description'         => $this->faker->paragraph(3),
            'temperature_min'     => $tempMin,
            'temperature_max'     => $tempMax,
            'temperature_ressentie' => $this->faker->randomFloat(1, $tempMax, $tempMax + 5),
            'humidite'            => $this->faker->numberBetween(10, 90),
            'indice_uv'           => $this->faker->numberBetween(1, 11),
            'vitesse_vent'        => $this->faker->randomFloat(1, 0, 80),
            'risque_coupure'      => $this->faker->boolean(30),
            'source'              => $this->faker->randomElement(self::$sources),
            'date_debut'          => $debut,
            'date_fin'            => $this->faker->boolean(70) ? $fin : null,
            'zone_id'             => $zone->id,
        ];
    }

    /** Scope: alerte active */
    public function active(): static
    {
        return $this->state(['statut' => 'active']);
    }

    /** Scope: niveau rouge */
    public function rouge(): static
    {
        return $this->state(['niveau' => 'rouge']);
    }
}
