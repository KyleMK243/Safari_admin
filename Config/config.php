<?php
/**
 * Configuration principale de l'application
 * Charge les variables d'environnement et configure l'application
 */

// Fonction pour charger le fichier .env
function loadEnv($path) {
    if (!file_exists($path)) {
        die("❌ Fichier .env introuvable. Copiez .env.example vers .env et configurez vos paramètres.");
    }
    
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Ignorer les commentaires
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parser la ligne
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Définir la variable d'environnement
            if (!array_key_exists($name, $_ENV)) {
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }
}

// Charger le fichier .env
loadEnv(__DIR__ . '/../.env');

// Définir les constantes d'environnement
define('APP_ENV', getenv('APP_ENV') ?: 'development');
define('APP_DEBUG', getenv('APP_DEBUG') === 'true');
define('APP_SECRET', getenv('APP_SECRET') ?: 'change_me_in_production');

// Configuration selon l'environnement
if (APP_ENV === 'development') {
    // MODE DÉVELOPPEMENT - Afficher les erreurs
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    // Logger aussi dans un fichier
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/php-errors.log');
    
} else {
    // MODE PRODUCTION - Cacher les erreurs
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
    
    // Logger uniquement dans un fichier
    ini_set('log_errors', 1);
    ini_set('error_log', __DIR__ . '/../logs/php-errors.log');
}

// Définir les constantes de chemin
if (!defined('BASE_URL')) {
    define('BASE_URL', '');
}
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}
if (!defined('VIEW_PATH')) {
    define('VIEW_PATH', ROOT_PATH . '/Views');
}

// ==========================================
// FONCTIONS UTILITAIRES DE SÉCURITÉ
// ==========================================

/**
 * Échapper les données pour l'affichage HTML (Protection XSS)
 * Utilisez TOUJOURS cette fonction pour afficher des données utilisateur
 * 
 * @param mixed $data Données à échapper
 * @return mixed Données échappées
 */
function e($data) {
    if (is_array($data)) {
        return array_map('e', $data);
    }
    if (is_null($data)) {
        return '';
    }
    return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
}

/**
 * Fonction de chiffrement sécurisée pour les IDs
 * 
 * @param mixed $id ID à chiffrer
 * @return string Hash HMAC
 */
function signerId($id) {
    return hash_hmac('sha256', (string)$id, APP_SECRET);
}

/**
 * Nettoyer les entrées utilisateur
 * 
 * @param mixed $data Données à nettoyer
 * @return mixed Données nettoyées
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return trim(strip_tags((string)$data));
}

/**
 * Valider une adresse email
 * 
 * @param string $email Email à valider
 * @return bool True si valide
 */
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Valider un numéro de téléphone (format RDC)
 * 
 * @param string $phone Numéro à valider
 * @return bool True si valide
 */
function isValidPhone($phone) {
    // Format: +243 XXX XXX XXX ou 0XXX XXX XXX
    $pattern = '/^(\+243|0)[0-9]{9}$/';
    $cleaned = preg_replace('/[\s\-\(\)]/', '', $phone);
    return preg_match($pattern, $cleaned);
}

/**
 * Fonction de debug (uniquement en développement)
 * Affiche les variables et arrête l'exécution
 * 
 * @param mixed ...$vars Variables à afficher
 */
function dd(...$vars) {
    if (APP_DEBUG) {
        echo '<pre style="background:#1e1e1e;color:#dcdcdc;padding:20px;border-radius:8px;margin:20px;font-family:monospace;font-size:14px;line-height:1.6;">';
        echo '<strong style="color:#4ec9b0;font-size:16px;">🐛 DEBUG OUTPUT</strong><br><br>';
        foreach ($vars as $var) {
            var_dump($var);
            echo '<br>';
        }
        echo '</pre>';
        die();
    }
}

/**
 * Logger un message (en développement et production)
 * 
 * @param string $message Message à logger
 * @param string $level Niveau (INFO, WARNING, ERROR)
 */
function logMessage($message, $level = 'INFO') {
    $logFile = ROOT_PATH . '/logs/app.log';
    $logDir = dirname($logFile);
    
    // Créer le dossier logs s'il n'existe pas
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * Rediriger vers une URL
 * 
 * @param string $url URL de destination
 */
function redirect($url) {
    header("Location: " . BASE_URL . $url);
    exit;
}

/**
 * Générer une chaîne aléatoire sécurisée
 * 
 * @param int $length Longueur de la chaîne
 * @return string Chaîne aléatoire
 */
function generateRandomString($length = 32) {
    return bin2hex(random_bytes($length / 2));
}
