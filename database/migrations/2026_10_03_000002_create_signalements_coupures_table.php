<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
    * Crée la table des signalements de coupures.
     */
    public function up(): void
    {
        Schema::create('signalements_coupures', function (Blueprint $table) {
            $table->id();
            $table->text('description');
            $table->dateTime('date_signalement')->useCurrent();
            $table->string('photo')->nullable();
            $table->string('statut_validation', 20)->default('en_attente'); // en_attente, valide, rejete
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('coupure_id')->nullable()->constrained('coupures')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
    * Supprime la table des signalements de coupures.
     */
    public function down(): void
    {
        Schema::dropIfExists('signalements_coupures');
    }
};