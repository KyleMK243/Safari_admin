<?php

require_once ROOT_PATH . '/Model/Dashboard.php';
require_once ROOT_PATH . '/Model/Alertes.php';

class DashboardController {
    private $dashboardModel;
    private $alertesModel;

    public function __construct() {
        $this->dashboardModel = new Dashboard();
        $this->alertesModel = new Alertes();
    }

    /**
     * Afficher la page d'accueil (dashboard)
     */
    public function index() {
        try {
            error_log("=== DASHBOARD INDEX APPELÉ ===");
            
            // Récupérer toutes les données
            $buses = $this->dashboardModel->getBusActifs();
            error_log("Buses récupérés: " . count($buses));
            
            $trajets = $this->dashboardModel->getTrajets();
            error_log("Trajets récupérés: " . count($trajets));
            
            $pointsShift = $this->dashboardModel->getPointsShift();
            $arrets = $this->dashboardModel->getArrets();
            $zones = $this->dashboardModel->getZones();
            $stats = $this->dashboardModel->getStatistiquesJour();
            error_log("Stats: " . json_encode($stats));
            
            // Compter les alertes non traitées
            $nombreAlertesNonTraitees = $this->alertesModel->compterAlertesNonTraitees();
            error_log("Alertes non traitées: " . $nombreAlertesNonTraitees);
            
            // Récupérer les lignes uniques pour le filtre
            $lignes = [];
            foreach ($trajets as $trajet) {
                $lignes[] = [
                    'id' => $trajet['id'],
                    'nom' => $trajet['nom'],
                    'code' => $trajet['code']
                ];
            }
            
            // Charger la vue Dashboard Planification
            require VIEW_PATH . '/dashboard-pl.php';
        } catch (Exception $e) {
            error_log('Erreur Dashboard: ' . $e->getMessage());
            
            // Initialiser des valeurs par défaut
            $buses = [];
            $trajets = [];
            $pointsShift = [];
            $arrets = [];
            $zones = [];
            $lignes = [];
            $stats = [
                'bus_actifs' => 0,
                'shifts_actifs' => 0,
                'passagers' => 0,
                'revenus' => 0
            ];
            $nombreAlertesNonTraitees = 0;
            
            require VIEW_PATH . '/index.php';
        }
    }

    /**
     * Récupérer les données pour AJAX (mise à jour en temps réel)
     */
    public function getDonnees() {
        header('Content-Type: application/json');
        
        try {
            error_log("=== API getDonnees appelée ===");
            
            $buses = $this->dashboardModel->getBusActifs();
            error_log("Nombre de bus récupérés: " . count($buses));
            
            $stats = $this->dashboardModel->getStatistiquesJour();
            error_log("Stats récupérées: " . json_encode($stats));
            
            $data = [
                'buses' => $buses,
                'stats' => $stats
            ];
            
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            error_log("❌ ERREUR getDonnees: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Récupérer les détails d'un bus spécifique
     */
    public function getDetailsBus() {
        header('Content-Type: application/json');
        
        try {
            $busId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
            
            if ($busId <= 0) {
                throw new Exception('ID de bus invalide');
            }
            
            $bus = $this->dashboardModel->getDetailsBus($busId);
            
            if (!$bus) {
                throw new Exception('Bus non trouvé');
            }
            
            echo json_encode(['success' => true, 'bus' => $bus]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
