<?php

require_once ROOT_PATH . '/Model/Alertes.php';

class AlertesController {
    private $alerteModel;

    public function __construct() {
        $this->alerteModel = new Alertes();
    }

    /**
     * Afficher la page des alertes
     */
    public function index() {
        try {
            // Pagination
            $limit = 20;
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $currentPage = max($currentPage, 1);
            $offset = ($currentPage - 1) * $limit;

            // Filtres
            $filters = [
                'type_alerte' => $_GET['type_alerte'] ?? '',
                'statut' => $_GET['statut'] ?? '',
                'priorite' => $_GET['priorite'] ?? '',
                'search' => $_GET['search'] ?? ''
            ];

            // Récupérer les alertes
            $alertes = $this->alerteModel->getAlertes($limit, $offset, $filters);
            
            // Nombre total d'alertes
            $totalAlertes = $this->alerteModel->compterAlertes($filters);
            
            // Statistiques
            $stats = $this->alerteModel->getStatistiques();

            // Calcul pagination
            $totalPages = ceil($totalAlertes / $limit);
            $paginationStart = $offset + 1;
            $paginationEnd = min($offset + count($alertes), $totalAlertes);

            // Charger la vue
            require VIEW_PATH . '/feedback.php';
        } catch (Exception $e) {
            // Initialiser les variables pour éviter les erreurs dans la vue
            $alertes = [];
            $stats = ['critiques' => 0, 'avertissements' => 0, 'informations' => 0, 'resolus' => 0];
            $totalAlertes = 0;
            $totalPages = 0;
            $paginationStart = 0;
            $paginationEnd = 0;
            $currentPage = 1;
            
            // Message d'erreur pour l'utilisateur
            $_SESSION['error'] = "Erreur lors du chargement des alertes. Veuillez réessayer.";
            
            require VIEW_PATH . '/feedback.php';
        }
    }

    /**
     * Récupérer les alertes (AJAX)
     */
    public function getAlertes() {
        header('Content-Type: application/json');
        
        try {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $filters = [
                'type_alerte' => $_GET['type_alerte'] ?? '',
                'statut' => $_GET['statut'] ?? '',
                'priorite' => $_GET['priorite'] ?? '',
                'search' => $_GET['search'] ?? ''
            ];

            $alertes = $this->alerteModel->getAlertes($limit, $offset, $filters);
            $total = $this->alerteModel->compterAlertes($filters);
            $stats = $this->alerteModel->getStatistiques();

            echo json_encode([
                'success' => true,
                'alertes' => $alertes,
                'total' => $total,
                'stats' => $stats
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Traiter une alerte (AJAX)
     */
    public function traiter() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['id'])) {
                throw new Exception("ID de l'alerte manquant");
            }

            $alerteId = (int)$data['id'];
            $action = $data['action'] ?? 'en_cours';
            $details = $data['details'] ?? [];
            $userId = $_SESSION['user_id'] ?? null;

            // Vérifier que l'alerte existe
            $alerte = $this->alerteModel->getAlerteById($alerteId);
            if (!$alerte) {
                throw new Exception("Alerte non trouvée");
            }

            // Déterminer le nouveau statut
            $nouveauStatut = match($action) {
                'traiter' => 'en_cours',
                'resoudre' => 'resolu',
                'ignorer' => 'resolu',
                default => 'en_cours'
            };

            // Mettre à jour le statut
            $this->alerteModel->mettreAJourStatut($alerteId, $nouveauStatut, $userId);
            
            // Enregistrer l'historique du traitement
            if (!empty($details)) {
                $this->alerteModel->enregistrerHistorique($alerteId, $action, $details, $userId);
            }

            $message = match($action) {
                'traiter' => 'Alerte prise en charge avec succès',
                'resoudre' => 'Alerte résolue avec succès',
                'ignorer' => 'Alerte ignorée avec succès',
                default => 'Alerte traitée avec succès'
            };

            echo json_encode([
                'success' => true,
                'message' => $message
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Marquer toutes les alertes comme lues (AJAX)
     */
    public function marquerToutesLues() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $this->alerteModel->marquerToutesCommeLues();

            echo json_encode([
                'success' => true,
                'message' => 'Toutes les alertes ont été marquées comme lues'
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Supprimer une alerte (AJAX)
     */
    public function supprimer() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['id'])) {
                throw new Exception("ID de l'alerte manquant");
            }

            $alerteId = (int)$data['id'];

            // Vérifier que l'alerte existe
            $alerte = $this->alerteModel->getAlerteById($alerteId);
            if (!$alerte) {
                throw new Exception("Alerte non trouvée");
            }

            $this->alerteModel->supprimerAlerte($alerteId);

            echo json_encode([
                'success' => true,
                'message' => 'Alerte supprimée avec succès'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}
