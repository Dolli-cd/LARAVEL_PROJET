<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable=[
        'client_id',
        'pharmacie_id',
        'reservation_id',
        'commande_id',
        'amount',
        'type',
        'payment_date',
        'status',
    ];

    protected $appends = ['status_pay'];

     // pour gérer les options
    public function getStatusPayAttribute(){
        return match($this->status){
            'processing'=>'EN cours',
            'approved'=>'Validé',
            'rejected'=>'Refusé',
            default => 'Non spécifié'
        };
    }

     //Un paiement est fait par un client  à une pharmacie sur une commande ou  une réservation
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
