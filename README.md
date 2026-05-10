# Meeting Manager - Faculté Polydisciplinaire de Sidi Bennour (FPSB)

Une solution de gestion moderne, efficace et transparente pour l'organisation et le suivi des réunions académiques et administratives au sein de la Faculté Polydisciplinaire de Sidi Bennour.

## Présentation
Développé par **Rachid ELOUIZI**, ce système permet d'automatiser le cycle de vie complet des réunions, de la planification à la publication des comptes-rendus officiels. L'application adopte désormais un design **Premium & Moderne**, alliant la clarté technique d'Odoo à des finitions esthétiques contemporaines (Indigo, Glassmorphism, Animations fluides).

## Fonctionnalités Clés

### Gestion des Réunions & Planning
- **Planification Avancée** : Création de réunions standards ou élargies avec affichage dynamique des membres de l'instance en temps réel.
- **Système de Report** : Possibilité de reporter une réunion en un clic avec mise à jour automatique des participants.
- **Ordre du Jour Intelligent** : Gestion dynamique des points de discussion avec numérotation automatique et suivi.
- **Invitations Automatiques** : Envoi groupé d'invitations et gestion des réponses (Acceptation/Refus) avec messages personnalisés.

### Membres & Instances
- **Annuaire avec Recherche Instantanée** : CRUD complet des professeurs et fonctionnaires avec filtrage temps réel (JS).
- **Commissions Spécialisées** : Organisation structurée des membres par instances avec gestion des droits d'accès.
- **Raccourcis Intelligents** : Barre latérale optimisée avec accès direct aux Archives et aux Comptes-rendus.

### Système de Notifications
- **Alertes Multi-canaux** : Notifications internes (cloche) et emails pour les invitations et mises à jour.
- **Interaction Sociale** : Confirmation ou excuse des participants avec ajout de messages explicatifs.
- **Rappels Automatiques** : Relances intelligentes 24h avant chaque séance.

### Gestion des Comptes-rendus (PV)
- **Centre de Documents** : Page dédiée listant tous les comptes-rendus officiels avec téléchargement direct.
- **Traçabilité** : Archivage sécurisé des fichiers PDF/Word rattachés aux séances terminées.

## Design & UX Premium
L'application a été entièrement refondue pour offrir une expérience utilisateur haut de gamme :
- **Identité Visuelle** : Logo et Favicon personnalisés, palette de couleurs Indigo moderne.
- **Tableaux Élite** : Affichage épuré des réunions, effets de survol sur les lignes, et boutons d'action minimalistes.
- **Responsive & Fluide** : Utilisation d'animations de fondu (Fade-in) et de micro-interactions pour une navigation vivante.
- **Sidebar Optimisée** : Navigation persistante avec gestion du défilement et raccourcis d'accès rapide.

## Stack Technique
- **Backend** : Laravel 11 (PHP 8.2+)
- **Frontend** : Blade Templates, Vanilla CSS 3 (Custom Tokens), JavaScript (Real-time filtering)
- **Base de données** : MySQL
- **Icônes** : Lucide Icons
- **Notifications** : Laravel Database & Mail Notifications

## Installation

1. Cloner le repository :
```bash
git clone https://github.com/rachidoverflow/univ-event-manager.git
```

2. Installer les dépendances :
```bash
composer install
npm install
```

3. Configurer l'environnement :
   - Dupliquer `.env.example` en `.env`
   - Configurer `APP_NAME="Meeting Manager"`
   - Configurer les accès MySQL
   - Lancer les migrations :
```bash
php artisan migrate --seed
```

4. Lancer le serveur :
```bash
php artisan serve
```

---
**Développé pour la Faculté Polydisciplinaire de Sidi Bennour**  
*Par Rachid ELOUIZI*
