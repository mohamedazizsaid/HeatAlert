<?php

namespace Database\Seeders;

use App\Models\Coupure;
use App\Models\SignalementCoupure;
use App\Models\Zone;
use Illuminate\Database\Seeder;

/**
 * Seeder pour les coupures et leurs signalements.
 */
class CoupureSeeder extends Seeder
{
    public function run(): void
    {
        if (Zone::query()->doesntExist()) {
            $this->call(ZoneSeeder::class);
        }

        $coupures = Coupure::factory()->count(12)->create();

        SignalementCoupure::factory()
            ->count(24)
            ->create([
                'coupure_id' => fn () => $coupures->random()->id,
            ]);

        $this->command->info('12 coupures et 24 signalements créés.');
    }
}
