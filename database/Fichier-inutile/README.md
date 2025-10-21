# Safari Smart Mobility - UI Template

Interface de gestion pour la plateforme Safari Smart Mobility.

## Pages disponibles

### 1. Dashboard (`index.html`)
Page principale de suivi en temps réel de la flotte :
- Carte interactive avec marqueurs des bus
- Filtres par ligne et zone
- Détails d'un bus sélectionné (position, vitesse, carburant, etc.)
- Visualisation du trajet en cours

### 2. Gestion des Bus (`gestion-bus.html`)
Page de gestion CRUD complète pour la flotte :
- **Créer** un nouveau bus
- **Modifier** les informations d'un bus existant
- **Désactiver/Activer** un bus
- **Supprimer** un bus
- **Affecter** un bus à une ligne
- Filtrage par statut, ligne et recherche
- Gestion des modules installés (Datcha, WiFi, POS, GPS, Caméra)

## Couleurs du thème

Les couleurs sont basées sur le logo Safari :
- **Bleu principal** : `#1B4B7F` (sidebar, boutons)
- **Bleu foncé** : `#0F3154` (dégradés)
- **Jaune Safari** : `#FDB913` (accents, warnings)
- **Rouge Safari** : `#E63946` (alertes, danger)

## Structure des fichiers

```
ui-template/
├── index.html              # Dashboard - Suivi temps réel
├── gestion-bus.html        # Gestion des bus
├── styles.css              # Styles uniques pour tout le projet
├── app.js                  # JavaScript unique (sans données)
├── data-test.js            # Données de test (À SUPPRIMER en production)
└── README.md               # Documentation
```

**Important:** Le fichier `data-test.js` contient uniquement des données de test et doit être supprimé lors de l'intégration PHP.

## Architecture

### Fichier CSS unique (`styles.css`)
- Tous les styles de l'application sont centralisés dans un seul fichier
- Styles de base et variables CSS
- Styles du dashboard
- Styles de gestion des bus (tableaux, modals, formulaires)
- Responsive design pour toutes les pages

### Fichier JS unique (`app.js`)
- Toutes les fonctionnalités JavaScript dans un seul fichier
- Initialisation automatique au chargement du DOM
- Fonctions du dashboard (carte, marqueurs, filtres)
- Fonctions de gestion des bus (CRUD complet)
- Gestion des icônes Feather

## Fonctionnalités de gestion des bus

### Création d'un bus
1. Cliquer sur "Nouveau Bus"
2. Remplir le formulaire :
   - Numéro de bus (requis)
   - Immatriculation (requis)
   - Marque, modèle, année
   - Capacité en places
   - Ligne affectée
   - Statut (requis)
   - Modules installés
   - Notes
3. Enregistrer

### Modification
- Cliquer sur l'icône "crayon" dans la colonne Actions
- Modifier les informations
- Enregistrer

### Désactivation/Activation
- Cliquer sur l'icône "cercle" dans la colonne Actions
- Le statut bascule entre actif et inactif

### Suppression
- Cliquer sur l'icône "corbeille"
- Confirmer la suppression

### Filtrage
- Par statut : Actif, Maintenance, En panne, Inactif
- Par ligne : Ligne 1, 2, 3 ou Non affecté
- Par recherche : Numéro, immatriculation, chauffeur

## Statuts disponibles

- **Actif** : Bus en service
- **Maintenance** : Bus en révision
- **En panne** : Bus hors service
- **Inactif** : Bus désactivé

## Modules disponibles

- **Datcha** : Système de billetterie
- **WiFi** : Connexion internet
- **POS** : Point de vente
- **GPS** : Géolocalisation
- **Caméra** : Vidéosurveillance

## Technologies utilisées

- HTML5
- CSS3 (Variables CSS, Grid, Flexbox)
- JavaScript Vanilla (ES6+)
- Feather Icons
- Google Fonts (Inter)

## Navigation

- **Dashboard** : Vue d'ensemble et suivi temps réel
- **Gestion Bus** : Administration de la flotte
- **Chauffeurs** : (À venir)
- **Trajets** : (À venir)
- **Modules** : (À venir)
- **Feedback** : (À venir)
- **BI** : (À venir)
- **Paramètres** : (À venir)

## Responsive Design

L'interface est entièrement responsive :
- Desktop : Vue complète avec sidebar
- Tablette : Adaptation des grilles
- Mobile : Navigation simplifiée, formulaires adaptés

## Prochaines étapes

1. Intégration avec une API backend
2. Authentification utilisateur
3. Gestion des chauffeurs
4. Gestion des trajets/lignes
5. Tableau de bord BI
6. Système de notifications temps réel
