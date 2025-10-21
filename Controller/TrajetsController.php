<?php

/**
 * Contrôleur Trajets
 * Gère toutes les opérations CRUD pour les trajets
 */

 require_once ROOT_PATH . '/Model/Trajets.php';

class TrajetsController {
    private $trajetModel;

    public function __construct() {
        $this->trajetModel = new Trajets();
    }

    /**
     * Afficher la liste des trajets avec pagination
     */
    public function index() {
        try {
            // Pagination par défaut
            $limit = 10;
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $currentPage = max($currentPage, 1); // jamais < 1
            $offset = ($currentPage - 1) * $limit;

            // Récupérer les trajets
            $trajets = $this->trajetModel->getTrajets($limit, $offset);

            // Nombre total de trajets
            $totalTrajets = $this->trajetModel->compterTrajets();

            // Calcul des infos de pagination
            $totalPages = ceil($totalTrajets / $limit);
            $paginationStart = $offset + 1;
            $paginationEnd = min($offset + count($trajets), $totalTrajets);

            // Compter les arrêts et points de chifte pour chaque trajet
            $arretsCount = [];
            $pointsChifteCount = [];
            
            foreach ($trajets as $trajet) {
                $arrets = $this->trajetModel->getArretsByTrajet($trajet['id']);
                $pointsChifte = $this->trajetModel->getPointsChifteByTrajet($trajet['id']);
                
                $arretsCount[$trajet['id']] = count($arrets);
                $pointsChifteCount[$trajet['id']] = count($pointsChifte);
            }

            // Charger la vue
            require VIEW_PATH . '/trajets.php';
        } catch (Exception $e) {
            logMessage("Erreur lors du chargement de la page trajets: " . $e->getMessage(), "ERROR");
            $_SESSION['error'] = "Erreur lors du chargement des données";
            redirect('/dashboard_' . $_SESSION['departement']);
        }
    }

    /**
     * Afficher le détail d'un trajet
     */
    public function voir($id) {
        try {
            $trajet = $this->trajetModel->getTrajetById($id);
            if (!$trajet) {
                throw new Exception("Trajet introuvable");
            }

            // Récupérer les arrêts et points de chifte associés
            $arrets = $this->trajetModel->getArretsByTrajet($id);
            $pointsChifte = $this->trajetModel->getPointsChifteByTrajet($id);

            require VIEW_PATH . '/trajet-detail.php';
        } catch (Exception $e) {
            logMessage("Erreur lors de l'affichage du trajet: " . $e->getMessage(), "ERROR");
            $_SESSION['error'] = $e->getMessage();
            redirect('/trajets');
        }
    }

