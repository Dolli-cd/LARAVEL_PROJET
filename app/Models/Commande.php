<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commande extends Model
{
    protected $fillable=[
        'client_id',
        'pharmacie_id',
        'status',
        'order_date',
    ];
    protected $appends = ['status_cmd'];


     // pour gérer les options
    public function getStatusCmdAttribute(){
        return match($this->status){
            'confirmed'=>'Confirmée',
            'pending'=>'En attente',
            'delivered'=>'Livrée',
            'cancelled'=>'Annulée',
            default => 'Non spécifié'
        };
    }


    // relation many to many 
    public function produits(){
        return $this->belongsToMany(Produit::class)
        ->withPivot('quantity')
        ->withTimestamps();
    }

     //Une commande est entre un client et une pharmacie 
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    public function pharmacie()
    {
        return $this->belongsTo(Pharmacie::class);
    }
        // une commande a un paiement
    public function paiement()
    {
        return $this->hasOne(Paiement::class);
    }
}
