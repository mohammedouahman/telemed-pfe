<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = User::where('role', 'patient')->get();
        $doctors = User::where('role', 'doctor')->whereHas('doctorProfile', function($query) {
            $query->where('is_verified', true);
        })->get();

        if ($patients->isEmpty() || $doctors->isEmpty()) return;

        // Pending
        Appointment::create([
            'patient_id' => $patients[0]->id,
            'doctor_id' => $doctors[0]->id,
            'appointment_date' => now()->addDays(2)->toDateString(),
            'start_time' => '09:00:00',
            'end_time' => '09:30:00',
            'status' => 'pending',
            'video_room_id' => 'telemed-' . Str::random(10),
        ]);

        // Confirmed (upcoming)
        Appointment::create([
            'patient_id' => $patients[1]->id,
            'doctor_id' => $doctors[1]->id,
            'appointment_date' => now()->addDays(1)->toDateString(),
            'start_time' => '14:30:00',
            'end_time' => '15:00:00',
            'status' => 'confirmed',
            'video_room_id' => 'telemed-' . Str::random(10),
        ]);

        // Cancelled
        Appointment::create([
            'patient_id' => $patients[2]->id,
            'doctor_id' => $doctors[0]->id,
            'appointment_date' => now()->addDays(3)->toDateString(),
            'start_time' => '10:00:00',
            'end_time' => '10:30:00',
            'status' => 'cancelled',
            'video_room_id' => 'telemed-' . Str::random(10),
        ]);

        $medications = [
            ["name" => "Paracétamol 1g", "dosage" => "1 comprimé", "frequency" => "toutes les 6h", "duration" => "5 jours"],
            ["name" => "Ibuprofène 400mg", "dosage" => "1 comprimé", "frequency" => "2 fois/jour", "duration" => "3 jours"]
        ];

        // 3 Completed appointments
        for ($i = 0; $i < 3; $i++) {
            $doctor = $doctors[$i % $doctors->count()];
            $patient = $patients[($i + 1) % $patients->count()];
            
            $app = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'appointment_date' => now()->subDays($i + 1)->toDateString(),
                'start_time' => '11:00:00',
                'end_time' => '11:30:00',
                'status' => 'completed',
                'video_room_id' => 'telemed-' . Str::random(10),
            ]);

            $cons = Consultation::create([
                'appointment_id' => $app->id,
                'diagnosis' => 'Infection virale légère. Repos conseillé.',
                'doctor_notes' => 'Le patient a signalé des maux de tête depuis 3 jours.',
                'started_at' => now()->subDays($i + 1)->setTime(11, 0),
                'ended_at' => now()->subDays($i + 1)->setTime(11, 25),
            ]);

            Prescription::create([
                'consultation_id' => $cons->id,
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id,
                'medications' => $medications,
                'recommendations' => 'Boire beaucoup d\'eau et se reposer.',
            ]);
        }
    }
}
