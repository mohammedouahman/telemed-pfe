# 🎬 Script de Démonstration (Soutenance PFE)

> **Durée estimée :** 10-15 minutes. Ce script couvre l'intégralité du workflow de la plateforme TeleMed tel qu'il doit être présenté devant le jury.

## 1. Introduction (Page d'accueil)
**Action :** Ouvrir `http://localhost:5173`.
**Discours :** "Voici TeleMed. Nous avons conçu une interface moderne, intuitive et rassurante pour simplifier l'accès aux soins de santé."
_Montrer la présentation premium, les statistiques de confiance, et les avantages._

## 2. Parcours Patient : Recherche et Réservation
**Action :** Se connecter en tant que Patient (`jean@telemed.ma` / `patient123`).
**Discours :** "Je suis maintenant connecté en tant que Jean, un patient. Je cherche un rendez-vous rapide avec un spécialiste."
1. Aller sur la barre de recherche : Filtrer par spécialité "Généraliste".
2. Cliquer sur la vignette du **Dr. Sarah Bernard**.
3. Montrer la page profil du docteur : Biographie, Tarifs, Avis, et **Créneaux Disponibles**.
4. Sélectionner une date (Aujourd'hui ou Demain) et une heure précise.
5. Cliquer sur "Confirmer la réservation".
6. Une notification apparaît. Le rendez-vous s'affiche dans "Mes Rendez-vous" avec le badge **"En attente"**.

## 3. Parcours Médecin : Approbation et Téléconsultation
**Action :** Ouvrir un nouvel onglet Chrome (ou navigation privée) et se connecter en tant que Médecin (`sarah@telemed.ma` / `doctor123`).
**Discours :** "Basculons côté praticien. Le Dr. Sarah Bernard se connecte à son tableau de bord."
1. Arriver sur le Dashboard. Noter les statistiques dynamiques (Revenus, Consultations).
2. Dans la liste "Agenda d'aujourd'hui", on voit la demande de Jean Dupont.
3. Cliquer sur **"Accepter"**. Le statut passe en vert ("Confirmé"). Au même moment, côté Patient (onglet 1), le bouton "Rejoindre" la visio apparaît.
4. Le Médecin clique sur **"Démarrer"** la vidéo. (Autoriser caméra/micro Jitsi).
5. Le Patient (onglet 1) clique aussi sur **"Rejoindre"**.
_Démontrer que les deux parties se retrouvent dans la salle virtuelle._

## 4. Parcours Médecin : Ordonnance Numérique
**Action :** Toujours sur le compte Médecin, quitter la vidéo et cliquer sur "Notes" à côté du rendez-vous.
**Discours :** "La consultation est terminée, le médecin rédige maintenant son rapport sécurisé."
1. Remplir le diagnostic : "Angine rouge".
2. Remplir les notes internes.
3. Ajouter un médicament : "Amoxicilline 1g" / "1 comp" / "Matin et Soir" / "6 jours".
4. Cliquer sur "Terminer et Envoyer l'Ordonnance".

## 5. Parcours Patient : Réception du Document
**Action :** Retourner sur le compte de Jean (Patient).
**Discours :** "Jean constate que la consultation est marquée 'Terminée'."
1. Dans "Mes Rendez-vous", le bouton "Ordonnance" est apparu.
2. Cliquer dessus. Un fichier PDF est généré et se télécharge instantanément.
3. Ouvrir le PDF devant le jury pour montrer la **mise en page clinique** générée par DOMPDF.

## 6. Parcours Administrateur : Validation
**Action :** Se déconnecter d'un des comptes et se connecter en Admin (`admin@telemed.ma` / `admin123`).
**Discours :** "Pour garantir la sécurité de la plateforme, un système de vérification des profils médecins est en place."
1. Observer le Tableau de Bord (chiffres globaux et l'alerte "Médecins en Attente").
2. Descendre dans la liste de KYC : on voit "Karim Mansouri", inscrit mais non validé.
3. Cliquer sur "Approuver le profil".
4. Démontrer (ou expliquer) que le Dr Karim Mansouri est maintenant référencé sur la page d'accueil des patients.

## 7. Conclusion
_Remercier le jury, inviter aux questions._
