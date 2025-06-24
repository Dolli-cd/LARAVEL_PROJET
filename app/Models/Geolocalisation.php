<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
class Geolocalisation extends Model
{
    protected $fillable=[
        'client_id',
        'pharmacie_id',
        'quartier_id',
        'latitude',
        'longitude',
    ];
    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

      public function client(){
        return $this->belongsTo(Client::class);
    }
    public function pharmacie(){
        return $this->belongsTo(Pharmacie::class);
    }
    public function quartier(){
        return $this->belongsTo(Quartier::class);
    }
}
