<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder pour créer l'administrateur de l'application.
 * Email : admin@admin.com | Password : admin123
 */
class AdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'nom'              => 'Admin',
                'prenom'           => 'HeatAlert',
                'email'            => 'admin@admin.com',
                'password'         => Hash::make('admin123'),
                'role'             => User::ROLE_ADMIN,
                'telephone'        => '+216 71 000 000',
                'date_inscription' => now(),
            ]
        );

        $this->command->info('✅ Admin créé : admin@admin.com / admin123');
    }
}
