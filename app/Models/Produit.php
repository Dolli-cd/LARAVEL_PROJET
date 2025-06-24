<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produit extends Model
{
    protected $fillable = [
        'name',
        'code', 
        'type',
        'price',
    ];
    // relation many to many 
    public function commandes(){
        return $this->belongsToMany(Commande::class)
        ->withPivot('quantity')
        ->withTimestamps();
    }
    public function reservations(){
        return $this->belongsToMany(Reservation::class)
        ->withPivot('quantity')
        ->withTimestamps();
    }
    public function pharmacies(){
        return $this->belongsToMany(Pharmacie::class)
        ->using(PharmacieProduit::class)
        ->withPivot('status','comment')
        //->withPivot('quantity') pas besoin puisqu'on ne veut pas diffuser le stock de produit
        ->withTimestamps();
    }
}
