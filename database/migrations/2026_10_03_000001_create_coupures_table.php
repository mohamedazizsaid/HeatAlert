<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
    * Crée la table des coupures électriques.
     */
    public function up(): void
    {
        Schema::create('coupures', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20); // delestage, surcharge, panne
            $table->string('statut', 20)->default('prevue'); // prevue, en_cours, terminee
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->text('cause')->nullable();
            $table->foreignId('zone_id')->constrained('zones')->restrictOnDelete();
            $table->timestamps();
        });
    }

    /**
    * Supprime la table des coupures électriques.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupures');
    }
};