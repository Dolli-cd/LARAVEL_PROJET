<?php

namespace App\Services;

class ProduitSearchService
{
    /**
     * Applique les filtres de recherche à une query Produit.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|null $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function apply($query, $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('code', 'ILIKE', "%{$search}%")
                  ->orWhere('type', 'ILIKE', "%{$search}%")
                  ->orWhereHas('pharmacies', function ($q2) use ($search) {
                      $q2->where('pharmacie_produit.price', 'ILIKE', "%{$search}%");
                  });
            });
        }
        return $query;
    }
}
