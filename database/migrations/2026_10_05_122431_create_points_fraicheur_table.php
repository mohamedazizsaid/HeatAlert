<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('points_fraicheur', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->enum('type', ['parc', 'salle_climatisee', 'fontaine', 'piscine', 'bibliotheque', 'autre']);
            $table->string('adresse', 255);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('horaires', 255)->nullable();
            $table->boolean('accessible_pmr')->default(false);
            $table->boolean('actif')->default(true);
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('points_fraicheur');
    }
};
