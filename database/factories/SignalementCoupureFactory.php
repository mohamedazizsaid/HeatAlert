<?php

namespace Database\Factories;

use App\Models\Coupure;
use App\Models\SignalementCoupure;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Factory pour les signalements de coupures.
 *
 * @extends Factory<SignalementCoupure>
 */
class SignalementCoupureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'description' => $this->faker->randomElement([
                'Une coupure de courant est constatée depuis ce matin dans le quartier.',
                'Le courant est interrompu et plusieurs immeubles sont concernés.',
                'Des variations de tension sont observées avant la coupure actuelle.',
                'La panne électrique touche plusieurs rues de cette zone.',
            ]),
            'date_signalement' => $this->faker->dateTimeBetween('-15 days', 'now'),
            'photo' => null,
            'statut_validation' => $this->faker->randomElement(SignalementCoupure::STATUTS_VALIDATION),
            'user_id' => User::query()->inRandomOrder()->value('id')
                ?? User::factory()->create()->id,
            'coupure_id' => Coupure::query()->inRandomOrder()->value('id')
                ?? Coupure::factory()->create()->id,
        ];
    }

    /**
     * Crée un signalement validé par un administrateur.
     */
    public function valide(): static
    {
        return $this->state(['statut_validation' => 'valide']);
    }

    /**
     * Crée un signalement encore en attente de validation.
     */
    public function enAttente(): static
    {
        return $this->state(['statut_validation' => 'en_attente']);
    }
}
