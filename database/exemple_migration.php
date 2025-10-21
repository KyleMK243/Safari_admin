<?php
/**
 * Exemple d'utilisation de la classe DatabaseMigration
 * Ce fichier montre comment ajouter deux colonnes à une table
 */

// Chargement de la configuration
require_once __DIR__ . '/../Config/init.php';
require_once __DIR__ . '/../Config/DatabaseMigration.php';

// Créer une instance de DatabaseMigration
$migration = new DatabaseMigration();

// ============================================
// EXEMPLE 1 : Ajouter deux colonnes à la table 'bus'
// ============================================
echo "<h2>Exemple 1 : Ajouter deux colonnes à la table 'bus'</h2>";

$result = $migration->addTwoColumns(
    'bus',  // Nom de la table
    [
        'name' => 'couleur',
        'type' => 'VARCHAR(50)',
        'nullable' => true,
        'default' => null,
        'after' => 'modele',
        'comment' => 'Couleur du bus'
    ],
    [
        'name' => 'consommation',
        'type' => 'DECIMAL(5,2)',
        'nullable' => true,
        'default' => null,
        'after' => 'kilometrage',
        'comment' => 'Consommation moyenne en L/100km'
    ]
);

// Afficher les résultats
echo "<h3>Résultat :</h3>";
if ($result['success']) {
    echo "<p style='color: green;'>✓ Migration réussie !</p>";
} else {
    echo "<p style='color: red;'>✗ Erreurs détectées</p>";
}

echo "<h4>Messages :</h4><ul>";
foreach ($result['messages'] as $message) {
    echo "<li>$message</li>";
}
echo "</ul>";

if (!empty($result['errors'])) {
    echo "<h4 style='color: red;'>Erreurs :</h4><ul>";
    foreach ($result['errors'] as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}

// ============================================
// EXEMPLE 2 : Ajouter deux colonnes à la table 'trajets'
// ============================================
echo "<hr><h2>Exemple 2 : Ajouter deux colonnes à la table 'trajets'</h2>";

$result2 = $migration->addTwoColumns(
    'trajets',
    [
        'name' => 'prix_standard',
        'type' => 'DECIMAL(10,2)',
        'nullable' => false,
        'default' => '0.00',
        'after' => 'duree_estimee',
        'comment' => 'Prix standard du trajet'
    ],
    [
        'name' => 'actif',
        'type' => 'TINYINT(1)',
        'nullable' => false,
        'default' => '1',
        'after' => 'prix_standard',
        'comment' => 'Trajet actif ou non'
    ]
);

echo "<h3>Résultat :</h3>";
if ($result2['success']) {
    echo "<p style='color: green;'>✓ Migration réussie !</p>";
} else {
    echo "<p style='color: red;'>✗ Erreurs détectées</p>";
}

echo "<h4>Messages :</h4><ul>";
foreach ($result2['messages'] as $message) {
    echo "<li>$message</li>";
}
echo "</ul>";

if (!empty($result2['errors'])) {
    echo "<h4 style='color: red;'>Erreurs :</h4><ul>";
    foreach ($result2['errors'] as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
}

// ============================================
// EXEMPLE 3 : Ajouter un index unique
// ============================================
echo "<hr><h2>Exemple 3 : Ajouter un index unique</h2>";

$result3 = $migration->addUniqueIndex('bus', 'immatriculation', 'idx_bus_immatriculation_unique');

echo "<h3>Résultat :</h3>";
echo "<p>" . $result3['message'] . "</p>";

if (isset($result3['error'])) {
    echo "<p style='color: red;'>Erreur : " . $result3['error'] . "</p>";
}

// ============================================
// TYPES DE DONNÉES MYSQL SUPPORTÉS
// ============================================
echo "<hr><h2>Types de données MySQL supportés</h2>";
echo "<ul>
    <li><strong>VARCHAR(n)</strong> - Chaîne de caractères variable (ex: VARCHAR(255))</li>
    <li><strong>INT</strong> - Nombre entier</li>
    <li><strong>BIGINT</strong> - Grand nombre entier</li>
    <li><strong>DECIMAL(p,s)</strong> - Nombre décimal (ex: DECIMAL(10,2))</li>
    <li><strong>FLOAT</strong> - Nombre à virgule flottante</li>
    <li><strong>DOUBLE</strong> - Nombre à virgule flottante double précision</li>
    <li><strong>DATE</strong> - Date (YYYY-MM-DD)</li>
    <li><strong>DATETIME</strong> - Date et heure</li>
    <li><strong>TIMESTAMP</strong> - Horodatage</li>
    <li><strong>TEXT</strong> - Texte long</li>
    <li><strong>LONGTEXT</strong> - Texte très long</li>
    <li><strong>TINYINT(1)</strong> - Booléen (0 ou 1)</li>
    <li><strong>ENUM('val1','val2')</strong> - Énumération</li>
    <li><strong>JSON</strong> - Données JSON</li>
</ul>";
?>
