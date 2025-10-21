<?php
/**
 * Fichier d'initialisation de l'application
 * Charge la configuration, démarre la session et configure la sécurité
 */

// Charger la configuration (qui charge le .env)
require_once __DIR__ . '/config.php';

// Définir le fuseau horaire
date_default_timezone_set('Africa/Lubumbashi');

// ==========================================
// CONFIGURATION DES SESSIONS SÉCURISÉE
// ==========================================

// Paramètres de sécurité des cookies de session
ini_set('session.cookie_httponly', 1);  // Empêche l'accès JavaScript aux cookies
ini_set('session.use_strict_mode', 1);  // Empêche l'utilisation d'IDs de session non initialisés
ini_set('session.cookie_samesite', 'Lax'); // Protection CSRF (Strict en production)

// En production, forcer HTTPS pour les cookies
if (APP_ENV === 'production') {
    ini_set('session.cookie_secure', 1);
}

// Nom de session personnalisé (plus sécurisé que PHPSESSID)
session_name('SAFARI_SESSION');

// Démarrer la session
session_start();

// Régénérer l'ID de session périodiquement (sécurité)
if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
} elseif (time() - $_SESSION['last_regeneration'] > 1800) { // 30 minutes
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

// Charger la base de données
require_once __DIR__ . '/database.php';

// ==========================================
// HEADERS DE SÉCURITÉ
// ==========================================

if (APP_ENV === 'production') {
    // Headers stricts en production
    header("X-Frame-Options: DENY");
    header("X-Content-Type-Options: nosniff");
    header("X-XSS-Protection: 1; mode=block");
    header("Referrer-Policy: strict-origin-when-cross-origin");
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' unpkg.com cdn.jsdelivr.net code.jquery.com maps.googleapis.com *.googleapis.com cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net; font-src 'self' fonts.gstatic.com; img-src 'self' data: maps.googleapis.com *.googleapis.com *.gstatic.com; connect-src 'self' maps.googleapis.com *.googleapis.com;");
} else {
    // Headers plus permissifs en développement
    header("X-Frame-Options: SAMEORIGIN");
    header("X-Content-Type-Options: nosniff");
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' unpkg.com cdn.jsdelivr.net code.jquery.com maps.googleapis.com *.googleapis.com cdnjs.cloudflare.com; style-src 'self' 'unsafe-inline' fonts.googleapis.com cdn.jsdelivr.net; font-src 'self' fonts.gstatic.com; img-src 'self' data: maps.googleapis.com *.googleapis.com *.gstatic.com; connect-src 'self' maps.googleapis.com *.googleapis.com;");
}

// ==========================================
// FONCTIONS DE SÉCURITÉ CSRF
// ==========================================

/**
 * Générer un token CSRF
 * 
 * @return string Token CSRF
 */
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Valider un token CSRF
 * 
 * @param string $token Token à valider
 * @return bool True si valide
 */
function validateCSRFToken($token) {
    if (!isset($_SESSION['csrf_token'])) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Obtenir un champ input caché avec le token CSRF
 * 
 * @return string HTML du champ caché
 */
function csrfField() {
    $token = generateCSRFToken();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Obtenir le token CSRF pour les requêtes AJAX
 * 
 * @return string Token CSRF
 */
function csrfToken() {
    return generateCSRFToken();
}

// ==========================================
// FONCTIONS DE VALIDATION
// ==========================================

/**
 * Valider et nettoyer les données POST
 * 
 * @param string $key Clé du tableau POST
 * @param mixed $default Valeur par défaut
 * @return mixed Valeur nettoyée
 */
function post($key, $default = '') {
    return isset($_POST[$key]) ? sanitizeInput($_POST[$key]) : $default;
}

/**
 * Valider et nettoyer les données GET
 * 
 * @param string $key Clé du tableau GET
 * @param mixed $default Valeur par défaut
 * @return mixed Valeur nettoyée
 */
function get($key, $default = '') {
    return isset($_GET[$key]) ? sanitizeInput($_GET[$key]) : $default;
}

/**
 * Vérifier si l'utilisateur est connecté
 * 
 * @return bool True si connecté
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Obtenir l'utilisateur connecté
 * 
 * @return array|null Données utilisateur ou null
 */
function currentUser() {
    return $_SESSION['user'] ?? null;
}

/**
 * Middleware pour protéger les routes
 * Redirige vers login si non connecté
 */
function requireAuth() {
    if (!isLoggedIn()) {
        redirect('/');
    }
}
