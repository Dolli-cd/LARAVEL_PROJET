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
        Schema::create('pharmacie_produit', function (Blueprint $table) {
            $table->unsignedBigInteger('pharmacie_id');
            $table->unsignedBigInteger('produit_id');
            $table->enum('status',['available','unavailable']);
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->primary(['pharmacie_id', 'produit_id']);
            $table->foreign('pharmacie_id')->references('id')->on('pharmacies')->onDelete('cascade');
            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmacie_produit');
    }
};
