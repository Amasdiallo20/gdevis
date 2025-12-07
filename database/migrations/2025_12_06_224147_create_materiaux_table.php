<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materiaux', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->decimal('prix_unitaire', 10, 2);
            $table->string('unite')->default('barre');
            $table->timestamp('date_mise_a_jour')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materiaux');
    }
};
