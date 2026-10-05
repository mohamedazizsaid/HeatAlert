<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('avis_points', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('note')->unsigned(); // 1-5
            $table->text('commentaire')->nullable();
            $table->date('date_avis');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('point_fraicheur_id')->constrained('points_fraicheur')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('avis_points');
    }
};
