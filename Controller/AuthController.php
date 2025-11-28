<?php
/**
 * Controller d'authentification
 * Gère la connexion, déconnexion et vérification des utilisateurs
 */

require_once ROOT_PATH . '/Model/Utilisateur.php';

class AuthController {
    
    private $utilisateurModel;
    
    public function __construct() {
        $this->utilisateurModel = new Utilisateur();
    }

    /**
     * Afficher la page de login
     */
    public function afficherLogin() {
        // Si déjà connecté, rediriger vers le dashboard approprié
        if (isLoggedIn()) {
            $this->redirigerSelonDepartement($_SESSION['departement']);
            exit;
        }
        
        require VIEW_PATH . '/login.php';
    }

    /**
     * Traiter la connexion
     */
    public function login() {
        // Vérifier que c'est une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/');
            exit;
        }

        // Valider le token CSRF
        if (!validateCSRFToken(post('csrf_token'))) {
            $_SESSION['error'] = "Token de sécurité invalide. Veuillez réessayer.";
            redirect('/');
            exit;
        }

        // Récupérer et nettoyer les données
        $email = post('email');
        $motDePasse = $_POST['mot_de_passe'] ?? ''; // Ne pas sanitizer le mot de passe

        // Validation des champs
        if (empty($email) || empty($motDePasse)) {
            $_SESSION['error'] = "Veuillez remplir tous les champs.";
            redirect('/');
            exit;
        }

        // Valider le format de l'email
        if (!isValidEmail($email)) {
            $_SESSION['error'] = "Format d'email invalide.";
            redirect('/');
            exit;
        }

        // Tentative de connexion (mode maquette : pas de vérification en base)
        $utilisateur = [
            'id' => 1,
            'nom' => 'Utilisateur Démo',
            'email' => $email,
            'role' => 'admin',
            'departement' => 'BC', // Bureau de conception
            'avatar' => null,
        ];

        // Régénérer l'ID de session (sécurité)
        session_regenerate_id(true);
        
        // Stocker les informations dans la session
        $_SESSION['user_id'] = $utilisateur['id'];
        $_SESSION['nom'] = $utilisateur['nom'];
        $_SESSION['email'] = $utilisateur['email'];
        $_SESSION['role'] = $utilisateur['role'];
        $_SESSION['departement'] = $utilisateur['departement'];
        $_SESSION['avatar'] = $utilisateur['avatar'];
        $_SESSION['last_activity'] = time();
        
        // Logger la connexion démo
        logMessage("Connexion DEMO pour l'utilisateur: {$utilisateur['email']} (ID: {$utilisateur['id']})", "INFO");
        
        // Redirection selon le département
        $this->redirigerSelonDepartement($utilisateur['departement']);
        exit;
    }

    /**
     * Déconnexion
     */
    public function logout() {
        // Logger la déconnexion
        if (isLoggedIn()) {
            logMessage("Déconnexion de l'utilisateur: {$_SESSION['email']} (ID: {$_SESSION['user_id']})", "INFO");
        }
        
        // Détruire toutes les données de session
        $_SESSION = [];
        
        // Détruire le cookie de session
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Détruire la session
        session_destroy();
        
        // Rediriger vers la page de login
        redirect('/');
        exit;
    }

    /**
     * Vérifier si l'utilisateur est connecté (middleware)
     */
    public function verifierAuth() {
        if (!isLoggedIn()) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page.";
            redirect('/');
            exit;
        }
        
        // Vérifier le timeout de session (30 minutes d'inactivité)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
            $this->logout();
        }
        
        // Mettre à jour le timestamp d'activité
        $_SESSION['last_activity'] = time();
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     * 
     * @param string|array $roles Rôle(s) autorisé(s)
     */
    public function verifierRole($roles) {
        $this->verifierAuth();
        
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        if (!in_array($_SESSION['role'], $roles)) {
            $_SESSION['error'] = "Vous n'avez pas les permissions nécessaires.";
            $this->redirigerSelonDepartement($_SESSION['departement']);
            exit;
        }
    }

    /**
     * Vérifier si l'utilisateur appartient à un département
     * 
     * @param string|array $departements Département(s) autorisé(s)
     */
    public function verifierDepartement($departements) {
        $this->verifierAuth();
        
        if (!is_array($departements)) {
            $departements = [$departements];
        }
        
        if (!in_array($_SESSION['departement'], $departements)) {
            $_SESSION['error'] = "Accès non autorisé à ce module.";
            $this->redirigerSelonDepartement($_SESSION['departement']);
            exit;
        }
    }

    /**
     * Rediriger selon le département
     * 
     * @param string $departement Code du département (PL, BT, RH)
     */
    private function redirigerSelonDepartement($departement) {
        switch ($departement) {
            case 'PL':
                // Planification
                redirect('/dashboard_PL');
                break;
                
            case 'BT':
                // Billetterie
                redirect('/billetterie');
                break;
                
            case 'RH':
                // Ressources Humaines
                redirect('/rh-dashboard');
                break;
            
            case 'BC':
                // Bureau de conception
                redirect('/dashboard_BC');
                break;
                
            default:
                // Si département inconnu, déconnecter
                $_SESSION['error'] = "Département non reconnu. Veuillez contacter l'administrateur.";
                $this->logout();
                break;
        }
    }

    /**
     * Changer le mot de passe de l'utilisateur connecté
     */
    public function changerMotDePasse() {
        $this->verifierAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('/profil');
            exit;
        }

        // Valider le token CSRF
        if (!validateCSRFToken(post('csrf_token'))) {
            $_SESSION['error'] = "Token de sécurité invalide.";
            redirect('/profil');
            exit;
        }

        $ancienMotDePasse = $_POST['ancien_mot_de_passe'] ?? '';
        $nouveauMotDePasse = $_POST['nouveau_mot_de_passe'] ?? '';
        $confirmationMotDePasse = $_POST['confirmation_mot_de_passe'] ?? '';

        // Validation
        if (empty($ancienMotDePasse) || empty($nouveauMotDePasse) || empty($confirmationMotDePasse)) {
            $_SESSION['error'] = "Veuillez remplir tous les champs.";
            redirect('/profil');
            exit;
        }

        if ($nouveauMotDePasse !== $confirmationMotDePasse) {
            $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
            redirect('/profil');
            exit;
        }

        // Vérifier la force du mot de passe (minimum 8 caractères)
        if (strlen($nouveauMotDePasse) < 8) {
            $_SESSION['error'] = "Le mot de passe doit contenir au moins 8 caractères.";
            redirect('/profil');
            exit;
        }

        // Vérifier l'ancien mot de passe
        $utilisateur = $this->utilisateurModel->getParId($_SESSION['user_id']);
        
        // Récupérer le hash du mot de passe actuel
        $sql = "SELECT mot_de_passe FROM utilisateurs WHERE id = :id";
        $stmt = Database::getInstance()->getConnection()->prepare($sql);
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $hash = $stmt->fetchColumn();
        
        if (!password_verify($ancienMotDePasse, $hash)) {
            $_SESSION['error'] = "L'ancien mot de passe est incorrect.";
            redirect('/profil');
            exit;
        }

        // Changer le mot de passe
        if ($this->utilisateurModel->changerMotDePasse($_SESSION['user_id'], $nouveauMotDePasse)) {
            logMessage("Changement de mot de passe pour l'utilisateur ID: {$_SESSION['user_id']}", "INFO");
            $_SESSION['success'] = "Mot de passe modifié avec succès.";
        } else {
            $_SESSION['error'] = "Erreur lors du changement de mot de passe.";
        }

        redirect('/profil');
        exit;
    }
}
