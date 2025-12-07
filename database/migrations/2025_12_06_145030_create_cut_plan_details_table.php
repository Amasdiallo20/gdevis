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
        Schema::create('cut_plan_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cut_plan_id')->constrained('cut_plans')->onDelete('cascade');
            $table->integer('bar_number');
            $table->json('sections')->comment('List of cut lengths in cm');
            $table->decimal('used_length', 10, 2)->default(0)->comment('Used length in cm');
            $table->decimal('waste', 10, 2)->default(0)->comment('Waste in cm');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cut_plan_details');
    }
};
