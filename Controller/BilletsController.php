<?php

require_once ROOT_PATH . '/Model/Billets.php';

class BilletsController {
    private $billetModel;

    public function __construct() {
        $this->billetModel = new Billets();
    }

    /**
     * Afficher la page billetterie
     */
    public function index() {
        try {
            // Récupérer les statistiques
            $stats = $this->billetModel->getStatistiques();
            
            // Récupérer les 15 dernières transactions
            $transactions = $this->billetModel->getTransactionsRecentes(15);
            
            // Récupérer les billets récents
            $limit = 20;
            $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
            $currentPage = max($currentPage, 1);
            $offset = ($currentPage - 1) * $limit;

            $filters = [
                'statut' => $_GET['statut'] ?? '',
                'date_debut' => $_GET['date_debut'] ?? '',
                'date_fin' => $_GET['date_fin'] ?? '',
                'search' => $_GET['search'] ?? ''
            ];

            $billets = $this->billetModel->getBilletsRecents($limit, $offset, $filters);
            $totalBillets = $this->billetModel->compterBillets($filters);
            
            // Pagination
            $totalPages = ceil($totalBillets / $limit);
            $paginationStart = $offset + 1;
            $paginationEnd = min($offset + count($billets), $totalBillets);

            // Charger la vue
            require VIEW_PATH . '/billetterie.php';
        } catch (Exception $e) {
            // Initialiser les variables pour éviter les erreurs
            $stats = [
                'billets_vendus' => 0,
                'revenus' => 0,
                'reservations' => 0,
                'cartes_actives' => 0,
                'tendance_billets' => 0,
                'tendance_revenus' => 0
            ];
            $transactions = [];
            $billets = [];
            $totalBillets = 0;
            $totalPages = 0;
            $paginationStart = 0;
            $paginationEnd = 0;
            $currentPage = 1;
            
            $_SESSION['error'] = "Erreur lors du chargement des données de billetterie";
            require VIEW_PATH . '/billetterie.php';
        }
    }

    /**
     * Annuler un billet (AJAX)
     */
    public function annuler() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (empty($data['id'])) {
                throw new Exception("ID du billet manquant");
            }

            if (empty($data['motif'])) {
                throw new Exception("Motif d'annulation requis");
            }

            $billetId = (int)$data['id'];
            $motif = $data['motif'];
            $userId = $_SESSION['user_id'] ?? null;

            // Vérifier que le billet existe
            $billet = $this->billetModel->getBilletById($billetId);
            if (!$billet) {
                throw new Exception("Billet non trouvé");
            }

            // Vérifier que le billet peut être annulé
            if ($billet['statut_billet'] === 'annule') {
                throw new Exception("Ce billet est déjà annulé");
            }

            if ($billet['statut_billet'] === 'utilise') {
                throw new Exception("Impossible d'annuler un billet déjà utilisé");
            }

            // Annuler le billet
            $this->billetModel->annulerBillet($billetId, $motif, $userId);

            echo json_encode([
                'success' => true,
                'message' => 'Billet annulé avec succès'
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
     * Récupérer les détails d'un billet (AJAX)
     */
    public function getDetails() {
        header('Content-Type: application/json');
        
        try {
            $billetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($billetId <= 0) {
                throw new Exception("ID du billet invalide");
            }

            $billet = $this->billetModel->getBilletById($billetId);
            
            if (!$billet) {
                throw new Exception("Billet non trouvé");
            }

            echo json_encode([
                'success' => true,
                'billet' => $billet
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
     * Imprimer un billet (duplicat)
     */
    public function imprimer() {
        try {
            $billetId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($billetId <= 0) {
                throw new Exception("ID du billet invalide");
            }

            $billet = $this->billetModel->getBilletById($billetId);
            
            if (!$billet) {
                throw new Exception("Billet non trouvé");
            }

            // Charger la vue d'impression
            require VIEW_PATH . '/imprimer-billet.php';

        } catch (Exception $e) {
            echo "Erreur : " . $e->getMessage();
        }
        exit;
    }

    /**
     * Récupérer les bus disponibles pour un trajet (AJAX) - Pour vente de billets
     */
    public function busDisponibles() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $trajetId = (int) ($_GET['trajet_id'] ?? 0);
            $distanceMontee = (float) ($_GET['distance_montee'] ?? 0);
            $distanceDescente = (float) ($_GET['distance_descente'] ?? 0);
            $typeTarif = $_GET['type_tarif'] ?? 'normal';
            $dateVoyage = $_GET['date_voyage'] ?? date('Y-m-d');
            
            if (!$trajetId) {
                throw new Exception("ID du trajet manquant");
            }
            
            if ($distanceMontee < 0 || $distanceDescente <= 0) {
                throw new Exception("Distances invalides");
            }
            
            if ($distanceDescente <= $distanceMontee) {
                throw new Exception("L'arrêt de descente doit être après l'arrêt de montée");
            }

            // Calculer la distance parcourue
            $distanceParcourue = $distanceDescente - $distanceMontee;

            // Récupérer les bus disponibles pour ce trajet (sans calcul de prix)
            $busListe = $this->billetModel->getBusDisponiblesPourTrajet($trajetId, $typeTarif, $dateVoyage);

            // Calculer le prix pour chaque bus
            foreach ($busListe as &$bus) {
                $bus['distance_parcourue'] = $distanceParcourue;
                $bus['prix_total'] = $bus['prix_par_km'] * $distanceParcourue;
            }

            echo json_encode([
                'success' => true,
                'bus' => $busListe
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'bus' => []
            ]);
        }
        exit;
    }

    /**
     * Créer un nouveau billet (vente)
     */
    public function creer() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            error_log("=== CREATION BILLET ===");
            error_log("Données reçues: " . json_encode($data));
            
            // Validation des données
            $required = ['trajet_id', 'tarif_id', 'bus_id', 'arret_depart', 'arret_arrivee', 
                        'date_voyage', 'prix_paye', 'nom_client', 'tel_client', 'mode_paiement'];
            
            foreach ($required as $field) {
                if (empty($data[$field])) {
                    error_log("Champ manquant: $field");
                    throw new Exception("Le champ $field est requis");
                }
            }

            // Créer le billet
            error_log("Création du billet...");
            $billetId = $this->billetModel->creerBillet($data);
            error_log("Billet ID créé: " . $billetId);

            if ($billetId) {
                // Récupérer le billet créé
                error_log("Récupération du billet...");
                $billet = $this->billetModel->getById($billetId);
                error_log("Billet récupéré: " . json_encode($billet));
                
                if (!$billet) {
                    throw new Exception("Billet créé mais non récupéré (ID: $billetId)");
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Billet créé avec succès',
                    'billet' => $billet
                ]);
            } else {
                error_log("Erreur: billetId est vide");
                throw new Exception("Erreur lors de la création du billet");
            }

        } catch (Exception $e) {
            error_log("ERREUR: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}
