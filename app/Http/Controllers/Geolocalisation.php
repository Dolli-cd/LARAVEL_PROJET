<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Arrondissement;
use App\Models\Commune;
use App\Models\Client;
use App\Models\Pharmacie;

class Geolocalisation extends Controller
{
    public function getCommunes($departementId) {
        return response()->json(Commune::where('departement_id', $departementId)->get());
    }
    
    public function getArrondissements($communeId) {
        return response()->json(Arrondissement::where('commune_id', $communeId)->get());
    }
}
