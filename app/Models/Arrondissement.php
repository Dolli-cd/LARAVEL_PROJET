<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrondissement extends Model
{
    protected $fillable=[
        'commune_id',
        'name',
    ];
     public function commune(){
        return $this->belongsTo(Commune::class);
    }
    public function geolocalisations(){
        return $this->hasMany(Geolocalisation::class);
    }
}
