# Guide d'utilisation - Affectation d'équipe de bord

## Description
Ce module permet d'affecter une équipe complète (chauffeur, contrôleur, receveur) à un bus et de désaffecter des membres individuellement.

## Fonctionnalités ajoutées

### 1. Affecter une équipe à un bus
✅ Sélectionner un bus disponible  
✅ Composer une équipe complète (chauffeur + contrôleur + receveur)  
✅ Vérification automatique que les membres ne sont pas déjà affectés  
✅ Affectation en une seule opération  

### 2. Désaffecter un membre d'un bus
✅ Retirer un membre de son affectation actuelle  
✅ Bouton visible uniquement pour les membres affectés  
✅ Confirmation avant désaffectation  

## Routes ajoutées

### Route d'affectation
```
POST /equipe-bord/affecter
```

**Payload JSON :**
```json
{
  "bus_id": 1,
  "chauffeur_id": 5,
  "controleur_id": 12,
  "receveur_id": 18
}
```

**Réponse succès :**
```json
{
  "success": true,
  "message": "Équipe affectée avec succès au bus #421"
}
```

**Réponse erreur :**
```json
{
  "success": false,
  "message": "Le chauffeur est déjà affecté au bus #315"
}
```

### Route de désaffectation
```
POST /equipe-bord/desaffecter
```

**Payload JSON :**
```json
{
  "membre_id": 5
}
```

**Réponse succès :**
```json
{
  "success": true,
  "message": "Membre désaffecté avec succès"
}
```

## Utilisation dans l'interface

### Affecter une équipe

1. **Accéder à la page Équipe de bord**
   ```
   http://localhost/SafariSmartMobily/equipe-bord
   ```

2. **Cliquer sur "Nouvelle Affectation"**
   - Un modal s'ouvre avec le formulaire d'affectation

3. **Remplir le formulaire**
   - **Bus** : Sélectionner le bus à affecter (avec Select2 pour recherche)
   - **Chauffeur** : Choisir un chauffeur disponible (non affecté)
   - **Contrôleur** : Choisir un contrôleur disponible
   - **Receveur** : Choisir un receveur disponible
   - **Date et horaires** : Définir le shift (optionnel pour l'instant)

4. **Valider**
   - Cliquer sur "Affecter l'équipe"
   - La page se recharge automatiquement après succès

### Désaffecter un membre

1. **Dans le tableau des membres**
   - Repérer un membre avec un bus affecté
   - Le bouton de désaffectation (X rouge) est visible

2. **Cliquer sur le bouton de désaffectation**
   - Une confirmation s'affiche

3. **Confirmer**
   - Le membre est retiré du bus
   - La page se recharge automatiquement

## Validations effectuées

### Lors de l'affectation
- ✅ Bus sélectionné existe
- ✅ Les 3 membres (chauffeur, contrôleur, receveur) sont sélectionnés
- ✅ Aucun membre n'est déjà affecté à un autre bus
- ✅ Le bus existe dans la base de données

### Lors de la désaffectation
- ✅ Le membre existe
- ✅ Le membre a bien un bus affecté

## Structure de la base de données

### Table `equipe_bord`
```sql
CREATE TABLE `equipe_bord` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `poste` enum('chauffeur','controleur','receveur') NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `adresse` text,
  `bus_affecte` varchar(20) DEFAULT NULL,  -- Numéro du bus
  `statut` enum('actif','conge','inactif') DEFAULT 'actif',
  `date_embauche` date DEFAULT NULL,
  `notes` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
);
```

**Champ clé :** `bus_affecte` contient le **numéro** du bus (ex: "421", "315")

### Table `bus`
```sql
CREATE TABLE `bus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) NOT NULL,  -- Référencé par equipe_bord.bus_affecte
  `immatriculation` varchar(50) NOT NULL,
  ...
);
```

## Méthodes du modèle utilisées

### Dans `EquipeBord.php`

```php
// Affecter un membre à un bus
public function affecterBus($idMembre, $numeroBus)

// Retirer un membre de son bus
public function retirerDuBus($idMembre)

// Récupérer un membre par ID
public function getMembreParId($id)
```

### Dans `Bus.php`

```php
// Récupérer un bus par ID
public function getBusParId($id)
```

## Logs générés

### Affectation réussie
```
[INFO] Équipe affectée au bus #421 (Chauffeur: 5, Contrôleur: 12, Receveur: 18) par l'utilisateur 1
```

### Désaffectation réussie
```
[INFO] Membre Jean-Pierre Mukendi (ID: 5) désaffecté du bus #421 par l'utilisateur 1
```

### Erreur
```
[ERROR] Erreur affectation équipe: Le chauffeur est déjà affecté au bus #315
```

## Améliorations futures possibles

1. **Historique des affectations**
   - Créer une table `historique_affectations`
   - Enregistrer chaque changement d'affectation

2. **Gestion des shifts**
   - Utiliser les champs date/heure du formulaire
   - Créer des affectations temporaires

3. **Notifications**
   - Notifier les membres lors d'une nouvelle affectation
   - Alerter en cas de désaffectation

4. **Validation avancée**
   - Vérifier que le bus n'a pas déjà une équipe complète
   - Empêcher l'affectation de membres en congé

5. **Interface améliorée**
   - Drag & drop pour affecter rapidement
   - Vue calendrier pour les shifts
   - Tableau de bord des affectations

## Dépannage

### Erreur : "Le chauffeur est déjà affecté au bus #XXX"
**Cause :** Le membre sélectionné a déjà un bus affecté  
**Solution :** Désaffecter d'abord le membre de son bus actuel

### Erreur : "Bus introuvable"
**Cause :** L'ID du bus n'existe pas dans la base  
**Solution :** Vérifier que le bus existe dans la table `bus`

### Le bouton de désaffectation n'apparaît pas
**Cause :** Le membre n'a pas de bus affecté  
**Solution :** Normal, le bouton n'apparaît que pour les membres affectés

### Les Select2 ne se chargent pas
**Cause :** jQuery ou Select2 non chargé  
**Solution :** Vérifier que les CDN sont accessibles dans le fichier HTML

## Support technique

Pour toute question ou problème :
1. Vérifier les logs dans le dossier `/logs`
2. Consulter la console du navigateur (F12)
3. Vérifier que les routes sont bien définies dans `index.php`
4. S'assurer que les méthodes existent dans `EquipeBordController.php`
