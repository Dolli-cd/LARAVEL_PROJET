<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Pivot;

class OrdonnancePharmacie extends Pivot
{
    //  j'ai créer le model de cette table pivot puisque elle a une enumeration sur elle 
    protected $table = 'ordonnance_pharmacie';

    protected $fillable = [
        'pharmacie_id',
        'ordonnance_id',
        'date', 
        'served_percentage',
        'status',
    ];

    protected $appends = ['status_ord'];


    public function getStatusOrdAttribute()
    {
        return match ($this->status) {
            'served' => 'Servi',
            'partially_served' => 'Partiellement servi',
            'not_served' => 'Non servi',
            default => 'Inconnu',
        };
    }
}
