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
        Schema::create('ordonnance_pharmacie', function (Blueprint $table) {
            $table->unsignedBigInteger('ordonnance_id');
            $table->unsignedBigInteger('pharmacie_id');
            $table->datetime('date');
            $table->enum('status',['served','partially_served','not_served']);
            $table->double('served_percentage');
            $table->timestamps();
            $table->primary(['ordonnance_id', 'pharmacie_id']);
            $table->foreign('ordonnance_id')->references('id')->on('ordonnances')->onDelete('cascade');
            $table->foreign('pharmacie_id')->references('id')->on('pharmacies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ordonnance_pharmacie');
    }
};
