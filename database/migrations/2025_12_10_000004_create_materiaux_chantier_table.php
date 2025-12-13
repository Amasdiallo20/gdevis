<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materiaux_chantier', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chantier_id')->constrained()->onDelete('cascade');
            $table->string('nom_materiau');
            $table->string('unite')->default('unité'); // m, m², feuille, paire, unité, etc.
            $table->decimal('quantite_prevue', 10, 3)->default(0);
            $table->decimal('quantite_utilisee', 10, 3)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiaux_chantier');
    }
};

