<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Paiement extends Model
{
    protected $fillable=[
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

     //Un paiement est fait pour  une commande ou  une réservation
  
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
