<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Table pivot entre alertes_meteo et conseils.
     */
    public function up(): void
    {
        Schema::create('alerte_conseil', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alerte_meteo_id')
                  ->constrained('alertes_meteo')
                  ->cascadeOnDelete();
            $table->foreignId('conseil_id')
                  ->constrained('conseils')
                  ->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['alerte_meteo_id', 'conseil_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alerte_conseil');
    }
};
