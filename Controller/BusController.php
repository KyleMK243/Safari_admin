<?php
/**
 * Contrôleur Bus
 * Gère toutes les opérations CRUD pour les bus
 */

require_once ROOT_PATH . '/Model/Bus.php';

class BusController {
    private $busModel;

    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page";
            redirect('/login');
            exit;
        }

        $this->busModel = new Bus();
    }

    /**
     * Afficher la page de gestion des bus
     */
    public function index() {
        try {
            // Récupérer les filtres depuis l'URL
            $filtreStatut = isset($_GET['statut']) && $_GET['statut'] !== '' ? $_GET['statut'] : null;
            $filtreTrajet = isset($_GET['trajet']) && $_GET['trajet'] !== '' ? $_GET['trajet'] : null;
            $recherche = isset($_GET['search']) && $_GET['search'] !== '' ? trim($_GET['search']) : null;
            
            // Debug
            error_log("=== FILTRES BUS ===");
            error_log("Statut: " . ($filtreStatut ?? 'null'));
            error_log("Trajet: " . ($filtreTrajet ?? 'null'));
            error_log("Recherche: " . ($recherche ?? 'null'));
            
            // Récupérer les bus avec filtres
            $buses = $this->busModel->getBusAvecFiltres($filtreStatut, $filtreTrajet, $recherche);
            
            error_log("Nombre de bus trouvés: " . count($buses));
            
            // Récupérer le nombre total de bus
            $totalBus = count($buses);
            
            // Récupérer les trajets pour le filtre
            require_once ROOT_PATH . '/Model/Trajets.php';
            $trajetModel = new Trajets();
            $trajets = $trajetModel->getTousLesTrajets();
            
            // Charger la vue
            require VIEW_PATH . '/gestion-bus.php';
            
        } catch (Exception $e) {
            error_log("ERREUR BUS INDEX: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            
            // Afficher l'erreur directement au lieu de rediriger
            echo "<!DOCTYPE html><html><head><title>Erreur</title></head><body>";
            echo "<h1>Erreur lors du chargement des bus</h1>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
            echo "<a href='/gestion-bus'>Retour</a>";
            echo "</body></html>";
            exit;
        }
    }
    
    /**
     * Ajouter un bus
     */
    public function ajouter() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $rawInput = file_get_contents('php://input');
            $data = json_decode($rawInput, true);

            if (!$data) {
                throw new Exception("Aucune donnée reçue ou JSON invalide : " . json_last_error_msg());
            }

            if (empty($data['numero']) || empty($data['immatriculation'])) {
                throw new Exception("Le numéro et l'immatriculation sont obligatoires");
            }

            // ✅ Adaptation pour modules : accepte tableau OU chaîne
            $modules = '';
            if (!empty($data['modules'])) {
                if (is_array($data['modules'])) {
                    $modules = implode(',', $data['modules']);
                } else {
                    $modules = trim($data['modules']);
                }
            }

            $donnees = [
                'numero' => trim($data['numero']),
                'immatriculation' => trim($data['immatriculation']),
                'marque' => trim($data['marque'] ?? ''),
                'modele' => trim($data['modele'] ?? ''),
                'annee' => (int) ($data['annee'] ?? 0),
                'capacite' => (int) ($data['capacite'] ?? 0),
                'kilometrage' => (int) ($data['kilometrage'] ?? 0),
                'ligne_affectee' => !empty($data['ligne_affectee']) ? trim($data['ligne_affectee']) : null,
                'statut' => $data['statut'] ?? 'actif',
                'modules' => $modules,
                'notes' => trim($data['notes'] ?? ''),
                'derniere_activite' => date('Y-m-d H:i:s')
            ];

            $result = $this->busModel->ajouterBus($donnees);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Bus ajouté avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de l'ajout du bus");
            }

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Modifier un bus existant
     */
    public function modifier() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => 'error', 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data) {
                throw new Exception("Aucune donnée reçue");
            }
            
            if (empty($data['id'])) {
                throw new Exception("ID du bus manquant");
            }
            
            $busId = (int) $data['id'];
            $bus = $this->busModel->getBusParId($busId);
            if (!$bus) {
                throw new Exception("Bus non trouvé");
            }

            // ✅ Gestion des modules identique à celle de l’ajout
            $modules = '';
            if (!empty($data['modules'])) {
                if (is_array($data['modules'])) {
                    $modules = implode(',', $data['modules']);
                } else {
                    $modules = trim($data['modules']);
                }
            }

            $donnees = [
                'numero' => trim($data['numero'] ?? $bus['numero']),
                'immatriculation' => trim($data['immatriculation'] ?? $bus['immatriculation']),
                'marque' => trim($data['marque'] ?? ''),
                'modele' => trim($data['modele'] ?? ''),
                'annee' => (int) ($data['annee'] ?? 0),
                'capacite' => (int) ($data['capacite'] ?? 0),
                'kilometrage' => (int) ($data['kilometrage'] ?? 0),
                'ligne_affectee' => !empty($data['ligne_affectee']) ? trim($data['ligne_affectee']) : null,
                'statut' => $data['statut'] ?? 'actif',
                'modules' => $modules,
                'notes' => trim($data['notes'] ?? ''),
                'derniere_activite' => date('Y-m-d H:i:s')
            ];

            $result = $this->busModel->mettreAJourBus($busId, $donnees);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Bus modifié avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de la modification du bus");
            }

        } catch (Exception $e) {
            echo json_encode([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Supprimer un bus (AJAX)
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        if (!validateCSRFToken(post('csrf_token'))) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            exit;
        }

        // Seul l'admin peut supprimer
        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Seul un administrateur peut supprimer un bus']);
            exit;
        }

        try {
            $busId = (int) post('bus_id');
            
            if (!$busId) {
                throw new Exception("ID du bus manquant");
            }

            $result = $this->busModel->supprimerBus($busId);

            if ($result) {
                logMessage("Bus supprimé: ID $busId par l'utilisateur {$_SESSION['user_id']}", "WARNING");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Bus supprimé avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de la suppression du bus");
            }

        } catch (Exception $e) {
            logMessage("Erreur suppression bus: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Changer le statut d'un bus (AJAX)
     */
    public function changeStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        if (!validateCSRFToken(post('csrf_token'))) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            exit;
        }

        try {
            $busId = (int) post('bus_id');
            $statut = post('statut');
            
            if (!$busId || !$statut) {
                throw new Exception("Données manquantes");
            }

            // Valider le statut
            $statutsValides = ['actif', 'maintenance', 'panne', 'inactif'];
            if (!in_array($statut, $statutsValides)) {
                throw new Exception("Statut invalide");
            }

            $result = $this->busModel->changerStatutBus($busId, $statut);

            if ($result) {
                logMessage("Statut bus modifié: ID $busId -> $statut par l'utilisateur {$_SESSION['user_id']}", "INFO");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Statut modifié avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors du changement de statut");
            }

        } catch (Exception $e) {
            logMessage("Erreur changement statut bus: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Récupérer les détails d'un bus (AJAX)
     */
    public function getDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $busId = (int) ($_GET['bus_id'] ?? 0);
            
            if (!$busId) {
                throw new Exception("ID du bus manquant");
            }

            $bus = $this->busModel->getBusParId($busId);
            
            if (!$bus) {
                throw new Exception("Bus non trouvé");
            }

            // Récupérer les documents du bus
            $documents = $this->busModel->getDocumentsBus($busId);
            $bus['documents'] = $documents;
            
            // Récupérer l'équipe de bord affectée au bus
            $equipe = $this->busModel->getEquipeBordBus($bus['numero']);
            $bus['equipe'] = $equipe;

            echo json_encode([
                'success' => true,
                'bus' => $bus
            ]);

        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Rechercher des bus (AJAX)
     */
    public function search() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $motCle = trim($_GET['q'] ?? '');
            
            if (empty($motCle)) {
                throw new Exception("Mot-clé de recherche manquant");
            }

            $resultats = $this->busModel->chercherBus($motCle);

            echo json_encode([
                'success' => true,
                'resultats' => $resultats,
                'count' => count($resultats)
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
     * Filtrer les bus (AJAX)
     */
    public function filter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $statut = $_GET['statut'] ?? null;
            $ligne = $_GET['ligne'] ?? null;
            $limit = (int) ($_GET['limit'] ?? 100);
            $offset = (int) ($_GET['offset'] ?? 0);

            $buses = $this->busModel->getBusAvecPagination($limit, $offset, $statut, $ligne);
            $total = $this->busModel->compterTousLesBus($statut, $ligne);

            echo json_encode([
                'success' => true,
                'buses' => $buses,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
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
