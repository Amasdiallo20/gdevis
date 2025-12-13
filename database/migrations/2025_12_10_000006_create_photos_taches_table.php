<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos_taches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tache_id')->constrained()->onDelete('cascade');
            $table->string('chemin_fichier');
            $table->text('commentaire')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos_taches');
    }
};

