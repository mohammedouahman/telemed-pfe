<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════╗');
        $this->command->info('║   TeleMed — Seeding Demo Data            ║');
        $this->command->info('╚══════════════════════════════════════════╝');
        $this->command->info('');

        $this->call([
            SpecialtySeeder::class,
            UserSeeder::class,
            AvailabilitySeeder::class,
            AppointmentSeeder::class,
        ]);

        $this->command->info('');
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('  RÉSUMÉ FINAL');
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('  Spécialités     : ' . DB::table('specialties')->count());
        $this->command->info('  Utilisateurs    : ' . DB::table('users')->count());
        $this->command->info('   ├─ Admin       : ' . DB::table('users')->where('role', 'admin')->count());
        $this->command->info('   ├─ Médecins    : ' . DB::table('users')->where('role', 'doctor')->count());
        $this->command->info('   └─ Patients    : ' . DB::table('users')->where('role', 'patient')->count());
        $this->command->info('  Profils médecin : ' . DB::table('doctor_profiles')->count());
        $this->command->info('   ├─ Vérifiés    : ' . DB::table('doctor_profiles')->where('is_verified', true)->count());
        $this->command->info('   └─ En attente  : ' . DB::table('doctor_profiles')->where('is_verified', false)->count());
        $this->command->info('  Disponibilités  : ' . DB::table('doctor_availabilities')->count());
        $this->command->info('  Rendez-vous     : ' . DB::table('appointments')->count());
        $this->command->info('  Consultations   : ' . DB::table('consultations')->count());
        $this->command->info('  Ordonnances     : ' . DB::table('prescriptions')->count());
        $this->command->info('  Avis patients   : ' . DB::table('reviews')->count());
        $this->command->info('══════════════════════════════════════════');
        $this->command->info('');
        $this->command->info('  Comptes démo :');
        $this->command->info('  Patient  → jean@telemed.ma / patient123');
        $this->command->info('  Médecin  → sarah@telemed.ma / doctor123');
        $this->command->info('  Médecin  → karim@telemed.ma / doctor123');
        $this->command->info('  Admin    → admin@telemed.ma / admin123');
        $this->command->info('');
    }
}
