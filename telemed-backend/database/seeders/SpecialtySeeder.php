<?php

namespace Database\Seeders;

use App\Models\Specialty;
use Illuminate\Database\Seeder;

class SpecialtySeeder extends Seeder
{
    public function run(): void
    {
        $specialties = [
            ['name' => 'Médecine Générale', 'icon' => 'stethoscope'],
            ['name' => 'Cardiologie', 'icon' => 'heart-pulse'],
            ['name' => 'Dermatologie', 'icon' => 'hand'],
            ['name' => 'Pédiatrie', 'icon' => 'baby'],
            ['name' => 'Gynécologie', 'icon' => 'venus'],
            ['name' => 'Ophtalmologie', 'icon' => 'eye'],
            ['name' => 'Psychiatrie', 'icon' => 'brain'],
            ['name' => 'Neurologie', 'icon' => 'activity'],
            ['name' => 'ORL', 'icon' => 'ear'],
            ['name' => 'Orthopédie', 'icon' => 'bone'],
            ['name' => 'Dentiste', 'icon' => 'smile'],
        ];

        foreach ($specialties as $specialty) {
            Specialty::create($specialty);
        }

        $this->command->info('✓ ' . count($specialties) . ' spécialités créées');
    }
}
