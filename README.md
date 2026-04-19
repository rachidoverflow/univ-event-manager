# Meeting Manager - Faculté Polydisciplinaire de Sidi Bennour (FPSB)

Une solution de gestion moderne, efficace et transparente pour l'organisation et le suivi des réunions académiques et administratives au sein de la Faculté Polydisciplinaire de Sidi Bennour.

##  Présentation
Développé par **Rachid ELOUIZI**, ce système permet d'automatiser le cycle de vie complet des réunions, de la planification à la publication des comptes-rendus officiels. L'interface adopte un design inspiré de l'écosystème Odoo, privilégiant la clarté technique et la densité informationnelle.

##  Fonctionnalités Clés

###  Gestion des Réunions
- **Planification Avancée** : Création de réunions standards ou élargies.
- **Ordre du Jour Dynamique** : Gestion des points de discussion avec suivi des décisions en temps réel.
- **Invitations Automatiques** : Envoi groupé d'invitations par commission (instance) et ajout de participants extérieurs.

###  Gestion des Membres & Instances
- **Annuaire Centralisé** : CRUD complet des professeurs et fonctionnaires avec recherche instantanée.
- **Commissions (Instances)** : Organisation des membres par commissions spécialisées avec gestion des responsables.
- **Rôles & Permissions** : Système de droits différenciés (Administrateur, Responsable, Membre).

###  Système de Notifications Omniprésent
- **Alertes en Temps Réel** : Notifications internes (cloche) pour les invitations, les mises à jour de planning et la publication de rapports.
- **Suivi des Interactions** : Le responsable est notifié dès qu'un participant accepte ou décline une invitation.
- **Rappels Automatiques** : Relances par email et notifications internes 24h avant chaque séance.

###  Archivage & Rapports
- **Compte-rendus (PV)** : Téléchargement et archivage des comptes-rendus PDF.
- **Génération de PV** : Vue dédiée pour l'exportation des décisions officielles.

##  Stack Technique
- **Backend** : Laravel 11
- **Frontend** : Blade Templates & Vanilla CSS (Thème Mixte Odoo-style)
- **Base de données** : MySQL
- **Icônes** : Lucide Icons
- **Notifications** : Laravel Database Notifications & Mail service

##  Design & UX
L'application utilise un **Thème Mixte** haut de gamme :
- **Sidebar Sombre (Elite)** : Pour une navigation focalisée.
- **Contenu Clair (Odoo Geometry)** : Angles droits (sharp), flat design et bordures nettes pour une lisibilité maximale.

##  Installation

1. Cloner le repository :
```bash
git clone [repository-url]
```

2. Installer les dépendances :
```bash
composer install
npm install
```

3. Configurer l'environnement :
   - Dupliquer `.env.example` en `.env`
   - Configurer la base de données
   - Lancer les migrations et seeders :
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
