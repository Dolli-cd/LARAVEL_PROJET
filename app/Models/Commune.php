<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    protected $fillable=[
        'departement_id',
        'name',
    ];
     public function arrondissements(){
        return $this->hasMany(Arrondissement::class);
    }
     public function departement(){
        return $this->belongsTo(Departement::class);
    }
}
