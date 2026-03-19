<?php

namespace Database\Seeders;

use App\Models\DoctorAvailability;
use App\Models\DoctorProfile;
use Illuminate\Database\Seeder;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $verifiedDoctors = DoctorProfile::where('is_verified', true)->get();
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];

        foreach ($verifiedDoctors as $doc) {
            foreach ($days as $day) {
                // Morning slot
                DoctorAvailability::create([
                    'doctor_id' => $doc->user_id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '12:00:00',
                    'slot_duration_minutes' => 30,
                ]);

                // Afternoon slot
                DoctorAvailability::create([
                    'doctor_id' => $doc->user_id,
                    'day_of_week' => $day,
                    'start_time' => '14:00:00',
                    'end_time' => '17:00:00',
                    'slot_duration_minutes' => 30,
                ]);
            }
        }
    }
}
