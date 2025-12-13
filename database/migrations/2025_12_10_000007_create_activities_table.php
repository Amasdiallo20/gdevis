<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Technicien qui a fait l'action
            $table->foreignId('tache_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('chantier_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('type'); // progress_updated, status_changed, photo_uploaded, photo_comment_added
            $table->text('description'); // Description de l'action
            $table->json('data')->nullable(); // Données supplémentaires (ancienne valeur, nouvelle valeur, etc.)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};

