<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Arrondissement extends Model
{
    protected $fillable=[
        'commune_id',
        'name',
    ];
    
     public function quartiers(){
        return $this->hasMany(Quartier::class);
    }
     public function commune(){
        return $this->belongsTo(Commune::class);
    }
}
