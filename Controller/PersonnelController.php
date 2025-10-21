<?php
/**
 * Contrôleur Personnel
 * Gère toutes les opérations CRUD pour le personnel
 */

require_once ROOT_PATH . '/Model/Personnel.php';

class PersonnelController {
    private $personnelModel;

    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page";
            redirect('/login');
            exit;
        }

        $this->personnelModel = new Personnel();
    }

    /**
     * Afficher la page de gestion du personnel
     */
    public function index() {
        try {
            // Récupérer les filtres depuis l'URL
            $filtrePoste = isset($_GET['poste']) && $_GET['poste'] !== '' ? $_GET['poste'] : null;
            $filtreStatut = isset($_GET['statut']) && $_GET['statut'] !== '' ? $_GET['statut'] : null;
            $recherche = isset($_GET['search']) && $_GET['search'] !== '' ? trim($_GET['search']) : null;
            
            // Récupérer les agents avec filtres
            $agents = $this->personnelModel->getAgentsAvecFiltres($filtrePoste, $filtreStatut, $recherche);
            
            // Récupérer le nombre total
            $totalAgents = count($agents);
            
            // Récupérer les statistiques
            $stats = $this->personnelModel->getStatistiques();
            
            // Charger la vue
            require VIEW_PATH . '/personnel.php';
            
        } catch (Exception $e) {
            logMessage("Erreur affichage personnel: " . $e->getMessage(), "ERROR");
            $_SESSION['error'] = "Erreur lors du chargement du personnel";
            redirect('/rh-dashboard');
        }
    }

    /**
     * Afficher la page de création d'agent
     */
    public function nouveau() {
        try {
            // Générer un matricule
            $matricule = $this->personnelModel->genererMatricule();
            
            // Charger la vue
            require VIEW_PATH . '/nouveau-agent.php';
            
        } catch (Exception $e) {
            logMessage("Erreur page nouveau agent: " . $e->getMessage(), "ERROR");
            $_SESSION['error'] = "Erreur lors du chargement de la page";
            redirect('/personnel');
        }
    }

    /**
     * API - Récupérer la liste des agents (AJAX)
     */
    public function getAgents() {
        header('Content-Type: application/json');
        
        try {
            $filtrePoste = isset($_GET['poste']) && $_GET['poste'] !== '' ? $_GET['poste'] : null;
            $filtreStatut = isset($_GET['statut']) && $_GET['statut'] !== '' ? $_GET['statut'] : null;
            $recherche = isset($_GET['search']) && $_GET['search'] !== '' ? trim($_GET['search']) : null;
            
            // Pagination
            $page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
            $limit = isset($_GET['limit']) && is_numeric($_GET['limit']) ? (int)$_GET['limit'] : 10;
            $offset = ($page - 1) * $limit;
            
            $agents = $this->personnelModel->getAgentsAvecFiltres($filtrePoste, $filtreStatut, $recherche, $limit, $offset);
            $total = $this->personnelModel->compterAgents($filtrePoste, $filtreStatut, $recherche);
            $stats = $this->personnelModel->getStatistiques();
            
            echo json_encode([
                'success' => true,
                'agents' => $agents,
                'total' => $total,
                'stats' => $stats,
                'page' => $page,
                'limit' => $limit,
                'totalPages' => ceil($total / $limit)
            ]);
            
        } catch (Exception $e) {
            logMessage("Erreur API getAgents: " . $e->getMessage(), "ERROR");
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la récupération des agents'
            ]);
        }
        exit;
    }

    /**
     * Récupérer un agent par ID (AJAX)
     */
    public function getAgent() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID manquant']);
            exit;
        }
        
        try {
            $agent = $this->personnelModel->getAgentParId($_GET['id']);
            
            if (!$agent) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Agent non trouvé']);
                exit;
            }
            
            echo json_encode([
                'success' => true,
                'agent' => $agent
            ]);
            
        } catch (Exception $e) {
            logMessage("Erreur récupération agent: " . $e->getMessage(), "ERROR");
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Créer un nouvel agent (AJAX)
     */
    public function creer() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }
        
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            // Validation
            if (empty($data['nom']) || empty($data['poste'])) {
                throw new Exception("Nom et poste sont obligatoires");
            }
            
            // Vérifier si le matricule existe déjà
            if (isset($data['matricule']) && $this->personnelModel->matriculeExiste($data['matricule'])) {
                throw new Exception("Ce matricule existe déjà");
            }
            
            // Si pas de matricule, en générer un
            if (empty($data['matricule'])) {
                $data['matricule'] = $this->personnelModel->genererMatricule();
            }
            
            // Créer l'agent
            $result = $this->personnelModel->creerAgent($data);
            
            if ($result) {
                logMessage("Agent créé: {$data['nom']} ({$data['matricule']}) par l'utilisateur {$_SESSION['user_id']}", "INFO");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Agent créé avec succès',
                    'matricule' => $data['matricule']
                ]);
            } else {
                throw new Exception("Erreur lors de la création de l'agent");
            }
            
        } catch (Exception $e) {
            logMessage("Erreur création agent: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Modifier un agent (AJAX)
     */
    public function modifier() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }
        
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if (empty($data['id'])) {
                throw new Exception("ID de l'agent manquant");
            }
            
            // Validation
            if (empty($data['nom']) || empty($data['poste'])) {
                throw new Exception("Nom et poste sont obligatoires");
            }
            
            // Modifier l'agent
            $result = $this->personnelModel->modifierAgent($data['id'], $data);
            
            if ($result) {
                logMessage("Agent modifié: ID {$data['id']} par l'utilisateur {$_SESSION['user_id']}", "INFO");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Agent modifié avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de la modification de l'agent");
            }
            
        } catch (Exception $e) {
            logMessage("Erreur modification agent: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Supprimer un agent (AJAX)
     */
    public function supprimer() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }
        
        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);
            
            if (empty($data['id'])) {
                throw new Exception("ID de l'agent manquant");
            }
            
            // Supprimer l'agent
            $result = $this->personnelModel->supprimerAgent($data['id']);
            
            if ($result) {
                logMessage("Agent supprimé: ID {$data['id']} par l'utilisateur {$_SESSION['user_id']}", "WARNING");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Agent supprimé avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de la suppression de l'agent");
            }
            
        } catch (Exception $e) {
            logMessage("Erreur suppression agent: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}

?>
