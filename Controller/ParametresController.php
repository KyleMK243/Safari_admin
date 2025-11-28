<?php
/**
 * Contrôleur Paramètres
 * Gère toutes les opérations pour les paramètres et utilisateurs
 */

require_once ROOT_PATH . '/Model/Parametres.php';
require_once ROOT_PATH . '/Model/Permission.php';

class ParametresController {
    private $parametresModel;
    private $permissionModel;

    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page";
            redirect('/login');
            exit;
        }

        $this->parametresModel = new Parametres();
        $this->permissionModel = new Permission();
    }

    /**
     * Afficher la page des paramètres - Redirection automatique selon département
     */
    public function index() {
        $departement = $_SESSION['departement'] ?? 'PL';
        
        // Rediriger vers la page de paramètres du département
        switch ($departement) {
            case 'BT':
                redirect('/parametres-bt');
                break;
            case 'RH':
                redirect('/parametres-rh');
                break;
            case 'BC':
                redirect('/parametres-bc');
                break;
            case 'PL':
            default:
                redirect('/parametres-pl');
                break;
        }
    }

    /**
     * Afficher la page des paramètres - Planification
     */
    public function indexPL() {
        try {
            // Récupérer tous les utilisateurs du département PL
            $utilisateurs = $this->parametresModel->getUtilisateursByDepartement('PL');
            
            // Charger la vue
            require VIEW_PATH . '/parametres-pl.php';
            
        } catch (Exception $e) {
            error_log("Erreur parametres PL: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement des paramètres";
            redirect('/dashboard_PL');
        }
    }

    /**
     * Afficher la page des paramètres - Billetterie
     */
    public function indexBT() {
        try {
            // Récupérer tous les utilisateurs du département BT
            $utilisateurs = $this->parametresModel->getUtilisateursByDepartement('BT');
            
            // Charger la vue
            require VIEW_PATH . '/parametres-bt.php';
            
        } catch (Exception $e) {
            error_log("Erreur parametres BT: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement des paramètres";
            redirect('/dashboard_BT');
        }
    }

    /**
     * Afficher la page des paramètres - RH
     */
    public function indexRH() {
        try {
            // Récupérer tous les utilisateurs du département RH
            $utilisateurs = $this->parametresModel->getUtilisateursByDepartement('RH');
            
            // Charger la vue
            require VIEW_PATH . '/parametres-rh.php';
            
        } catch (Exception $e) {
            error_log("Erreur parametres RH: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement des paramètres";
            redirect('/dashboard_RH');
        }
    }

    /**
     * Afficher la page des paramètres - Bureau de conception (BC)
     */
    public function indexBC() {
        try {
            // Récupérer tous les utilisateurs du département BC
            $utilisateurs = $this->parametresModel->getUtilisateursByDepartement('BC');
            
            // Charger la vue spécifique BC
            require VIEW_PATH . '/parametres-bc.php';
            
        } catch (Exception $e) {
            error_log("Erreur parametres BC: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement des paramètres";
            redirect('/dashboard_BC');
        }
    }

    /**
     * Afficher la page des paramètres généraux (Super Admin uniquement)
     */
    public function general() {
        try {
            // Vérifier que l'utilisateur est super admin
            if ($_SESSION['role'] !== 'admin') {
                $_SESSION['error'] = "Accès non autorisé";
                redirect('/dashboard');
                exit;
            }
            
            // Charger la vue
            require VIEW_PATH . '/parametres-general.php';
            
        } catch (Exception $e) {
            error_log("Erreur parametres general: " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement des paramètres";
            redirect('/dashboard');
        }
    }

    /**
     * Créer un utilisateur (département PL uniquement)
     */
    public function creerUtilisateur() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $nom = $_POST['nom'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'viewer';
            $statut = $_POST['statut'] ?? 'actif';

            // Validation
            if (empty($nom) || empty($email) || empty($password)) {
                echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis']);
                exit;
            }

            $result = $this->parametresModel->creerUtilisateur($nom, $email, $password, $role, $statut);
            echo json_encode($result);

        } catch (Exception $e) {
            error_log("Erreur creerUtilisateur: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    /**
     * Modifier un utilisateur (département PL uniquement)
     */
    public function modifierUtilisateur() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $id = $_POST['id'] ?? '';
            $nom = $_POST['nom'] ?? '';
            $email = $_POST['email'] ?? '';
            $password = !empty($_POST['password']) ? $_POST['password'] : null;
            $role = $_POST['role'] ?? 'viewer';
            $statut = $_POST['statut'] ?? 'actif';

            // Validation
            if (empty($id) || empty($nom) || empty($email)) {
                echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis']);
                exit;
            }

            $result = $this->parametresModel->modifierUtilisateur($id, $nom, $email, $role, $statut, $password);
            echo json_encode($result);

        } catch (Exception $e) {
            error_log("Erreur modifierUtilisateur: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur']);
        }
    }

    /**
     * Changer le statut d'un utilisateur
     */
    public function changerStatut() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $id = $_POST['id'] ?? '';
            $statut = $_POST['statut'] ?? '';

            if (empty($id) || empty($statut)) {
                echo json_encode(['success' => false, 'message' => 'ID et statut requis']);
                exit;
            }

            $result = $this->parametresModel->changerStatutUtilisateur($id, $statut);
            echo json_encode($result);

        } catch (Exception $e) {
            error_log("Erreur changerStatut: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function supprimerUtilisateur() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $id = $_POST['id'] ?? '';

            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'ID requis']);
                exit;
            }

            $result = $this->parametresModel->supprimerUtilisateur($id);
            echo json_encode($result);

        } catch (Exception $e) {
            error_log("Erreur supprimerUtilisateur: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }

    /**
     * Récupérer un utilisateur par ID (pour le formulaire de modification)
     */
    public function getUtilisateur() {
        header('Content-Type: application/json');
        
        try {
            $id = $_GET['id'] ?? '';

            if (empty($id)) {
                echo json_encode(['success' => false, 'message' => 'ID requis']);
                exit;
            }

            $utilisateur = $this->parametresModel->getUtilisateurById($id);
            
            if ($utilisateur) {
                echo json_encode(['success' => true, 'utilisateur' => $utilisateur]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
            }

        } catch (Exception $e) {
            error_log("Erreur getUtilisateur: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => 'Erreur serveur: ' . $e->getMessage()]);
        }
    }
}
