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
        // ─── ADMIN ───────────────────────────────────────────────
        User::create([
            'name'     => 'Admin TeleMed',
            'email'    => 'admin@telemed.ma',
            'password' => Hash::make('admin123'),
            'role'     => 'admin',
            'phone'    => '+212 600-000000',
        ]);

        // ─── DOCTORS (18) ────────────────────────────────────────
        $doctors = [
            // — Médecine Générale (4) —
            [
                'name' => 'Dr. Sarah Bennani', 'email' => 'sarah@telemed.ma',
                'specialty' => 'Médecine Générale', 'exp' => 8, 'fee' => 200.00,
                'city' => 'Casablanca', 'address' => '45 Bd Zerktouni, Casablanca',
                'verified' => true, 'rating' => 4.8, 'total_reviews' => 12,
                'bio' => 'Médecin généraliste avec 8 ans d\'expérience. Spécialisée dans la médecine préventive et le suivi des maladies chroniques. Ancienne interne au CHU Ibn Rochd.',
                'avatar_idx' => 1,
            ],
            [
                'name' => 'Dr. Amine Fassi', 'email' => 'amine@telemed.ma',
                'specialty' => 'Médecine Générale', 'exp' => 12, 'fee' => 180.00,
                'city' => 'Rabat', 'address' => '12 Av. Mohammed V, Rabat',
                'verified' => true, 'rating' => 4.6, 'total_reviews' => 8,
                'bio' => '12 ans de pratique en médecine générale. Consultant au Centre de Santé Agdal. Approche centrée sur le patient et la médecine familiale.',
                'avatar_idx' => 2,
            ],
            [
                'name' => 'Dr. Nadia Chakir', 'email' => 'nadia@telemed.ma',
                'specialty' => 'Médecine Générale', 'exp' => 5, 'fee' => 150.00,
                'city' => 'Kénitra', 'address' => '8 Rue Ibn Batouta, Kénitra',
                'verified' => true, 'rating' => 4.5, 'total_reviews' => 5,
                'bio' => 'Jeune médecin généraliste passionnée par la télémédecine et l\'accès aux soins en milieu rural. Diplômée de la Faculté de Médecine de Rabat.',
                'avatar_idx' => 3,
            ],
            [
                'name' => 'Dr. Hassan El Idrissi', 'email' => 'hassan@telemed.ma',
                'specialty' => 'Médecine Générale', 'exp' => 20, 'fee' => 250.00,
                'city' => 'Meknès', 'address' => '33 Av. des FAR, Meknès',
                'verified' => false, 'rating' => 0.00, 'total_reviews' => 0,
                'bio' => '20 ans d\'expérience en médecine générale et médecine d\'urgence. Ancien médecin-chef au Centre Hospitalier Provincial de Meknès.',
                'avatar_idx' => 4,
            ],

            // — Cardiologie (2) —
            [
                'name' => 'Dr. Youssef El Amrani', 'email' => 'youssef@telemed.ma',
                'specialty' => 'Cardiologie', 'exp' => 15, 'fee' => 400.00,
                'city' => 'Casablanca', 'address' => '120 Bd d\'Anfa, Casablanca',
                'verified' => true, 'rating' => 4.9, 'total_reviews' => 15,
                'bio' => '15 ans d\'expérience en cardiologie. Ancien chef de service au CHU Ibn Rochd. Spécialisé dans l\'hypertension et les maladies coronariennes.',
                'avatar_idx' => 5,
            ],
            [
                'name' => 'Dr. Rachid Alaoui', 'email' => 'rachid@telemed.ma',
                'specialty' => 'Cardiologie', 'exp' => 10, 'fee' => 350.00,
                'city' => 'Fès', 'address' => '55 Av. Hassan II, Fès',
                'verified' => true, 'rating' => 4.7, 'total_reviews' => 9,
                'bio' => 'Cardiologue spécialiste en rythmologie et insuffisance cardiaque. Formé à l\'Université de Montpellier. Consultant au CHU Hassan II de Fès.',
                'avatar_idx' => 6,
            ],

            // — Dermatologie (2) —
            [
                'name' => 'Dr. Salma Berrada', 'email' => 'salma@telemed.ma',
                'specialty' => 'Dermatologie', 'exp' => 9, 'fee' => 300.00,
                'city' => 'Marrakech', 'address' => '18 Rue Yougoslavie, Guéliz, Marrakech',
                'verified' => true, 'rating' => 4.8, 'total_reviews' => 11,
                'bio' => 'Dermatologue spécialisée en dermatologie esthétique et allergologie cutanée. 9 ans d\'expérience. Cabinet privé à Guéliz.',
                'avatar_idx' => 7,
            ],
            [
                'name' => 'Dr. Omar Tazi', 'email' => 'omar@telemed.ma',
                'specialty' => 'Dermatologie', 'exp' => 14, 'fee' => 320.00,
                'city' => 'Tanger', 'address' => '7 Av. Mohammed VI, Tanger',
                'verified' => true, 'rating' => 4.6, 'total_reviews' => 7,
                'bio' => 'Dermatologue avec 14 ans d\'expérience. Spécialiste en dermatoscopie et traitement du psoriasis. Ancien chef de clinique au CHU de Tanger.',
                'avatar_idx' => 8,
            ],

            // — Pédiatrie (2) —
            [
                'name' => 'Dr. Khadija Bennani', 'email' => 'khadija@telemed.ma',
                'specialty' => 'Pédiatrie', 'exp' => 11, 'fee' => 250.00,
                'city' => 'Rabat', 'address' => '22 Rue Oued Fès, Agdal, Rabat',
                'verified' => true, 'rating' => 4.9, 'total_reviews' => 14,
                'bio' => 'Pédiatre passionnée avec 11 ans d\'expérience. Spécialisée en néonatologie et suivi du développement de l\'enfant. Consultante à l\'Hôpital d\'Enfants de Rabat.',
                'avatar_idx' => 9,
            ],
            [
                'name' => 'Dr. Mehdi Sqalli', 'email' => 'mehdi@telemed.ma',
                'specialty' => 'Pédiatrie', 'exp' => 7, 'fee' => 220.00,
                'city' => 'Fès', 'address' => '40 Bd Allal Ben Abdallah, Fès',
                'verified' => true, 'rating' => 4.5, 'total_reviews' => 6,
                'bio' => 'Pédiatre généraliste spécialisé en allergologie pédiatrique. Diplômé de la Faculté de Médecine de Fès. 7 ans d\'expérience en cabinet et milieu hospitalier.',
                'avatar_idx' => 10,
            ],

            // — Gynécologie (2) —
            [
                'name' => 'Dr. Imane Ziani', 'email' => 'imane@telemed.ma',
                'specialty' => 'Gynécologie', 'exp' => 13, 'fee' => 350.00,
                'city' => 'Casablanca', 'address' => '90 Bd Moulay Youssef, Casablanca',
                'verified' => true, 'rating' => 4.7, 'total_reviews' => 10,
                'bio' => 'Gynécologue-obstétricienne avec 13 ans d\'expérience. Spécialisée en grossesse à haut risque et fertilité. Ancienne résidente au CHU de Casablanca.',
                'avatar_idx' => 11,
            ],
            [
                'name' => 'Dr. Laila Mouline', 'email' => 'laila@telemed.ma',
                'specialty' => 'Gynécologie', 'exp' => 6, 'fee' => 300.00,
                'city' => 'Agadir', 'address' => '15 Av. du 29 Février, Agadir',
                'verified' => false, 'rating' => 0.00, 'total_reviews' => 0,
                'bio' => 'Gynécologue diplômée de la Faculté de Médecine de Marrakech. Intérêt particulier pour la santé reproductive et le suivi prénatal.',
                'avatar_idx' => 12,
            ],

            // — Ophtalmologie (1) —
            [
                'name' => 'Dr. Karim Mansouri', 'email' => 'karim@telemed.ma',
                'specialty' => 'Ophtalmologie', 'exp' => 16, 'fee' => 350.00,
                'city' => 'Marrakech', 'address' => '5 Rue de la Liberté, Guéliz, Marrakech',
                'verified' => true, 'rating' => 4.8, 'total_reviews' => 10,
                'bio' => '16 ans d\'expérience en ophtalmologie. Spécialiste en chirurgie réfractive et traitement du glaucome. Formé à l\'Hôpital des Quinze-Vingts à Paris.',
                'avatar_idx' => 13,
            ],

            // — Psychiatrie (1) —
            [
                'name' => 'Dr. Fatima Benali', 'email' => 'fatima@telemed.ma',
                'specialty' => 'Psychiatrie', 'exp' => 10, 'fee' => 400.00,
                'city' => 'Rabat', 'address' => '60 Av. Ibn Sina, Agdal, Rabat',
                'verified' => true, 'rating' => 4.9, 'total_reviews' => 8,
                'bio' => 'Psychiatre spécialisée en troubles anxieux et dépressifs. 10 ans d\'expérience. Formée en thérapie cognitivo-comportementale à l\'Université de Bordeaux.',
                'avatar_idx' => 14,
            ],

            // — Neurologie (1) —
            [
                'name' => 'Dr. Mourad Kettani', 'email' => 'mourad@telemed.ma',
                'specialty' => 'Neurologie', 'exp' => 18, 'fee' => 450.00,
                'city' => 'Casablanca', 'address' => '200 Bd Zerktouni, Casablanca',
                'verified' => true, 'rating' => 4.7, 'total_reviews' => 7,
                'bio' => 'Neurologue avec 18 ans d\'expérience. Spécialiste en épilepsie et maladies neurodégénératives. Ancien professeur agrégé à la Faculté de Médecine de Casablanca.',
                'avatar_idx' => 15,
            ],

            // — ORL (1) —
            [
                'name' => 'Dr. Zineb Chraibi', 'email' => 'zineb@telemed.ma',
                'specialty' => 'ORL', 'exp' => 8, 'fee' => 280.00,
                'city' => 'Oujda', 'address' => '25 Bd Mohammed Derfoufi, Oujda',
                'verified' => true, 'rating' => 4.4, 'total_reviews' => 4,
                'bio' => 'ORL spécialisée en audiologie et chirurgie des sinus. 8 ans d\'expérience. Diplômée de la Faculté de Médecine d\'Oujda.',
                'avatar_idx' => 16,
            ],

            // — Orthopédie (1) —
            [
                'name' => 'Dr. Adil Benmoussa', 'email' => 'adil@telemed.ma',
                'specialty' => 'Orthopédie', 'exp' => 11, 'fee' => 380.00,
                'city' => 'Tétouan', 'address' => '10 Av. des Almohades, Tétouan',
                'verified' => false, 'rating' => 0.00, 'total_reviews' => 0,
                'bio' => 'Chirurgien orthopédiste spécialisé en traumatologie sportive et chirurgie du genou. 11 ans d\'expérience entre le Maroc et la France.',
                'avatar_idx' => 17,
            ],
        ];

        foreach ($doctors as $index => $doc) {
            $user = User::create([
                'name'     => $doc['name'],
                'email'    => $doc['email'],
                'password' => Hash::make('doctor123'),
                'role'     => 'doctor',
                'phone'    => '+212 6' . str_pad($index + 10, 2, '0', STR_PAD_LEFT) . '-' . str_pad(($index + 1) * 111111, 6, '0', STR_PAD_LEFT),
                'avatar'   => 'https://i.pravatar.cc/150?img=' . $doc['avatar_idx'],
            ]);

            DoctorProfile::create([
                'user_id'          => $user->id,
                'specialty'        => $doc['specialty'],
                'experience_years' => $doc['exp'],
                'bio'              => $doc['bio'],
                'consultation_fee' => $doc['fee'],
                'address'          => $doc['address'],
                'city'             => $doc['city'],
                'is_verified'      => $doc['verified'],
                'rating_average'   => $doc['rating'],
                'total_reviews'    => $doc['total_reviews'],
            ]);
        }

        $this->command->info('✓ ' . count($doctors) . ' médecins créés');

        // ─── PATIENTS (25) ───────────────────────────────────────
        $patients = [
            // Original demo account
            ['name' => 'Jean Dupont',            'email' => 'jean@telemed.ma',      'age' => 45, 'phone' => '+212 661-234567', 'medical_history' => 'Hypertension artérielle sous traitement depuis 2020. Allergie à la pénicilline.'],
            // New patients
            ['name' => 'Amina Lahlou',           'email' => 'amina@telemed.ma',     'age' => 34, 'phone' => '+212 662-345678', 'medical_history' => 'Asthme léger depuis l\'enfance. Pas d\'autres antécédents notables.'],
            ['name' => 'Yassine Boukili',        'email' => 'yassine@telemed.ma',   'age' => 28, 'phone' => '+212 663-456789', 'medical_history' => null],
            ['name' => 'Houda Mernissi',         'email' => 'houda@telemed.ma',     'age' => 52, 'phone' => '+212 664-567890', 'medical_history' => 'Diabète de type 2 diagnostiqué en 2018. Suivi régulier. Cholestérol élevé.'],
            ['name' => 'Karim Idrissi',          'email' => 'karimpatient@telemed.ma', 'age' => 41, 'phone' => '+212 665-678901', 'medical_history' => 'Hernie discale L4-L5 opérée en 2021. Suivi orthopédique.'],
            ['name' => 'Fatima-Zahra Benkirane', 'email' => 'fatimazahra@telemed.ma', 'age' => 29, 'phone' => '+212 666-789012', 'medical_history' => null],
            ['name' => 'Hamza Touzani',          'email' => 'hamza@telemed.ma',     'age' => 36, 'phone' => '+212 667-890123', 'medical_history' => 'Antécédent de fracture du tibia (2019). RAS sinon.'],
            ['name' => 'Sanaa Chraibi',          'email' => 'sanaa@telemed.ma',     'age' => 47, 'phone' => '+212 668-901234', 'medical_history' => 'Hypothyroïdie sous Levothyrox. Ménopause précoce.'],
            ['name' => 'Othmane Kettani',        'email' => 'othmane@telemed.ma',   'age' => 23, 'phone' => '+212 669-012345', 'medical_history' => null],
            ['name' => 'Rim Belhaj',             'email' => 'rim@telemed.ma',       'age' => 31, 'phone' => '+212 670-123456', 'medical_history' => 'Migraine chronique depuis 5 ans. Traitement par triptans.'],
            ['name' => 'Anas Filali',            'email' => 'anas@telemed.ma',      'age' => 55, 'phone' => '+212 671-234567', 'medical_history' => 'Insuffisance cardiaque légère. Pace-maker posé en 2022.'],
            ['name' => 'Nora Benjelloun',        'email' => 'nora@telemed.ma',      'age' => 39, 'phone' => '+212 672-345678', 'medical_history' => 'Eczéma atopique. Allergie aux acariens.'],
            ['name' => 'Mehdi Ouazzani',         'email' => 'mehdipatient@telemed.ma', 'age' => 62, 'phone' => '+212 673-456789', 'medical_history' => 'Arthrose des genoux. Prostatite chronique. HTA contrôlée.'],
            ['name' => 'Salma Kadiri',           'email' => 'salmak@telemed.ma',    'age' => 26, 'phone' => null, 'medical_history' => null],
            ['name' => 'Rachid Tahiri',          'email' => 'rachidt@telemed.ma',   'age' => 44, 'phone' => '+212 675-678901', 'medical_history' => 'Reflux gastro-œsophagien chronique. Tabagisme actif.'],
            ['name' => 'Leila Amrani',           'email' => 'leila@telemed.ma',     'age' => 33, 'phone' => '+212 676-789012', 'medical_history' => 'Grossesse en cours (7 mois). Diabète gestationnel.'],
            ['name' => 'Zakaria Bouazza',        'email' => 'zakaria@telemed.ma',   'age' => 20, 'phone' => '+212 677-890123', 'medical_history' => null],
            ['name' => 'Hajar Sentissi',         'email' => 'hajar@telemed.ma',     'age' => 58, 'phone' => '+212 678-901234', 'medical_history' => 'Glaucome chronique œil droit. Cataracte opérée œil gauche en 2023.'],
            ['name' => 'Mohamed Lagrini',        'email' => 'mohamed@telemed.ma',   'age' => 37, 'phone' => '+212 679-012345', 'medical_history' => 'Anxiété généralisée diagnostiquée en 2022. Sous paroxétine.'],
            ['name' => 'Ghita Bensouda',         'email' => 'ghita@telemed.ma',     'age' => 42, 'phone' => '+212 680-123456', 'medical_history' => 'Syndrome du canal carpien bilatéral. En attente de chirurgie.'],
            ['name' => 'Ayoub Regragui',         'email' => 'ayoub@telemed.ma',     'age' => 30, 'phone' => null, 'medical_history' => null],
            ['name' => 'Wiam Bakkali',           'email' => 'wiam@telemed.ma',      'age' => 25, 'phone' => '+212 682-345678', 'medical_history' => 'Acné sévère sous isotrétinoïne depuis 3 mois.'],
            ['name' => 'Driss Lahmidi',          'email' => 'driss@telemed.ma',     'age' => 65, 'phone' => '+212 683-456789', 'medical_history' => 'BPCO stade II. Ancien fumeur. Ostéoporose.'],
            ['name' => 'Ikram Fassi Fihri',      'email' => 'ikram@telemed.ma',     'age' => 38, 'phone' => '+212 684-567890', 'medical_history' => 'Endométriose diagnostiquée en 2020. Suivi gynécologique régulier.'],
            ['name' => 'Taha Benchekroun',       'email' => 'taha@telemed.ma',      'age' => 49, 'phone' => '+212 685-678901', 'medical_history' => 'Apnée du sommeil appareillée. Surpoids (IMC 31).'],
        ];

        foreach ($patients as $index => $pat) {
            $user = User::create([
                'name'     => $pat['name'],
                'email'    => $pat['email'],
                'password' => Hash::make('patient123'),
                'role'     => 'patient',
                'phone'    => $pat['phone'],
                'avatar'   => 'https://i.pravatar.cc/150?img=' . ($index + 30),
            ]);

            PatientProfile::create([
                'user_id'         => $user->id,
                'age'             => $pat['age'],
                'phone'           => $pat['phone'],
                'medical_history' => $pat['medical_history'],
            ]);
        }

        $this->command->info('✓ ' . count($patients) . ' patients créés');
        $this->command->info('✓ 1 admin créé');
    }
}
