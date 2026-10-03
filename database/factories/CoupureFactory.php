<?php

namespace Database\Factories;

use App\Models\Coupure;
use App\Models\Zone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory pour les coupures électriques tunisiennes.
 *
 * @extends Factory<Coupure>
 */
class CoupureFactory extends Factory
{
    public function definition(): array
    {
        $dateDebut = $this->faker->dateTimeBetween('-30 days', '+15 days');
        $type = $this->faker->randomElement(Coupure::TYPES);

        return [
            'type' => $type,
            'statut' => $this->faker->randomElement(Coupure::STATUTS),
            'date_debut' => $dateDebut,
            'date_fin' => (clone $dateDebut)->modify('+' . $this->faker->numberBetween(1, 24) . ' hours'),
            'cause' => $this->faker->randomElement([
                'Intervention préventive sur le réseau électrique.',
                'Surcharge temporaire du réseau local.',
                'Défaut technique sur une ligne moyenne tension.',
                'Travaux de maintenance dans la zone.',
            ]),
            'zone_id' => Zone::query()->inRandomOrder()->value('id')
                ?? Zone::factory()->create()->id,
        ];
    }

    /**
     * Crée une coupure de délestage avec une cause obligatoire.
     */
    public function delestage(): static
    {
        return $this->state([
            'type' => 'delestage',
            'cause' => 'Délestage programmé pour équilibrer la charge du réseau.',
        ]);
    }

    /**
     * Crée une coupure actuellement en cours.
     */
    public function enCours(): static
    {
        return $this->state(['statut' => 'en_cours']);
    }
}
