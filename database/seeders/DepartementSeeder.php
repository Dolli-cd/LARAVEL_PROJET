<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    \App\Models\Departement::insert([
        ['id' => 1, 'name' => 'Alibori'],
        ['id' => 2, 'name' => 'Atacora'],
        ['id' => 3, 'name' => 'Atlantique'],
        ['id' => 4, 'name' => 'Borgou'],
        ['id' => 5, 'name' => 'Collines'],
        ['id' => 6, 'name' => 'Couffo'],
        ['id' => 7, 'name' => 'Donga'],
        ['id' => 8, 'name' => 'Littoral'],
        ['id' => 9, 'name' => 'Mono'],
        ['id' => 10, 'name' => 'Ouémé'],
        ['id' => 11, 'name' => 'Plateau'],
        ['id' => 12, 'name' => 'Zou'],
    ]);

        
    }
}
