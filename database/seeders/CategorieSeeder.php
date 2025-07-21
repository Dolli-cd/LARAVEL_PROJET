<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    public function run(): void
    {
        // Supprimer toutes les catégories existantes
        //Categorie::truncate();
        $categories = [
            ['name' => 'Analgésiques', 'description' => 'Médicaments contre la douleur'],
            ['name' => 'Antibiotiques', 'description' => 'Médicaments contre les infections bactériennes'],
            ['name' => 'Anti-inflammatoires', 'description' => 'Réduisent l\'inflammation'],
            ['name' => 'Antipyrétiques', 'description' => 'Médicaments contre la fièvre'],
            ['name' => 'Antitussifs', 'description' => 'Calment la toux'],
            ['name' => 'Antidiabétiques', 'description' => 'Régulent la glycémie'],
            ['name' => 'Antihypertenseurs', 'description' => 'Contre l\'hypertension'],
            ['name' => 'Antihistaminiques', 'description' => 'Contre les allergies'],
            ['name' => 'Antidépresseurs', 'description' => 'Traitement de la dépression'],
            ['name' => 'Antiviraux', 'description' => 'Contre les virus'],
            ['name' => 'Antifongiques', 'description' => 'Contre les champignons'],
            ['name' => 'Vitamines et compléments', 'description' => 'Suppléments nutritionnels et fortifiants'],
            ['name' => 'Produits pour bébé', 'description' => 'Laits, soins et produits pour nourrissons'],
            ['name' => 'Hygiène et soins corporels', 'description' => 'Savons, dentifrices, désodorisants...'],
            ['name' => 'Premiers soins', 'description' => 'Pansements, antiseptiques, désinfectants'],
            ['name' => 'Matériel médical', 'description' => 'Tensiomètres, thermomètres, etc.'],
            ['name' => 'Produits féminins', 'description' => 'Hygiène intime, tests de grossesse'],
            ['name' => 'Autres', 'description' => 'Produits divers ou non classés'],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }
    }
} 