<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quartier extends Model
{
    protected $fillable=[
        'arrondissement_id',
        'name',
    ];
     public function geolocalisations(){
        return $this->hasMany(Geolocalisation::class);
    }
     public function arrondissement(){
        return $this->belongsTo(Arrondissement::class);
    }
}
