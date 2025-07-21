<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;


class PharmacieProduit extends Pivot
{
    //  j'ai créer le model de cette table pivot puisque elle a une enumeration sur elle 
    protected $table = 'pharmacie_produit';//important cette ligne si on n'avait pas mis le nom connu par laravel à la table pivot

    protected $fillable = [
        'pharmacie_id',
        'produit_id',
        'status',
        'price',
        'quantity',
        'comment',
    ];
    protected $appends = ['status_prod'];


    public function getStatusProdAttribute()
    {
        return match ($this->status) {
            'available' => 'Disponible',
            'unavailable' => 'Indisponible',
            default => 'Inconnu',
        };
    }
    
}
