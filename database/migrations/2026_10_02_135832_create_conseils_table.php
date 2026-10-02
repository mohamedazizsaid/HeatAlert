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
        Schema::create('conseils', function (Blueprint $table) {
            $table->id();
            $table->string('titre', 150);
            $table->text('contenu');
            $table->string('categorie', 30)->default('hydratation'); // hydratation, energie, equipements, sante, habitat, deplacement
            $table->string('public_cible', 30)->default('tous');     // tous, enfants, personnes_agees, malades_chroniques, sportifs
            $table->string('niveau_alerte_cible', 20)->nullable();
            $table->unsignedTinyInteger('priorite')->default(1);     // 1 = faible, 5 = urgent
            $table->string('icone', 50)->nullable();
            $table->string('image')->nullable();
            $table->boolean('actif')->default(true);
            $table->boolean('genere_par_ia')->default(false);        // utile pour la partie IA
            $table->foreignId('alerte_meteo_id')->nullable()->constrained('alertes_meteo')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conseils');
    }
};
