# TeleMed - Plateforme de Consultation Medicale en Ligne

Plateforme web de teleconsultation medicale permettant aux patients de consulter un medecin a distance via appel video et de recevoir une ordonnance numerique.

**Projet de Fin d'Etudes (PFE)** - OFPPT, Filiere Developpement Digital Full Stack (2025/2026)

**Realise par :** Mohammed Ouahman & Zineb Aamir
**Encadrante :** Mlle Aalami Naima

---

## Table des matieres

- [Apercu du projet](#apercu-du-projet)
- [Stack technique](#stack-technique)
- [Architecture du projet](#architecture-du-projet)
- [Installation rapide (Docker)](#installation-rapide-docker)
- [Installation manuelle (sans Docker)](#installation-manuelle-sans-docker)
- [Ports et services](#ports-et-services)
- [Comptes de demonstration](#comptes-de-demonstration)
- [Base de donnees](#base-de-donnees)
- [Fonctionnalites](#fonctionnalites)
- [API Endpoints](#api-endpoints)
- [Consultation video](#consultation-video)
- [Donnees de demo (Seeders)](#donnees-de-demo-seeders)
- [Structure du projet](#structure-du-projet)
- [Deploiement en production](#deploiement-en-production)
- [Depannage](#depannage)

---

## Apercu du projet

TeleMed est une application web full stack qui offre 3 espaces utilisateurs :

| Espace | Description |
|--------|-------------|
| **Patient** | Recherche de medecins, prise de RDV, consultation video, telechargement d'ordonnances |
| **Medecin** | Gestion du profil et des disponibilites, calendrier des RDV, teleconsultation, redaction d'ordonnances |
| **Administrateur** | Validation des comptes medecins, statistiques, gestion des specialites |

---

## Stack technique

| Couche | Technologie | Version |
|--------|-------------|---------|
| **Frontend** | React + Vite | React 19, Vite 8 |
| **UI/CSS** | Tailwind CSS | v4 |
| **Backend** | Laravel (PHP) | Laravel 13, PHP 8.4 |
| **Base de donnees** | MySQL | 8.0 |
| **Authentification** | Laravel Sanctum (token-based) | v4 |
| **Appel video** | Jitsi Meet (iframe API) | Public instance |
| **PDF** | DomPDF (via barryvdh/laravel-dompdf) | v3 |
| **Containerisation** | Docker + Docker Compose | - |
| **Serveur web** | Nginx | Alpine |

---

## Architecture du projet

```
telemed-pfe/
|
|-- docker-compose.yml          # Orchestration de tous les services
|-- docker/
|   `-- nginx/
|       `-- default.conf        # Configuration Nginx
|
|-- telemed-backend/            # API Laravel
|   |-- app/
|   |   |-- Http/Controllers/  # Controleurs API
|   |   `-- Models/            # Modeles Eloquent
|   |-- database/
|   |   |-- migrations/        # Schema de la base de donnees
|   |   `-- seeders/           # Donnees de demonstration
|   |-- routes/api.php         # Routes API
|   |-- .env.docker            # Config pour Docker
|   `-- Dockerfile
|
`-- telemed-frontend/           # SPA React
    |-- src/
    |   |-- pages/             # Pages (patient, doctor, admin)
    |   |-- components/        # Composants reutilisables
    |   |-- services/          # Appels API (Axios)
    |   `-- App.jsx            # Routes React
    `-- package.json
```

---

## Installation rapide (Docker)

> **Prerequis :** [Docker](https://docs.docker.com/get-docker/) et [Docker Compose](https://docs.docker.com/compose/install/) installes sur votre machine.

### Etape 1 : Cloner le projet

```bash
git clone https://github.com/SimoOuworworworworwork/telemed-pfe.git
cd telemed-pfe
```

*(Remplacez l'URL par votre URL de depot GitHub)*

### Etape 2 : Configurer l'environnement backend

```bash
cp telemed-backend/.env.docker telemed-backend/.env
```

### Etape 3 : Lancer tous les services

```bash
docker compose up -d
```

Cela demarre 5 conteneurs :
- `telemed_db` - MySQL 8.0
- `telemed_backend` - PHP-FPM (Laravel)
- `telemed_nginx` - Serveur web Nginx
- `telemed_frontend` - Serveur de dev React/Vite
- `telemed_phpmyadmin` - Interface phpMyAdmin

### Etape 4 : Initialiser la base de donnees

Attendez ~30 secondes que MySQL soit pret, puis :

```bash
# Generer la cle d'application Laravel
docker exec telemed_backend php artisan key:generate

# Creer les tables et inserer les donnees de demo
docker exec telemed_backend php artisan migrate:fresh --seed
```

### Etape 5 : Corriger les permissions (si necessaire)

```bash
docker exec telemed_backend chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker exec telemed_backend chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

### Etape 6 : Ouvrir l'application

L'application est prete ! Ouvrez votre navigateur :

| Service | URL |
|---------|-----|
| **Application TeleMed** | http://localhost:5173 |
| **API Backend** | http://localhost:8000/api |
| **phpMyAdmin** | http://localhost:8888 |

---

## Installation manuelle (sans Docker)

> **Prerequis :** PHP 8.2+, Composer, Node.js 18+, npm, MySQL 8.0

### Backend

```bash
cd telemed-backend

# Installer les dependances PHP
composer install

# Configurer l'environnement
cp .env.example .env

# Modifier .env avec vos identifiants MySQL :
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=telemed_db
# DB_USERNAME=root
# DB_PASSWORD=votre_mot_de_passe

# Generer la cle
php artisan key:generate

# Creer les tables + donnees de demo
php artisan migrate:fresh --seed

# Lancer le serveur
php artisan serve --port=8000
```

### Frontend

```bash
cd telemed-frontend

# Installer les dependances
npm install

# Verifier que le fichier .env contient :
# VITE_API_URL=http://localhost:8000/api

# Lancer le serveur de developpement
npm run dev
```

---

## Ports et services

| Port | Service | Description |
|------|---------|-------------|
| `5173` | Frontend (Vite) | Interface utilisateur React |
| `8000` | Nginx + PHP-FPM | API REST Laravel |
| `8888` | phpMyAdmin | Administration de la base de donnees |
| `3306` | MySQL | Base de donnees (acces direct) |
| `9000` | PHP-FPM | (interne Docker, non expose) |

---

## Comptes de demonstration

Apres avoir execute `php artisan migrate:fresh --seed`, les comptes suivants sont disponibles :

### Comptes principaux (pour la demo)

| Role | Nom | Email | Mot de passe |
|------|-----|-------|-------------|
| **Patient** | Jean Dupont | `jean@telemed.ma` | `patient123` |
| **Medecin** | Dr. Sarah Bennani | `sarah@telemed.ma` | `doctor123` |
| **Medecin** | Dr. Karim Mansouri | `karim@telemed.ma` | `doctor123` |
| **Admin** | Admin TeleMed | `admin@telemed.ma` | `admin123` |

### Tous les medecins (18)

| Nom | Specialite | Ville | Email | Tarif | Statut |
|-----|-----------|-------|-------|-------|--------|
| Dr. Sarah Bennani | Medecine Generale | Casablanca | sarah@telemed.ma | 200 DH | Verifie |
| Dr. Amine Fassi | Medecine Generale | Rabat | amine@telemed.ma | 180 DH | Verifie |
| Dr. Nadia Chakir | Medecine Generale | Kenitra | nadia@telemed.ma | 150 DH | Verifie |
| Dr. Hassan El Idrissi | Medecine Generale | Meknes | hassan@telemed.ma | 250 DH | En attente |
| Dr. Youssef El Amrani | Cardiologie | Casablanca | youssef@telemed.ma | 400 DH | Verifie |
| Dr. Rachid Alaoui | Cardiologie | Fes | rachid@telemed.ma | 350 DH | Verifie |
| Dr. Salma Berrada | Dermatologie | Marrakech | salma@telemed.ma | 300 DH | Verifie |
| Dr. Omar Tazi | Dermatologie | Tanger | omar@telemed.ma | 320 DH | Verifie |
| Dr. Khadija Bennani | Pediatrie | Rabat | khadija@telemed.ma | 250 DH | Verifie |
| Dr. Mehdi Sqalli | Pediatrie | Fes | mehdi@telemed.ma | 220 DH | Verifie |
| Dr. Imane Ziani | Gynecologie | Casablanca | imane@telemed.ma | 350 DH | Verifie |
| Dr. Laila Mouline | Gynecologie | Agadir | laila@telemed.ma | 300 DH | En attente |
| Dr. Karim Mansouri | Ophtalmologie | Marrakech | karim@telemed.ma | 350 DH | Verifie |
| Dr. Fatima Benali | Psychiatrie | Rabat | fatima@telemed.ma | 400 DH | Verifie |
| Dr. Mourad Kettani | Neurologie | Casablanca | mourad@telemed.ma | 450 DH | Verifie |
| Dr. Zineb Chraibi | ORL | Oujda | zineb@telemed.ma | 280 DH | Verifie |
| Dr. Adil Benmoussa | Orthopedie | Tetouan | adil@telemed.ma | 380 DH | En attente |

**Mot de passe pour tous les medecins :** `doctor123`

### Patients (25)

25 patients avec des noms marocains realistes, ages entre 20 et 65 ans, repartis sur plusieurs villes.

**Mot de passe pour tous les patients :** `patient123`

---

## Base de donnees

### Acces MySQL

| Methode | Details |
|---------|---------|
| **phpMyAdmin** | http://localhost:8888 |
| **Terminal** | `docker exec -it telemed_db mysql -u telemed -ptelemed123 telemed_db` |
| **Client externe** | Host: `127.0.0.1`, Port: `3306`, User: `telemed`, Password: `telemed123` |
| **Compte root** | User: `root`, Password: `root_secret` |

### Schema de la base de donnees

```
users                    # Tous les utilisateurs (patients, medecins, admin)
  |-- doctor_profiles    # Profil etendu du medecin (specialite, tarif, bio...)
  |-- patient_profiles   # Profil etendu du patient (age, historique medical)
  |-- doctor_availabilities  # Creneaux de disponibilite hebdomadaires
  |
  |-- appointments       # Rendez-vous (patient_id + doctor_id)
  |     |-- consultations    # Notes et diagnostic post-consultation
  |     |     `-- prescriptions  # Ordonnances (medicaments en JSON)
  |     `-- reviews          # Avis et notes des patients
  |
  `-- specialties        # Table de reference des specialites
```

### Donnees seedees

| Table | Nombre d'enregistrements |
|-------|--------------------------|
| Utilisateurs | 43 (1 admin + 17 medecins + 25 patients) |
| Profils medecin | 17 (14 verifies, 3 en attente) |
| Specialites | 11 |
| Disponibilites | 141 creneaux |
| Rendez-vous | 50 (27 termines, 12 confirmes, 7 en attente, 4 annules) |
| Consultations | 27 |
| Ordonnances | 25 |
| Avis patients | 20 |

---

## Fonctionnalites

### Espace Patient
- Inscription / Connexion securisee
- Recherche de medecins par specialite
- Consultation des profils medecins (bio, avis, tarifs)
- Prise de rendez-vous selon les disponibilites du medecin
- Consultation video en temps reel (Jitsi Meet)
- Telechargement d'ordonnances (PDF)
- Historique des consultations

### Espace Medecin
- Gestion du profil (specialite, tarifs, bio)
- Definition des disponibilites (jours et heures)
- Calendrier des rendez-vous a venir
- Confirmation / annulation des rendez-vous
- Teleconsultation video avec le patient
- Redaction de diagnostic et ordonnance numerique
- Historique des patients

### Espace Administrateur
- Tableau de bord avec statistiques globales
- Validation des comptes medecins (verification manuelle)
- Gestion des specialites medicales
- Liste de tous les utilisateurs

---

## API Endpoints

### Authentification
| Methode | Endpoint | Description |
|---------|----------|-------------|
| POST | `/api/register` | Inscription |
| POST | `/api/login` | Connexion |
| POST | `/api/logout` | Deconnexion |
| GET | `/api/user` | Profil utilisateur connecte |

### Patient
| Methode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/doctors` | Liste des medecins verifies |
| GET | `/api/doctors/{id}` | Profil d'un medecin |
| GET | `/api/doctors/{id}/availabilities` | Disponibilites d'un medecin |
| GET | `/api/appointments` | Mes rendez-vous |
| POST | `/api/appointments` | Prendre un rendez-vous |
| PUT | `/api/appointments/{id}/cancel` | Annuler un rendez-vous |

### Medecin
| Methode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/doctor/appointments` | Mes rendez-vous (medecin) |
| PUT | `/api/doctor/appointments/{id}/confirm` | Confirmer un RDV |
| PUT | `/api/doctor/appointments/{id}/complete` | Terminer un RDV |
| POST | `/api/consultations/{id}/prescription` | Creer ordonnance |

### Admin
| Methode | Endpoint | Description |
|---------|----------|-------------|
| GET | `/api/admin/stats` | Statistiques globales |
| GET | `/api/admin/users` | Liste des utilisateurs |
| GET | `/api/admin/doctors/pending` | Medecins en attente |
| PUT | `/api/admin/doctors/{id}/verify` | Verifier un medecin |

---

## Consultation video

La teleconsultation utilise **Jitsi Meet** (service gratuit et open source) via une integration iframe.

### Comment ca marche

1. Le patient prend un rendez-vous --> un `video_room_id` unique est genere
2. Le medecin confirme le rendez-vous
3. Le jour J, les deux parties cliquent sur "Rejoindre la consultation"
4. Une salle Jitsi Meet s'ouvre avec video, audio et partage d'ecran
5. Apres la consultation, le medecin redige le diagnostic et l'ordonnance

### Remarques pour la production

Pour un deploiement en production, il est recommande de :
- Utiliser un serveur Jitsi auto-heberge (gratuit) pour la confidentialite
- Ou migrer vers un service HIPAA-compliant (Daily.co, Twilio Video)
- Ajouter une authentification par token pour securiser les salles video

---

## Structure du projet

### Backend (Laravel)

```
telemed-backend/
|-- app/
|   |-- Http/
|   |   `-- Controllers/
|   |       |-- AuthController.php          # Login, Register, Logout
|   |       |-- AppointmentController.php   # CRUD Rendez-vous
|   |       |-- DoctorController.php        # Profils medecins
|   |       |-- ConsultationController.php  # Consultations + ordonnances
|   |       `-- AdminController.php         # Back-office admin
|   `-- Models/
|       |-- User.php
|       |-- DoctorProfile.php
|       |-- PatientProfile.php
|       |-- Appointment.php
|       |-- Consultation.php
|       |-- Prescription.php
|       |-- Review.php
|       |-- Specialty.php
|       `-- DoctorAvailability.php
|-- database/
|   |-- migrations/          # 12 fichiers de migration
|   `-- seeders/
|       |-- DatabaseSeeder.php
|       |-- SpecialtySeeder.php
|       |-- UserSeeder.php
|       |-- AvailabilitySeeder.php
|       `-- AppointmentSeeder.php
`-- routes/
    `-- api.php              # Toutes les routes API
```

### Frontend (React)

```
telemed-frontend/
`-- src/
    |-- pages/
    |   |-- LoginPage.jsx
    |   |-- RegisterPage.jsx
    |   |-- VideoCallPage.jsx
    |   |-- patient/
    |   |   |-- DoctorSearch.jsx
    |   |   |-- DoctorProfile.jsx
    |   |   `-- MyAppointments.jsx
    |   |-- doctor/
    |   |   |-- DoctorDashboard.jsx
    |   |   `-- DoctorAvailability.jsx
    |   `-- admin/
    |       `-- AdminDashboard.jsx
    |-- components/           # Composants reutilisables
    |-- services/             # Couche API (Axios)
    `-- App.jsx               # Routage principal
```

---

## Deploiement en production

### Option 1 : VPS (recommandee)

Deployer sur un VPS (DigitalOcean, Hetzner, OVH) avec Docker Compose :

```bash
# Sur le serveur
git clone <votre-repo> && cd telemed-pfe
cp telemed-backend/.env.docker telemed-backend/.env
# Modifier APP_URL, FRONTEND_URL, SANCTUM_STATEFUL_DOMAINS avec votre domaine
docker compose up -d
docker exec telemed_backend php artisan key:generate
docker exec telemed_backend php artisan migrate --seed
```

### Option 2 : Services separes

| Service | Plateforme | Cout |
|---------|-----------|------|
| Backend API | Railway, Render | Gratuit (tier free) |
| Frontend | Vercel, Netlify | Gratuit |
| Base de donnees | PlanetScale, Railway | Gratuit (tier free) |

---

## Depannage

### Les conteneurs ne demarrent pas

```bash
# Verifier l'etat des conteneurs
docker compose ps

# Voir les logs
docker compose logs backend
docker compose logs frontend
docker compose logs db
```

### Erreur "Connection refused" (base de donnees)

Attendez que MySQL soit completement demarre :

```bash
# Verifier que MySQL est healthy
docker compose ps db
# Doit afficher : (healthy)
```

### Erreur 500 "tempnam()"

```bash
docker exec telemed_backend chmod -R 775 /var/www/storage /var/www/bootstrap/cache
docker exec telemed_backend chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
```

### Reinitialiser completement la base de donnees

```bash
docker exec telemed_backend php artisan migrate:fresh --seed
```

### Arreter tous les services

```bash
docker compose down        # Arreter les conteneurs (garde les donnees)
docker compose down -v     # Arreter et supprimer les volumes (reset total)
```

---

## Commandes utiles

```bash
# Demarrer le projet
docker compose up -d

# Arreter le projet
docker compose down

# Voir les logs en temps reel
docker compose logs -f

# Acceder au terminal du backend
docker exec -it telemed_backend bash

# Acceder a MySQL
docker exec -it telemed_db mysql -u telemed -ptelemed123 telemed_db

# Re-seeder la base de donnees
docker exec telemed_backend php artisan migrate:fresh --seed

# Vider le cache Laravel
docker exec telemed_backend php artisan cache:clear
docker exec telemed_backend php artisan config:clear
```

---

## Licence

Projet academique realise dans le cadre du PFE - OFPPT (2025/2026).

---

*Developpe avec Laravel, React et Docker*
