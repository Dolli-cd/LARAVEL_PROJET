<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produit extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'name',
        'code', 
        'type',
        'file',
        // au final prix c'est dans la table pivot ooooh
        'prescription',
        'categorie_id',
    ];
    // relation many to many 
    public function commandes(){
        return $this->belongsToMany(Commande::class)
        ->withPivot('quantity')
        //récemment ajouté
        ->withPivot('prescription_file')
        ->withTimestamps();
    }
    public function reservations(){
        return $this->belongsToMany(Reservation::class)
        ->withPivot('quantity')
        ->withPivot('prescription_file')
        ->withTimestamps();
    }
    public function pharmacies(){
        return $this->belongsToMany(Pharmacie::class)
        ->using(PharmacieProduit::class)
        ->withPivot('price','status','comment','quantity')
        //->withPivot('quantity') pas besoin puisqu'on ne veut pas diffuser le stock de produit
        ->withTimestamps();
    }
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }   
}
