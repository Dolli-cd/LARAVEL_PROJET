<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Pharmacie extends User
{
    use Notifiable;
   
    protected $fillable=[
        'user_id',
        'schedule',
        'guard_time',
        'insurance_name',
        'online',
    ];
     public function user(){
        return $this->belongsTo(User::class);
    }
    // relation many to many 
    public function produits(){
        return $this->belongsToMany(Produit::class)
        //permet d'accéder aux attributs de la table pivot
        ->using(PharmacieProduit::class)
        ->withPivot('price','status', 'comment','quantity')
        ->withTimestamps();
    }
 
    // une pharmacie a plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
    //une pharmacie a plusieurs commandes
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
    
    
     public function geolocalisation(){
        return $this->hasOne(Geolocalisation::class);
    }

}
