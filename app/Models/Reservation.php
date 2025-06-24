<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{//pas finiiii
    protected $fillable = [
        'client_id',
        'pharmacie_id',
        'booking_date',
        'status', 
    ];
    protected $appends = ['status_res'];


    // pour gérer les options
    public function getStatusResAttribute(){
        return match($this->status){
            'confirmed'=>'Confirmée',
            'pending'=>'En attente',
            'rejected'=>'Refusée',
            'expired'=>'Expirée',
            default => 'Non spécifié'
        };
    }
    // relation many to many 
    public function produits(){
        return $this->belongsToMany(Produit::class)
        ->withPivot('quantity')
        ->withTimestamps();
    }

    //Une réservation est entre un client et une pharmacie 
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }

     public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }

}
