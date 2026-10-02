<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Ordre des seeders (respecter les dépendances FK).
     * 1. Zones (pas de dépendance)
     * 2. Admin (pas de dépendance zone)
     * 3. AlerteMeteo (dépend de Zone)
     * 4. Conseils (indépendant)
     */
    public function run(): void
    {
        $this->call([
            ZoneSeeder::class,
            AdminSeeder::class,
            AlerteMeteoSeeder::class,
            ConseilSeeder::class,
        ]);
    }
}
