<?php
/**
 * Script pour générer des hash de mots de passe
 * Utilisez ce script pour créer des mots de passe sécurisés
 * 
 * Exécutez : php generate_password.php
 */

// Mot de passe à hasher
$password = 'password123'; // CHANGEZ CE MOT DE PASSE

// Générer le hash avec ARGON2ID (le plus sécurisé)
$hash = password_hash($password, PASSWORD_ARGON2ID);

echo "========================================\n";
echo "GÉNÉRATEUR DE HASH DE MOT DE PASSE\n";
echo "========================================\n\n";
echo "Mot de passe : $password\n\n";
echo "Hash généré :\n";
echo "$hash\n\n";
echo "========================================\n";
echo "Copiez ce hash dans votre requête SQL\n";
echo "========================================\n\n";

// Exemple de requête SQL
echo "Exemple d'insertion :\n\n";
echo "INSERT INTO utilisateurs (nom, email, mot_de_passe, role, departement, statut, avatar)\n";
echo "VALUES ('Nom Utilisateur', 'email@safari.cd', '$hash', 'admin', 'PL', 'actif', 'NU');\n\n";

// Vérification
if (password_verify($password, $hash)) {
    echo "✅ Vérification : Le hash est valide !\n\n";
} else {
    echo "❌ Erreur : Le hash n'est pas valide !\n\n";
}
?>
