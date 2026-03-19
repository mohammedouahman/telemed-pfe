# 🏥 TeleMed - Projet de Fin d'Études (PFE)

**Développement Digital Full Stack - 2025/2026**
**Réalisé par :** Mohammed Ouahman & Zineb Aamir
**Encadré par :** Mlle Aalami Naima

## 🌟 Présentation
TeleMed est une plateforme web moderne de télémédecine permettant la mise en relation entre patients et médecins. Elle offre la recherche de praticiens, la prise de rendez-vous en temps réel, la téléconsultation vidéo intégrée, et la génération électronique sécurisée d'ordonnances médicales au format PDF.

## 🛠 Stack Technique
- **Frontend :** React.js (Vite), React Router v6, Tailwind CSS v4, Lucide React, Axios.
- **Backend :** Laravel 11, PHP 8.2+, Eloquent ORM, Laravel Sanctum (Authentification API).
- **Communication Vidéo :** API Jitsi Meet (Iframe native).
- **Base de Données :** MySQL.
- **Génération PDF :** `barryvdh/laravel-dompdf`.

## 🚀 Installation & Démarrage Rapide

### 1. Prérequis
- XAMPP/WAMP (ou MySQL natif actif sur le port 3306).
- Un utilisateur MySQL `telemed` avec le mot de passe `telemed123` et les droits sur la base `telemed_db`.
- PHP 8.2+ et Composer.
- Node.js v18+ et npm.

### 2. Backend (API Laravel)
```bash
cd telemed-backend
cp .env.example .env
# Configurez votre .env pour MySQL :
# DB_CONNECTION=mysql
# DB_USERNAME=telemed
# DB_PASSWORD=telemed123
# DB_DATABASE=telemed_db

composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```
> Le backend tourne sur `http://localhost:8000`.

### 3. Frontend (Application React)
```bash
cd telemed-frontend
npm install
npm run dev
```
> Le frontend tourne sur `http://localhost:5173`. L'API Axios est configurée pour pointer automatiquement vers le port 8000.

## 👥 Comptes de démonstration générés
Les seeders (`UserSeeder`) ont peuplé la base pour la soutenance :

- **Patient :**
  - Email : `jean@telemed.ma`
  - Mot de passe : `patient123`
- **Médecin (Généraliste, vérifié) :**
  - Email : `sarah@telemed.ma`
  - Mot de passe : `doctor123`
- **Médecin (Non vérifié, KYC en attente) :**
  - Email : `karim@telemed.ma`
  - Mot de passe : `doctor123`
- **Administrateur :**
  - Email : `admin@telemed.ma`
  - Mot de passe : `admin123`

## 🔒 Sécurité & Architecture
- Architecture RESTful stricte.
- Middlewares Laravel customisés (`role:patient|doctor|admin`).
- Composants React gardés par un système de routes privées (`ProtectedRoute`).
- Stockage de tokens Sanctum.

---
_Projet réalisé dans le cadre de la certification de fin de formation OFPPT._
