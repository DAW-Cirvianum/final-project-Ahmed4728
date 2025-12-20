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
        Schema::create('detalls_comanda', function (Blueprint $table) {
            $table->id();

            $table->foreignId('comanda_id')
                ->constrained('comandes')
                ->cascadeOnDelete();

            $table->foreignId('producte_id')
                ->constrained('productes')
                ->restrictOnDelete();

            $table->integer('quantitat');

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalls_comanda');
    }
};
