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
            $table->decimal('latitude', 10, 7)->default(0);
            $table->decimal('longitude', 10, 7)->default(0);
//j'ai ajouté le nullable après 
            $table->foreignId('pharmacie_id')->nullable()->constrained('pharmacies')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('cascade');

            // 🔧 Si tu veux 'on delete set null', il faut utiliser unsignedBigInteger + foreign
            $table->unsignedBigInteger('arrondissement_id')->nullable();
            $table->foreign('arrondissement_id')->references('id')->on('arrondissements')->onDelete('set null');
// longitude et  latitude en anglais sinon comme client est rattaché à commande, produit et réservation dans payement est ce qu'on a besoin de ça et aussi dans le formulaire de pharmacie on doit avoir alors commune,département.....
            

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
