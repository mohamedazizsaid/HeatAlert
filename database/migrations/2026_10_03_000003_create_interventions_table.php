<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('interventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coupure_id')->constrained('coupures')->cascadeOnDelete();
            $table->string('equipe', 150);
            $table->dateTime('date_prevue');
            $table->unsignedInteger('duree_estimee_min');
            $table->string('statut', 20)->default('planifiee');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('interventions');
    }
};
