<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            'Généraliste', 'Cardiologue', 'Dermatologue', 'Pédiatre',
            'Ophtalmologue', 'Dentiste', 'Neurologue', 'Gynécologue'
        ];

        foreach ($specialties as $name) {
            Specialty::create([
                'name' => $name,
            ]);
        }
    }
}
