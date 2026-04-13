<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Consultation;
use App\Models\Prescription;
use App\Models\Review;
use App\Models\User;
use App\Models\DoctorProfile;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $patients = User::where('role', 'patient')->get();
        $verifiedDoctors = User::where('role', 'doctor')
            ->whereHas('doctorProfile', fn($q) => $q->where('is_verified', true))
            ->with('doctorProfile')
            ->get();

        if ($patients->isEmpty() || $verifiedDoctors->isEmpty()) {
            $this->command->warn('No patients or verified doctors found. Skipping appointments.');
            return;
        }

        $today = Carbon::today();
        $appointmentCount = 0;
        $consultationCount = 0;
        $prescriptionCount = 0;
        $reviewCount = 0;

        // Track booked slots to prevent double-booking: "doctorId-date-startTime"
        $bookedSlots = [];

        $bookSlot = function ($doctorId, $date, $startTime) use (&$bookedSlots) {
            $key = "{$doctorId}-{$date}-{$startTime}";
            if (isset($bookedSlots[$key])) {
                return false;
            }
            $bookedSlots[$key] = true;
            return true;
        };

        // ─── Time slots pool ─────────────────────────────────────
        $timeSlots = [
            ['09:00:00', '09:30:00'], ['09:30:00', '10:00:00'],
            ['10:00:00', '10:30:00'], ['10:30:00', '11:00:00'],
            ['11:00:00', '11:30:00'], ['11:30:00', '12:00:00'],
            ['14:00:00', '14:30:00'], ['14:30:00', '15:00:00'],
            ['15:00:00', '15:30:00'], ['15:30:00', '16:00:00'],
            ['16:00:00', '16:30:00'], ['16:30:00', '17:00:00'],
        ];

        // ─── Medical data pools ──────────────────────────────────
        $diagnoses = [
            'Hypertension artérielle légère. Tension mesurée à 14/9. Recommandation de régime hyposodé et activité physique régulière.',
            'Rhinopharyngite aiguë. Pas de complications. Repos et hydratation recommandés.',
            'Dermatite atopique modérée. Lésions au niveau des coudes et genoux. Peau très sèche.',
            'Consultation de suivi post-opératoire. Cicatrisation normale. Retrait des points dans 5 jours.',
            'Anxiété généralisée modérée. Mise en place d\'une thérapie cognitivo-comportementale.',
            'Bronchite aiguë d\'origine virale. Toux productive depuis 5 jours. Pas de surinfection bactérienne.',
            'Lombalgie chronique avec contracture musculaire paravertébrale. Pas de signe neurologique déficitaire.',
            'Gastrite chronique. Douleurs épigastriques post-prandiales. Helicobacter pylori à rechercher.',
            'Conjonctivite allergique bilatérale. Prurit oculaire intense. Chémosis modéré.',
            'Migraine sans aura. Crises bihebdomadaires depuis 2 mois. Retentissement sur la qualité de vie.',
            'Otite moyenne aiguë droite. Tympan bombé et érythémateux. Fièvre à 38.5°C.',
            'Suivi de grossesse — 5ème mois. Échographie normale. Prise de poids adéquate.',
            'Eczéma de contact professionnel. Lésions vésiculeuses aux mains. Suspicion d\'allergie au latex.',
            'Insomnie chronique d\'endormissement. Hygiène du sommeil déficiente. Écrans excessifs le soir.',
            'Entorse de la cheville droite grade II. Œdème modéré. Pas de fracture à la radiographie.',
        ];

        $doctorNotes = [
            'Patient coopératif. Bonne compréhension des consignes. Contrôle prévu dans 1 mois.',
            'Patiente anxieuse mais rassurée après explication. À revoir dans 2 semaines.',
            'Examen clinique sans particularité majeure. Bilan biologique demandé.',
            'Le patient a signalé des maux de tête récurrents depuis 3 semaines.',
            'Amélioration nette depuis la dernière consultation. Traitement à poursuivre.',
            'Première consultation. Dossier médical complet à constituer.',
            'Patient stable. Pas de modification du traitement nécessaire.',
            'Patiente en bon état général. Résultats d\'analyses précédentes satisfaisants.',
            'Contrôle de routine. Tous les paramètres dans les normes.',
            'Patient motivé pour le changement de mode de vie. Objectifs réalistes fixés.',
        ];

        $medicationSets = [
            [
                ['name' => 'Amlodipine 5mg', 'dosage' => '1 comprimé', 'frequency' => 'le matin', 'duration' => '30 jours'],
                ['name' => 'Hydrochlorothiazide 25mg', 'dosage' => '1 comprimé', 'frequency' => 'le matin', 'duration' => '30 jours'],
            ],
            [
                ['name' => 'Paracétamol 1g', 'dosage' => '1 comprimé', 'frequency' => 'toutes les 6h si douleur', 'duration' => '5 jours'],
                ['name' => 'Spray nasal Physiomer', 'dosage' => '2 pulvérisations', 'frequency' => '3 fois/jour', 'duration' => '7 jours'],
            ],
            [
                ['name' => 'Betamethasone crème 0.05%', 'dosage' => 'application locale', 'frequency' => '2 fois/jour', 'duration' => '10 jours'],
                ['name' => 'Dexeryl crème émolliente', 'dosage' => 'application généreuse', 'frequency' => '2 fois/jour', 'duration' => '30 jours'],
            ],
            [
                ['name' => 'Amoxicilline 1g', 'dosage' => '1 comprimé', 'frequency' => 'matin et soir', 'duration' => '7 jours'],
                ['name' => 'Ibuprofène 400mg', 'dosage' => '1 comprimé', 'frequency' => '3 fois/jour au repas', 'duration' => '5 jours'],
            ],
            [
                ['name' => 'Oméprazole 20mg', 'dosage' => '1 gélule', 'frequency' => 'avant le petit-déjeuner', 'duration' => '14 jours'],
                ['name' => 'Gaviscon suspension', 'dosage' => '1 sachet', 'frequency' => 'après chaque repas', 'duration' => '14 jours'],
            ],
            [
                ['name' => 'Ventoline 100µg', 'dosage' => '2 bouffées', 'frequency' => 'en cas de crise', 'duration' => '3 mois'],
            ],
            [
                ['name' => 'Paroxétine 20mg', 'dosage' => '1 comprimé', 'frequency' => 'le matin', 'duration' => '3 mois'],
                ['name' => 'Hydroxyzine 25mg', 'dosage' => '1 comprimé', 'frequency' => 'le soir au coucher', 'duration' => '15 jours'],
            ],
            [
                ['name' => 'Diclofénac gel 1%', 'dosage' => 'application locale', 'frequency' => '3 fois/jour', 'duration' => '10 jours'],
                ['name' => 'Thiocolchicoside 4mg', 'dosage' => '1 comprimé', 'frequency' => '2 fois/jour', 'duration' => '7 jours'],
            ],
            [
                ['name' => 'Cromoglicate de sodium collyre', 'dosage' => '1 goutte par œil', 'frequency' => '4 fois/jour', 'duration' => '14 jours'],
                ['name' => 'Desloratadine 5mg', 'dosage' => '1 comprimé', 'frequency' => 'le soir', 'duration' => '14 jours'],
            ],
            [
                ['name' => 'Sumatriptan 50mg', 'dosage' => '1 comprimé', 'frequency' => 'au début de la crise', 'duration' => '1 mois'],
                ['name' => 'Paracétamol 1g', 'dosage' => '1 comprimé', 'frequency' => 'toutes les 6h si douleur', 'duration' => '5 jours'],
            ],
        ];

        $recommendations = [
            'Régime hyposodé. Marche rapide 30 min/jour. Contrôle tensionnel hebdomadaire.',
            'Repos au lit 2-3 jours. Hydratation abondante (2L/jour). Éviter le froid.',
            'Éviter les savons agressifs. Utiliser uniquement des produits hypoallergéniques. Douche tiède.',
            'Garder la plaie propre et sèche. Éviter les efforts physiques pendant 2 semaines.',
            'Exercices de relaxation quotidiens. Limiter la caféine. Journal des émotions recommandé.',
            'Arrêter le tabac. Éviter les environnements pollués. Humidifier l\'air ambiant.',
            'Ergonomie du poste de travail. Exercices d\'étirement lombaire 2 fois/jour.',
            'Régime alimentaire anti-reflux. Éviter épices, agrumes, café. Ne pas se coucher après le repas.',
            'Éviter les allergènes connus. Porter des lunettes de soleil en extérieur.',
            'Tenir un journal des crises migraineuses. Identifier les déclencheurs. Dormir 7-8h/nuit.',
        ];

        $reviewComments = [
            'Très bon médecin, à l\'écoute et professionnel. Je recommande vivement.',
            'Consultation rapide mais efficace. Le diagnostic était précis.',
            'Docteur très compétent, explique bien le traitement et les effets secondaires.',
            'Un peu d\'attente mais la consultation valait le coup. Très satisfait.',
            'Excellente expérience de téléconsultation, presque comme en présentiel.',
            'Médecin très humain et rassurant. Prend le temps d\'écouter.',
            'Suivi rigoureux et disponibilité remarquable. Merci docteur !',
            'Très professionnel. La plateforme fonctionne très bien pour les consultations.',
            'Bon diagnostic, traitement efficace. Je reviendrai pour le suivi.',
            'Première téléconsultation et je suis agréablement surprise. Très pratique.',
            'Docteur attentif aux détails. Ordonnance claire et bien expliquée.',
            'Consultation complète malgré la distance. Très bonne expérience.',
            'Le docteur a pris le temps de répondre à toutes mes questions. Top.',
            'Rendez-vous ponctuel, consultation de qualité. Rien à redire.',
            'Bon médecin mais la connexion était un peu instable. Le reste était parfait.',
        ];

        // ═══════════════════════════════════════════════════════════
        // 1) COMPLETED APPOINTMENTS (12) — past 60 days
        // ═══════════════════════════════════════════════════════════
        for ($i = 0; $i < 12; $i++) {
            $doctor = $verifiedDoctors[$i % $verifiedDoctors->count()];
            // Spread patients: some return multiple times
            $patientIndex = $i < 8 ? $i % $patients->count() : ($i % 5); // first 8 unique-ish, then repeats
            $patient = $patients[$patientIndex];

            $daysAgo = rand(3, 55);
            $date = $today->copy()->subDays($daysAgo)->toDateString();
            $slot = $timeSlots[$i % count($timeSlots)];

            if (!$bookSlot($doctor->id, $date, $slot[0])) {
                $slot = $timeSlots[($i + 3) % count($timeSlots)];
                $date = $today->copy()->subDays($daysAgo + 1)->toDateString();
                $bookSlot($doctor->id, $date, $slot[0]);
            }

            $app = Appointment::create([
                'patient_id'       => $patient->id,
                'doctor_id'        => $doctor->id,
                'appointment_date' => $date,
                'start_time'       => $slot[0],
                'end_time'         => $slot[1],
                'status'           => 'completed',
                'notes'            => $i % 3 === 0 ? 'Consultation de suivi' : null,
                'video_room_id'    => 'telemed-' . Str::random(10),
            ]);
            $appointmentCount++;

            // Create consultation
            $appointmentDate = Carbon::parse($date);
            $startTime = Carbon::parse($slot[0]);
            $cons = Consultation::create([
                'appointment_id' => $app->id,
                'diagnosis'      => $diagnoses[$i % count($diagnoses)],
                'doctor_notes'   => $doctorNotes[$i % count($doctorNotes)],
                'started_at'     => $appointmentDate->copy()->setTime($startTime->hour, $startTime->minute),
                'ended_at'       => $appointmentDate->copy()->setTime($startTime->hour, $startTime->minute)->addMinutes(rand(18, 28)),
            ]);
            $consultationCount++;

            // Create prescription (for 10 of 12)
            if ($i < 10) {
                Prescription::create([
                    'consultation_id' => $cons->id,
                    'patient_id'      => $patient->id,
                    'doctor_id'       => $doctor->id,
                    'medications'     => $medicationSets[$i % count($medicationSets)],
                    'recommendations' => $recommendations[$i % count($recommendations)],
                    'issued_at'       => $appointmentDate->copy()->setTime($startTime->hour + 1, 0),
                ]);
                $prescriptionCount++;
            }

            // Create review (for 8 of 12 completed)
            if ($i < 8) {
                $rating = $i < 5 ? rand(4, 5) : rand(3, 5);
                Review::create([
                    'patient_id'     => $patient->id,
                    'doctor_id'      => $doctor->id,
                    'appointment_id' => $app->id,
                    'rating'         => $rating,
                    'comment'        => $reviewComments[$i % count($reviewComments)],
                ]);
                $reviewCount++;
            }
        }

        // ═══════════════════════════════════════════════════════════
        // 2) CONFIRMED APPOINTMENTS (10) — next 2 weeks
        // ═══════════════════════════════════════════════════════════
        for ($i = 0; $i < 10; $i++) {
            $doctor = $verifiedDoctors[($i + 3) % $verifiedDoctors->count()];
            $patient = $patients[($i + 2) % $patients->count()];

            $daysAhead = rand(1, 13);
            $date = $today->copy()->addDays($daysAhead)->toDateString();
            $slot = $timeSlots[($i + 5) % count($timeSlots)];

            if (!$bookSlot($doctor->id, $date, $slot[0])) {
                $slot = $timeSlots[($i + 7) % count($timeSlots)];
                $bookSlot($doctor->id, $date, $slot[0]);
            }

            Appointment::create([
                'patient_id'       => $patient->id,
                'doctor_id'        => $doctor->id,
                'appointment_date' => $date,
                'start_time'       => $slot[0],
                'end_time'         => $slot[1],
                'status'           => 'confirmed',
                'notes'            => $i % 4 === 0 ? 'Première consultation' : null,
                'video_room_id'    => 'telemed-' . Str::random(10),
            ]);
            $appointmentCount++;
        }

        // ═══════════════════════════════════════════════════════════
        // 3) PENDING APPOINTMENTS (6) — upcoming
        // ═══════════════════════════════════════════════════════════
        for ($i = 0; $i < 6; $i++) {
            $doctor = $verifiedDoctors[($i + 5) % $verifiedDoctors->count()];
            $patient = $patients[($i + 8) % $patients->count()];

            $daysAhead = rand(2, 10);
            $date = $today->copy()->addDays($daysAhead)->toDateString();
            $slot = $timeSlots[($i + 2) % count($timeSlots)];

            if (!$bookSlot($doctor->id, $date, $slot[0])) {
                $slot = $timeSlots[($i + 9) % count($timeSlots)];
                $bookSlot($doctor->id, $date, $slot[0]);
            }

            Appointment::create([
                'patient_id'       => $patient->id,
                'doctor_id'        => $doctor->id,
                'appointment_date' => $date,
                'start_time'       => $slot[0],
                'end_time'         => $slot[1],
                'status'           => 'pending',
                'notes'            => null,
                'video_room_id'    => 'telemed-' . Str::random(10),
            ]);
            $appointmentCount++;
        }

        // ═══════════════════════════════════════════════════════════
        // 4) CANCELLED APPOINTMENTS (4) — past
        // ═══════════════════════════════════════════════════════════
        $cancelNotes = [
            'Annulé par le patient — urgence personnelle',
            'Annulé par le médecin — indisponibilité de dernière minute',
            'Annulé par le patient — amélioration des symptômes',
            'Annulé par le médecin — urgence médicale',
        ];

        for ($i = 0; $i < 4; $i++) {
            $doctor = $verifiedDoctors[($i + 7) % $verifiedDoctors->count()];
            $patient = $patients[($i + 12) % $patients->count()];

            $daysAgo = rand(5, 30);
            $date = $today->copy()->subDays($daysAgo)->toDateString();
            $slot = $timeSlots[($i + 4) % count($timeSlots)];

            if (!$bookSlot($doctor->id, $date, $slot[0])) {
                $slot = $timeSlots[($i + 8) % count($timeSlots)];
                $bookSlot($doctor->id, $date, $slot[0]);
            }

            Appointment::create([
                'patient_id'       => $patient->id,
                'doctor_id'        => $doctor->id,
                'appointment_date' => $date,
                'start_time'       => $slot[0],
                'end_time'         => $slot[1],
                'status'           => 'cancelled',
                'notes'            => $cancelNotes[$i],
                'video_room_id'    => null,
            ]);
            $appointmentCount++;
        }

        // ═══════════════════════════════════════════════════════════
        // 5) TODAY'S APPOINTMENTS (3) — for live demo
        // ═══════════════════════════════════════════════════════════
        $todayStr = $today->toDateString();

        // Jean Dupont (demo patient) with Dr. Sarah Bennani (demo doctor) — confirmed, near current time
        $jeanUser = $patients->firstWhere('email', 'jean@telemed.ma') ?? $patients[0];
        $sarahUser = $verifiedDoctors->firstWhere('email', 'sarah@telemed.ma') ?? $verifiedDoctors[0];

        $currentHour = (int) now()->format('H');
        $nearSlot = $currentHour < 12
            ? ['10:00:00', '10:30:00']
            : ['15:00:00', '15:30:00'];

        if ($bookSlot($sarahUser->id, $todayStr, $nearSlot[0])) {
            Appointment::create([
                'patient_id'       => $jeanUser->id,
                'doctor_id'        => $sarahUser->id,
                'appointment_date' => $todayStr,
                'start_time'       => $nearSlot[0],
                'end_time'         => $nearSlot[1],
                'status'           => 'confirmed',
                'notes'            => 'Consultation de suivi — hypertension',
                'video_room_id'    => 'telemed-demo-' . Str::random(8),
            ]);
            $appointmentCount++;
        }

        // Second today appointment
        $doctor2 = $verifiedDoctors[4 % $verifiedDoctors->count()]; // Youssef (cardiologie)
        $patient2 = $patients[3]; // Houda
        $slot2 = ['11:00:00', '11:30:00'];
        if ($bookSlot($doctor2->id, $todayStr, $slot2[0])) {
            Appointment::create([
                'patient_id'       => $patient2->id,
                'doctor_id'        => $doctor2->id,
                'appointment_date' => $todayStr,
                'start_time'       => $slot2[0],
                'end_time'         => $slot2[1],
                'status'           => 'confirmed',
                'notes'            => 'Contrôle cardiologique trimestriel',
                'video_room_id'    => 'telemed-demo-' . Str::random(8),
            ]);
            $appointmentCount++;
        }

        // Third today appointment — pending
        $doctor3 = $verifiedDoctors[6 % $verifiedDoctors->count()];
        $patient3 = $patients[5];
        $slot3 = ['16:00:00', '16:30:00'];
        if ($bookSlot($doctor3->id, $todayStr, $slot3[0])) {
            Appointment::create([
                'patient_id'       => $patient3->id,
                'doctor_id'        => $doctor3->id,
                'appointment_date' => $todayStr,
                'start_time'       => $slot3[0],
                'end_time'         => $slot3[1],
                'status'           => 'pending',
                'notes'            => null,
                'video_room_id'    => 'telemed-demo-' . Str::random(8),
            ]);
            $appointmentCount++;
        }

        // ═══════════════════════════════════════════════════════════
        // 6) EXTRA COMPLETED (past) to reach ~50+ total & more reviews
        // ═══════════════════════════════════════════════════════════
        for ($i = 0; $i < 15; $i++) {
            $doctor = $verifiedDoctors[($i + 2) % $verifiedDoctors->count()];
            $patient = $patients[($i + 5) % $patients->count()];

            $daysAgo = rand(10, 58);
            $date = $today->copy()->subDays($daysAgo)->toDateString();
            $slot = $timeSlots[($i + 1) % count($timeSlots)];

            if (!$bookSlot($doctor->id, $date, $slot[0])) {
                $date = $today->copy()->subDays($daysAgo + 2)->toDateString();
                if (!$bookSlot($doctor->id, $date, $slot[0])) {
                    continue; // skip if still conflict
                }
            }

            $app = Appointment::create([
                'patient_id'       => $patient->id,
                'doctor_id'        => $doctor->id,
                'appointment_date' => $date,
                'start_time'       => $slot[0],
                'end_time'         => $slot[1],
                'status'           => 'completed',
                'notes'            => null,
                'video_room_id'    => 'telemed-' . Str::random(10),
            ]);
            $appointmentCount++;

            $appointmentDate = Carbon::parse($date);
            $startTime = Carbon::parse($slot[0]);

            $cons = Consultation::create([
                'appointment_id' => $app->id,
                'diagnosis'      => $diagnoses[($i + 5) % count($diagnoses)],
                'doctor_notes'   => $doctorNotes[($i + 3) % count($doctorNotes)],
                'started_at'     => $appointmentDate->copy()->setTime($startTime->hour, $startTime->minute),
                'ended_at'       => $appointmentDate->copy()->setTime($startTime->hour, $startTime->minute)->addMinutes(rand(15, 28)),
            ]);
            $consultationCount++;

            Prescription::create([
                'consultation_id' => $cons->id,
                'patient_id'      => $patient->id,
                'doctor_id'       => $doctor->id,
                'medications'     => $medicationSets[($i + 3) % count($medicationSets)],
                'recommendations' => $recommendations[($i + 2) % count($recommendations)],
                'issued_at'       => $appointmentDate->copy()->setTime($startTime->hour + 1, 0),
            ]);
            $prescriptionCount++;

            // Reviews for ~60% of these
            if ($i % 5 !== 0) {
                $rating = collect([4, 4, 4, 5, 5, 5, 5, 3, 4, 5])->random();
                Review::create([
                    'patient_id'     => $patient->id,
                    'doctor_id'      => $doctor->id,
                    'appointment_id' => $app->id,
                    'rating'         => $rating,
                    'comment'        => $reviewComments[($i + 7) % count($reviewComments)],
                ]);
                $reviewCount++;
            }
        }

        // ═══════════════════════════════════════════════════════════
        // Update doctor rating averages from actual review data
        // ═══════════════════════════════════════════════════════════
        foreach ($verifiedDoctors as $doctor) {
            $reviews = Review::where('doctor_id', $doctor->id);
            $count = $reviews->count();
            if ($count > 0) {
                $avg = round($reviews->avg('rating'), 2);
                DoctorProfile::where('user_id', $doctor->id)->update([
                    'rating_average' => $avg,
                    'total_reviews'  => $count,
                ]);
            }
        }

        // ═══════════════════════════════════════════════════════════
        // Summary
        // ═══════════════════════════════════════════════════════════
        $this->command->info("✓ {$appointmentCount} rendez-vous créés");
        $this->command->info("  ├─ " . Appointment::where('status', 'completed')->count() . " terminés");
        $this->command->info("  ├─ " . Appointment::where('status', 'confirmed')->count() . " confirmés");
        $this->command->info("  ├─ " . Appointment::where('status', 'pending')->count() . " en attente");
        $this->command->info("  └─ " . Appointment::where('status', 'cancelled')->count() . " annulés");
        $this->command->info("✓ {$consultationCount} consultations créées");
        $this->command->info("✓ {$prescriptionCount} ordonnances créées");
        $this->command->info("✓ {$reviewCount} avis patients créés");
    }
}
