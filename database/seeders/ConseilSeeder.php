<?php

namespace Database\Seeders;

use App\Models\Conseil;
use Illuminate\Database\Seeder;

/**
 * Seeder pour les conseils santé/météo.
 */
class ConseilSeeder extends Seeder
{
    public function run(): void
    {
        Conseil::factory(50)->create();
        $this->command->info('✅ 50 conseils créés.');
    }
}
