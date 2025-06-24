<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ordonnance extends Model
{
    protected $fillable=[
        'client_id',
        'doctor_name',
        'doctor_phone',
        'file_path',
    ];
     //Une ordonnance est pour un client 
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function pharmacies(){
        return $this->belongsToMany(Pharmacie::class)
        //permet d'accéder aux attributs de la table pivot
        ->using(OrdonnancePharmacie::class)
        ->withPivot('date','served_percentage','status')
        ->withTimestamps();
    }
}
