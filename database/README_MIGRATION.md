# Guide d'utilisation de DatabaseMigration

## Description
La classe `DatabaseMigration` permet d'ajouter facilement des colonnes à vos tables de base de données de manière sécurisée.

## Fonctionnalités
✅ Ajoute deux colonnes à une table en une seule opération  
✅ Vérifie automatiquement si les colonnes existent déjà  
✅ Supporte tous les types de données MySQL  
✅ Gère les valeurs par défaut, NULL/NOT NULL, commentaires  
✅ Permet de positionner les colonnes avec AFTER  
✅ Peut ajouter des index uniques  

## Installation
La classe est déjà installée dans `Config/DatabaseMigration.php`

## Utilisation de base

### 1. Ajouter deux colonnes simples

```php
<?php
require_once __DIR__ . '/../Config/init.php';
require_once __DIR__ . '/../Config/DatabaseMigration.php';

$migration = new DatabaseMigration();

$result = $migration->addTwoColumns(
    'nom_de_la_table',
    [
        'name' => 'colonne1',
        'type' => 'VARCHAR(100)',
        'nullable' => true,
        'default' => null,
        'after' => 'colonne_existante',
        'comment' => 'Description de la colonne 1'
    ],
    [
        'name' => 'colonne2',
        'type' => 'INT',
        'nullable' => false,
        'default' => '0',
        'after' => 'colonne1',
        'comment' => 'Description de la colonne 2'
    ]
);

// Vérifier le résultat
if ($result['success']) {
    echo "Migration réussie !";
} else {
    echo "Erreurs : " . implode(', ', $result['errors']);
}
```

### 2. Configuration des colonnes

Chaque colonne est définie par un tableau associatif avec les clés suivantes :

| Clé | Type | Obligatoire | Description |
|-----|------|-------------|-------------|
| `name` | string | ✅ Oui | Nom de la colonne |
| `type` | string | ✅ Oui | Type de données MySQL (VARCHAR(50), INT, etc.) |
| `nullable` | bool | ❌ Non | true = NULL, false = NOT NULL (défaut: true) |
| `default` | mixed | ❌ Non | Valeur par défaut (null, '0', 'valeur', etc.) |
| `after` | string | ❌ Non | Nom de la colonne après laquelle insérer |
| `comment` | string | ❌ Non | Commentaire descriptif de la colonne |

### 3. Exemples de types de données

```php
// Texte
['name' => 'nom', 'type' => 'VARCHAR(255)']
['name' => 'description', 'type' => 'TEXT']

// Nombres
['name' => 'age', 'type' => 'INT']
['name' => 'prix', 'type' => 'DECIMAL(10,2)']
['name' => 'quantite', 'type' => 'BIGINT']

// Dates
['name' => 'date_naissance', 'type' => 'DATE']
['name' => 'date_creation', 'type' => 'DATETIME']
['name' => 'derniere_modif', 'type' => 'TIMESTAMP']

// Booléen
['name' => 'actif', 'type' => 'TINYINT(1)']

// JSON
['name' => 'metadata', 'type' => 'JSON']

// Énumération
['name' => 'statut', 'type' => "ENUM('actif','inactif','suspendu')"]
```

### 4. Ajouter un index unique

```php
$result = $migration->addUniqueIndex(
    'nom_table',
    'nom_colonne',
    'nom_index_optionnel'
);
```

## Exemples pratiques

### Exemple 1 : Ajouter des colonnes à la table 'bus'

```php
$result = $migration->addTwoColumns(
    'bus',
    [
        'name' => 'couleur',
        'type' => 'VARCHAR(50)',
        'nullable' => true,
        'after' => 'modele',
        'comment' => 'Couleur du bus'
    ],
    [
        'name' => 'consommation',
        'type' => 'DECIMAL(5,2)',
        'nullable' => true,
        'after' => 'kilometrage',
        'comment' => 'Consommation en L/100km'
    ]
);
```

### Exemple 2 : Ajouter des colonnes avec valeurs par défaut

```php
$result = $migration->addTwoColumns(
    'trajets',
    [
        'name' => 'prix_standard',
        'type' => 'DECIMAL(10,2)',
        'nullable' => false,
        'default' => '0.00',
        'comment' => 'Prix standard du trajet'
    ],
    [
        'name' => 'actif',
        'type' => 'TINYINT(1)',
        'nullable' => false,
        'default' => '1',
        'comment' => 'Trajet actif (1) ou inactif (0)'
    ]
);
```

### Exemple 3 : Ajouter des colonnes de dates

```php
$result = $migration->addTwoColumns(
    'equipe_bord',
    [
        'name' => 'date_embauche',
        'type' => 'DATE',
        'nullable' => true,
        'comment' => 'Date d\'embauche'
    ],
    [
        'name' => 'date_fin_contrat',
        'type' => 'DATE',
        'nullable' => true,
        'comment' => 'Date de fin de contrat'
    ]
);
```

## Gestion des résultats

La méthode `addTwoColumns()` retourne un tableau avec :

```php
[
    'success' => true/false,           // Succès global
    'messages' => [...],               // Messages d'information
    'errors' => [...]                  // Erreurs éventuelles
]
```

### Afficher les résultats

```php
if ($result['success']) {
    echo "✓ Migration réussie !<br>";
    foreach ($result['messages'] as $msg) {
        echo "- $msg<br>";
    }
} else {
    echo "✗ Erreurs détectées :<br>";
    foreach ($result['errors'] as $error) {
        echo "- $error<br>";
    }
}
```

## Sécurité

✅ Vérifie l'existence de la table avant modification  
✅ Vérifie l'existence des colonnes pour éviter les doublons  
✅ Utilise des requêtes préparées pour éviter les injections SQL  
✅ Gère les erreurs PDO de manière appropriée  

## Tester la migration

Pour tester, accédez à :
```
http://localhost/SafariSmartMobily/database/exemple_migration.php
```

## Conseils

1. **Testez d'abord** sur une copie de votre base de données
2. **Sauvegardez** votre base avant toute migration
3. **Vérifiez** les résultats après chaque migration
4. **Documentez** vos migrations pour référence future

## Support

Pour toute question ou problème, consultez la documentation MySQL :
- [Types de données MySQL](https://dev.mysql.com/doc/refman/8.0/en/data-types.html)
- [ALTER TABLE](https://dev.mysql.com/doc/refman/8.0/en/alter-table.html)
