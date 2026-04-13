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

        // Different schedule patterns for variety
        $schedulePatterns = [
            // Pattern A: Mon-Fri full day
            [
                'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'morning' => ['09:00:00', '12:00:00'],
                'afternoon' => ['14:00:00', '17:00:00'],
            ],
            // Pattern B: Mon-Fri + Saturday morning
            [
                'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
                'morning' => ['08:30:00', '12:30:00'],
                'afternoon' => ['14:30:00', '17:30:00'], // no afternoon on saturday
            ],
            // Pattern C: Mon-Thu full, Fri morning only
            [
                'days' => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
                'morning' => ['09:00:00', '12:00:00'],
                'afternoon' => ['15:00:00', '18:00:00'], // no afternoon on friday
            ],
            // Pattern D: Tue-Sat
            [
                'days' => ['tuesday', 'wednesday', 'thursday', 'friday', 'saturday'],
                'morning' => ['09:00:00', '13:00:00'],
                'afternoon' => ['14:00:00', '17:00:00'],
            ],
        ];

        $count = 0;

        foreach ($verifiedDoctors as $index => $doc) {
            $pattern = $schedulePatterns[$index % count($schedulePatterns)];

            foreach ($pattern['days'] as $dayIndex => $day) {
                // Morning slot for all days
                DoctorAvailability::create([
                    'doctor_id'            => $doc->user_id,
                    'day_of_week'          => $day,
                    'start_time'           => $pattern['morning'][0],
                    'end_time'             => $pattern['morning'][1],
                    'slot_duration_minutes' => 30,
                ]);
                $count++;

                // Afternoon slot — skip for Saturday in pattern B, skip Friday in pattern C
                $skipAfternoon = false;
                if ($index % count($schedulePatterns) === 1 && $day === 'saturday') {
                    $skipAfternoon = true;
                }
                if ($index % count($schedulePatterns) === 2 && $day === 'friday') {
                    $skipAfternoon = true;
                }

                if (!$skipAfternoon) {
                    DoctorAvailability::create([
                        'doctor_id'            => $doc->user_id,
                        'day_of_week'          => $day,
                        'start_time'           => $pattern['afternoon'][0],
                        'end_time'             => $pattern['afternoon'][1],
                        'slot_duration_minutes' => 30,
                    ]);
                    $count++;
                }
            }
        }

        $this->command->info("✓ {$count} créneaux de disponibilité créés pour {$verifiedDoctors->count()} médecins vérifiés");
    }
}
