<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pharmacie extends Model
{
   
    protected $fillable=[
        'user_id',
        'schedule',
        'guard_time',
        'insurance_name',
    ];
     public function user(){
        return $this->belongsTo(User::class);
    }
    // relation many to many 
    public function produits(){
        return $this->belongsToMany(Produit::class)
        //permet d'accéder aux attributs de la table pivot
        ->using(PharmacieProduit::class)
        ->withPivot('status', 'comment')
        ->withTimestamps();
    }
    public function ordonnances(){
        return $this->belongsToMany(Ordonnance::class)
        //permet d'accéder aux attributs de la table pivot
        ->using(OrdonnancePharmacie::class)
        ->withPivot('date','served_percentage','status')
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
    // une pharmacie reçoit plusieurs notifications
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
    
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
    
     public function geolocalisation(){
        return $this->hasOne(Geolocalisation::class);
    }

}
