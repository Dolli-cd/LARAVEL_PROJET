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
        Schema::create('produit_reservation', function (Blueprint $table) {
            $table->unsignedBigInteger('produit_id');
            $table->unsignedBigInteger('reservation_id');

            $table->primary(['produit_id', 'reservation_id']);
            $table->integer('quantity');
            $table->string('prescription_file')->nullable(); // pour la soumission du formulaire
            $table->timestamps();

            $table->foreign('produit_id')->references('id')->on('produits')->onDelete('cascade');
            $table->foreign('reservation_id')->references('id')->on('reservations')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produit_reservation');
    }
};