    /**
     * Ajouter un nouveau trajet
     */
    public function ajouter() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'nom' => $_POST['nom'],
                    'code' => $_POST['code'],
                    'distance_totale' => $_POST['distance_totale'],
                    'duree_estimee' => $_POST['duree_estimee'],
                    'statut' => $_POST['statut'] ?? 'actif'
                ];

                $this->trajetModel->ajouterTrajet($data);
                $_SESSION['success'] = "Trajet ajouté avec succès";
                redirect('/trajets');
            } catch (Exception $e) {
                logMessage("Erreur lors de l'ajout du trajet: " . $e->getMessage(), "ERROR");
                $_SESSION['error'] = $e->getMessage();
                redirect('/trajets/ajouter');
            }
        }

        // Afficher le formulaire
        require VIEW_PATH . '/trajet-form.php';
    }

    /**
     * Modifier un trajet existant
     */
    public function edit($id) {
        $trajet = $this->trajetModel->getTrajetById($id);
        if (!$trajet) {
            $_SESSION['error'] = "Trajet introuvable";
            redirect('/trajets');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'nom' => $_POST['nom'],
                    'code' => $_POST['code'],
                    'distance_totale' => $_POST['distance_totale'],
                    'duree_estimee' => $_POST['duree_estimee'],
                    'statut' => $_POST['statut'] ?? 'actif'
                ];

                $this->trajetModel->mettreAJourTrajet($id, $data);
                $_SESSION['success'] = "Trajet mis à jour avec succès";
                redirect('/trajets');
            } catch (Exception $e) {
                logMessage("Erreur lors de la modification du trajet: " . $e->getMessage(), "ERROR");
                $_SESSION['error'] = $e->getMessage();
                redirect("/trajets/edit/$id");
            }
        }

        // Afficher le formulaire de modification
        require VIEW_PATH . '/trajet-form.php';
    }

    /**
     * Récupérer les détails d'un trajet (AJAX)
     */
    public function getDetails() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $trajetId = (int) ($_GET['trajet_id'] ?? 0);
            
            if (!$trajetId) {
                throw new Exception("ID du trajet manquant");
            }

            $trajet = $this->trajetModel->getTrajetById($trajetId);
            
            if (!$trajet) {
                throw new Exception("Trajet non trouvé");
            }

            // Récupérer les arrêts et points de chifte
            $arrets = $this->trajetModel->getArretsByTrajet($trajetId);
            $pointsChifte = $this->trajetModel->getPointsChifteByTrajet($trajetId);

            echo json_encode([
                'success' => true,
                'trajet' => $trajet,
                'arrets' => $arrets,
                'pointsChifte' => $pointsChifte
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
     * Créer un nouveau trajet (AJAX)
     */
    public function create() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || empty($data['nom'])) {
                throw new Exception("Le nom du trajet est requis");
            }

            $trajetId = $this->trajetModel->ajouterTrajet($data);
            
            if (!$trajetId) {
                throw new Exception("Erreur lors de la création du trajet");
            }

            echo json_encode([
                'success' => true,
                'message' => 'Trajet créé avec succès',
                'id' => $trajetId
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
     * Mettre à jour un trajet (AJAX)
     */
    public function update() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || empty($data['id'])) {
                throw new Exception("ID du trajet manquant");
            }

            $trajetId = (int) $data['id'];
            
            // Vérifier si le trajet existe
            $trajet = $this->trajetModel->getTrajetById($trajetId);
            if (!$trajet) {
                throw new Exception("Trajet non trouvé");
            }

            $this->trajetModel->mettreAJourTrajet($trajetId, $data);

            echo json_encode([
                'success' => true,
                'message' => 'Trajet mis à jour avec succès'
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
     * Supprimer un trajet (AJAX)
     */
    public function delete() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || empty($data['id'])) {
                throw new Exception("ID du trajet manquant");
            }

            $trajetId = (int) $data['id'];
            
            // Vérifier si le trajet existe
            $trajet = $this->trajetModel->getTrajetById($trajetId);
            if (!$trajet) {
                throw new Exception("Trajet non trouvé");
            }

            $this->trajetModel->supprimerTrajet($trajetId);

            echo json_encode([
                'success' => true,
                'message' => 'Trajet supprimé avec succès'
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
     * Récupérer tous les trajets actifs (AJAX) - Pour vente de billets
     */
    public function liste() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            // Récupérer tous les trajets actifs
            $trajets = $this->trajetModel->getTousLesTrajets();

            echo json_encode([
                'success' => true,
                'trajets' => $trajets
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'trajets' => []
            ]);
        }
        exit;
    }

    /**
     * Récupérer les arrêts d'un trajet (AJAX) - Pour vente de billets
     */
    public function arrets() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $trajetId = (int) ($_GET['trajet_id'] ?? 0);
            
            if (!$trajetId) {
                throw new Exception("ID du trajet manquant");
            }

            // Récupérer les arrêts du trajet depuis la table arrets
            $arrets = $this->trajetModel->getArretsByTrajet($trajetId);

            echo json_encode([
                'success' => true,
                'arrets' => $arrets
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'arrets' => []
            ]);
        }
        exit;
    }

    /**
     * Enregistrer un trajet complet avec arrêts et shifts (AJAX)
     */
    public function save() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            error_log("=== SAVE TRAJET ===");
            error_log("Données reçues: " . json_encode($data));
            
            if (!$data || empty($data['nom'])) {
                throw new Exception("Le nom du trajet est requis");
            }

            // Préparer les données du trajet
            $trajetData = [
                'nom' => $data['nom'],
                'statut' => $data['statut'] ?? 'actif',
                'distance_totale' => $data['distance_totale'] ?? 0,
                'lat_depart' => $data['lat_depart'] ?? null,
                'lon_depart' => $data['lon_depart'] ?? null,
                'lat_arrivee' => $data['lat_arrivee'] ?? null,
                'lon_arrivee' => $data['lon_arrivee'] ?? null
            ];

            // Créer ou mettre à jour le trajet
            if (!empty($data['id'])) {
                // Mise à jour
                $trajetId = (int) $data['id'];
                $this->trajetModel->mettreAJourTrajet($trajetId, $trajetData);
                
                // Supprimer les anciens arrêts et shifts
                $this->trajetModel->supprimerArretsByTrajet($trajetId);
                $this->trajetModel->supprimerShiftsByTrajet($trajetId);
            } else {
                // Création
                $trajetId = $this->trajetModel->ajouterTrajet($trajetData);
            }

            if (!$trajetId) {
                throw new Exception("Erreur lors de l'enregistrement du trajet");
            }

            // Ajouter les arrêts
            if (!empty($data['arrets']) && is_array($data['arrets'])) {
                foreach ($data['arrets'] as $arret) {
                    $arretData = [
                        'trajet_id' => $trajetId,
                        'nom' => $arret['nom'],
                        'latitude' => $arret['latitude'] ?? null,
                        'longitude' => $arret['longitude'] ?? null,
                        'distance_avec_debut' => $arret['distance_avec_debut'] ?? 0
                    ];
                    $this->trajetModel->ajouterArret($arretData);
                }
            }

            // Ajouter les shifts
            if (!empty($data['shifts']) && is_array($data['shifts'])) {
                foreach ($data['shifts'] as $shift) {
                    $shiftData = [
                        'trajet_id' => $trajetId,
                        'nom' => $shift['nom'],
                        'latitude' => $shift['latitude'] ?? null,
                        'longitude' => $shift['longitude'] ?? null,
                        'distance_avec_debut' => $shift['distance_avec_debut'] ?? 0,
                        'temp_parcour' => $shift['temp_parcour'] ?? 0
                    ];
                    $this->trajetModel->ajouterShift($shiftData);
                }
            }

            echo json_encode([
                'success' => true,
                'message' => 'Trajet enregistré avec succès',
                'id' => $trajetId
            ]);

        } catch (Exception $e) {
            error_log("ERREUR SAVE TRAJET: " . $e->getMessage());
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
