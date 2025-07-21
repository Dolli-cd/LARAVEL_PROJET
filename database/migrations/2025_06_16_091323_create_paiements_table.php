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
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();//pas besoin de client et pharmacie car un payement est pour une commande et une réservation sinon client et pharmacie seront redondant
            // certains peuvent être nul
            $table->foreignId('commande_id')->nullable()->constrained('commandes')->onDelete('cascade');
            $table->foreignId('reservation_id')->nullable()->constrained('reservations')->onDelete('cascade');
            $table->decimal('amount');
            $table->string('type');
            $table->datetime('payment_date')->nullable();
            $table->enum('status',['processing','approved','rejected']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
