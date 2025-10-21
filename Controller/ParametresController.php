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
     * Afficher la page des paramètres
     */
    public function index() {
        try {
            // Récupérer tous les utilisateurs du département PL
            $utilisateurs = $this->parametresModel->getUtilisateurs();
            
            // Récupérer les statistiques
            $stats = $this->parametresModel->getStatistiquesUtilisateurs();
            
            // Récupérer les modules et permissions pour le département PL
            $modules = $this->permissionModel->getModulesByDepartement('PL');
            $permissions = $this->permissionModel->getAllPermissionsByDepartement('PL');
            
            // Récupérer les paramètres système
            $parametresSysteme = $this->parametresModel->getParametresSysteme();
            
            // Charger la vue
            require VIEW_PATH . '/parametres.php';
            
        } catch (Exception $e) {
            error_log("Erreur parametres index: " . $e->getMessage());
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
