<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('alertes_meteo', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 150);
            $table->string('type', 30)->default('canicule');      // canicule, vague_de_chaleur, orage, secheresse, autre
            $table->string('niveau', 20)->default('jaune');       // vert, jaune, orange, rouge
            $table->string('statut', 20)->default('brouillon');   // brouillon, active, terminee, annulee
            $table->text('description');
            $table->float('temperature_min')->nullable();
            $table->float('temperature_max')->nullable();
            $table->float('temperature_ressentie')->nullable();
            $table->unsignedTinyInteger('humidite')->nullable();  // en %
            $table->unsignedTinyInteger('indice_uv')->nullable();
            $table->float('vitesse_vent')->nullable();            // km/h
            $table->boolean('risque_coupure')->default(false);
            $table->string('source', 100)->nullable();
            $table->dateTime('date_debut');
            $table->dateTime('date_fin')->nullable();
            $table->foreignId('zone_id')->nullable()->constrained('zones')->nullOnDelete();
            $table->timestamps();                                  // created_at + updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerte_meteos');
    }
};
