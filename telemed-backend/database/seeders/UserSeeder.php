<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin TeleMed',
            'email' => 'admin@telemed.ma',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
        ]);

        // Doctors
        $doctors = [
            ['name' => 'Dr. Arrami Youssef', 'email' => 'arrami@telemed.ma', 'specialty' => 'Cardiologue', 'exp' => 15, 'fee' => 25.00, 'city' => 'Paris', 'verified' => true, 'rating' => 4.9],
            ['name' => 'Dr. Sarah Bernard', 'email' => 'sarah@telemed.ma', 'specialty' => 'Généraliste', 'exp' => 8, 'fee' => 20.00, 'city' => 'Lyon', 'verified' => true, 'rating' => 4.8],
            ['name' => 'Dr. Marc Lefevre', 'email' => 'marc@telemed.ma', 'specialty' => 'Dermatologue', 'exp' => 10, 'fee' => 30.00, 'city' => 'Bordeaux', 'verified' => true, 'rating' => 4.9],
            ['name' => 'Dr. Julie Martin', 'email' => 'julie@telemed.ma', 'specialty' => 'Pédiatre', 'exp' => 12, 'fee' => 22.00, 'city' => 'Marseille', 'verified' => true, 'rating' => 5.0],
            ['name' => 'Dr. Thomas Dubois', 'email' => 'thomas@telemed.ma', 'specialty' => 'Ophtalmologue', 'exp' => 6, 'fee' => 28.00, 'city' => 'Lille', 'verified' => true, 'rating' => 4.7],
            ['name' => 'Dr. Sophie Chen', 'email' => 'sophie@telemed.ma', 'specialty' => 'Dentiste', 'exp' => 9, 'fee' => 35.00, 'city' => 'Nantes', 'verified' => true, 'rating' => 4.8],
            ['name' => 'Dr. Karim Mansouri', 'email' => 'karim@telemed.ma', 'specialty' => 'Neurologue', 'exp' => 14, 'fee' => 40.00, 'city' => 'Casablanca', 'verified' => false, 'rating' => 4.6],
        ];

        foreach ($doctors as $index => $doc) {
            $user = User::create([
                'name' => $doc['name'],
                'email' => $doc['email'],
                'password' => Hash::make('doctor123'),
                'role' => 'doctor',
                'avatar' => 'https://i.pravatar.cc/150?img=' . ($index + 10),
            ]);

            DoctorProfile::create([
                'user_id' => $user->id,
                'specialty' => $doc['specialty'],
                'experience_years' => $doc['exp'],
                'consultation_fee' => $doc['fee'],
                'city' => $doc['city'],
                'is_verified' => $doc['verified'],
                'rating_average' => $doc['rating'],
            ]);
        }

        // Patients
        $patients = [
            ['name' => 'Jean Dupont', 'email' => 'jean@telemed.ma', 'age' => 45],
            ['name' => 'Marie Curie', 'email' => 'marie@telemed.ma', 'age' => 32],
            ['name' => 'Ahmed Benali', 'email' => 'ahmed@telemed.ma', 'age' => 28],
        ];

        foreach ($patients as $index => $pat) {
            $user = User::create([
                'name' => $pat['name'],
                'email' => $pat['email'],
                'password' => Hash::make('patient123'),
                'role' => 'patient',
                'avatar' => 'https://i.pravatar.cc/150?img=' . ($index + 30),
            ]);

            PatientProfile::create([
                'user_id' => $user->id,
                'age' => $pat['age'],
            ]);
        }
    }
}
