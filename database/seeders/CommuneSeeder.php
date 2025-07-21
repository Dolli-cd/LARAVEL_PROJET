<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommuneSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Commune::insert([
            // Alibori (1)
            ['name' => 'Banikoara', 'departement_id' => 1],
            ['name' => 'Gogounou', 'departement_id' => 1],
            ['name' => 'Kandi', 'departement_id' => 1],
            ['name' => 'Karimama', 'departement_id' => 1],
            ['name' => 'Malanville', 'departement_id' => 1],
            ['name' => 'Ségbana', 'departement_id' => 1],

            // Atacora (2)
            ['name' => 'Boukoumbé', 'departement_id' => 2],
            ['name' => 'Cobly', 'departement_id' => 2],
            ['name' => 'Kérou', 'departement_id' => 2],
            ['name' => 'Kouandé', 'departement_id' => 2],
            ['name' => 'Matéri', 'departement_id' => 2],
            ['name' => 'Natitingou', 'departement_id' => 2],
            ['name' => 'Péhunco', 'departement_id' => 2],
            ['name' => 'Tanguiéta', 'departement_id' => 2],
            ['name' => 'Toucountouna', 'departement_id' => 2],

            // Atlantique (3)
            ['name' => 'Abomey‑Calavi', 'departement_id' => 3],
            ['name' => 'Allada', 'departement_id' => 3],
            ['name' => 'Kpomassè', 'departement_id' => 3],
            ['name' => 'Ouidah', 'departement_id' => 3],
            ['name' => 'Sô‑Ava', 'departement_id' => 3],
            ['name' => 'Toffo', 'departement_id' => 3],
            ['name' => 'Tori‑Bossito', 'departement_id' => 3],
            ['name' => 'Zè', 'departement_id' => 3],

            // Borgou (4)
            ['name' => 'Bembéréké', 'departement_id' => 4],
            ['name' => 'Kalalé', 'departement_id' => 4],
            ['name' => 'N\'Dali', 'departement_id' => 4],
            ['name' => 'Nikki', 'departement_id' => 4],
            ['name' => 'Parakou', 'departement_id' => 4],
            ['name' => 'Pèrèrè', 'departement_id' => 4],
            ['name' => 'Sinendé', 'departement_id' => 4],
            ['name' => 'Tchaourou', 'departement_id' => 4],

            // Collines (5)
            ['name' => 'Bantè', 'departement_id' => 5],
            ['name' => 'Dassa‑Zoumè', 'departement_id' => 5],
            ['name' => 'Glazoué', 'departement_id' => 5],
            ['name' => 'Ouèssè', 'departement_id' => 5],
            ['name' => 'Savalou', 'departement_id' => 5],
            ['name' => 'Savè', 'departement_id' => 5],

            // Couffo (6)
            ['name' => 'Aplahoué', 'departement_id' => 6],
            ['name' => 'Djakotomey', 'departement_id' => 6],
            ['name' => 'Dogbo‑Tota', 'departement_id' => 6],
            ['name' => 'Klouékanmè', 'departement_id' => 6],
            ['name' => 'Lalo', 'departement_id' => 6],
            ['name' => 'Toviklin', 'departement_id' => 6],

            // Donga (7)
            ['name' => 'Bassila', 'departement_id' => 7],
            ['name' => 'Copargo', 'departement_id' => 7],
            ['name' => 'Djougou', 'departement_id' => 7],
            ['name' => 'Ouaké', 'departement_id' => 7],

            // Littoral (8)
            ['name' => 'Cotonou', 'departement_id' => 8],

            // Mono (9)
            ['name' => 'Athiémé', 'departement_id' => 9],
            ['name' => 'Bopa', 'departement_id' => 9],
            ['name' => 'Comè', 'departement_id' => 9],
            ['name' => 'Grand‑Popo', 'departement_id' => 9],
            ['name' => 'Houéyogbé', 'departement_id' => 9],
            ['name' => 'Lokossa', 'departement_id' => 9],

            // Ouémé (10)
            ['name' => 'Adjarra', 'departement_id' => 10],
            ['name' => 'Adjohoun', 'departement_id' => 10],
            ['name' => 'Aguégués', 'departement_id' => 10],
            ['name' => 'Akpro‑Missérété', 'departement_id' => 10],
            ['name' => 'Avrankou', 'departement_id' => 10],
            ['name' => 'Bonou', 'departement_id' => 10],
            ['name' => 'Dangbo', 'departement_id' => 10],
            ['name' => 'Porto‑Novo', 'departement_id' => 10],
            ['name' => 'Sèmè‑Kpodji', 'departement_id' => 10],

            // Plateau (11)
            ['name' => 'Adja‑Ouèrè', 'departement_id' => 11],
            ['name' => 'Ifangni', 'departement_id' => 11],
            ['name' => 'Kétou', 'departement_id' => 11],
            ['name' => 'Pobè', 'departement_id' => 11],
            ['name' => 'Sakété', 'departement_id' => 11],

            // Zou (12)
            ['name' => 'Abomey', 'departement_id' => 12],
            ['name' => 'Agbangnizoun', 'departement_id' => 12],
            ['name' => 'Bohicon', 'departement_id' => 12],
            ['name' => 'Covè', 'departement_id' => 12],
            ['name' => 'Djidja', 'departement_id' => 12],
            ['name' => 'Ouinhi', 'departement_id' => 12],
            ['name' => 'Za‑Kpota', 'departement_id' => 12],
            ['name' => 'Zagnanado', 'departement_id' => 12],
            ['name' => 'Zogbodomey', 'departement_id' => 12],
        ]);
    }
}
