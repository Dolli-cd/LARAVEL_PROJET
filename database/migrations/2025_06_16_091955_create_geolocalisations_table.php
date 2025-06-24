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
        Schema::create('geolocalisations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pharmacie_id')->constrained('pharmacies')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');

            // 🔧 Si tu veux 'on delete set null', il faut utiliser unsignedBigInteger + foreign
            $table->unsignedBigInteger('quartier_id')->nullable();
            $table->foreign('quartier_id')->references('id')->on('quartiers')->onDelete('set null');

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geolocalisations');
    }
};
