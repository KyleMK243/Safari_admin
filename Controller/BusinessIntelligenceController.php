<?php

require_once ROOT_PATH . '/Model/BusinessIntelligence.php';

class BusinessIntelligenceController {
    private $biModel;

    public function __construct() {
        $this->biModel = new BusinessIntelligence();
    }

    /**
     * Afficher la page Business Intelligence
     */
    public function index() {
        try {
            // Récupérer la période sélectionnée
            $periode = isset($_GET['periode']) ? (int)$_GET['periode'] : 30;
            $periode = in_array($periode, [7, 30, 90, 365]) ? $periode : 30;

            // Récupérer les KPIs
            $kpis = $this->biModel->getKPIs($periode);
            
            // Récupérer les données pour les graphiques
            $trajetsParJour = $this->biModel->getTrajetsParJour($periode);
            $repartitionLignes = $this->biModel->getRepartitionParLigne();
            $top5Bus = $this->biModel->getTop5Bus();
            $revenusMensuels = $this->biModel->getRevenusMensuels();
            
            // Pagination pour le tableau des bus
            $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
            $parPage = 10;
            $offset = ($page - 1) * $parPage;
            
            // Récupérer les statistiques détaillées avec pagination
            $statsDetailleesParBus = $this->biModel->getStatistiquesDetailleesParBus($parPage, $offset);
            $totalBus = $this->biModel->getTotalBusActifs();
            $totalPages = ceil($totalBus / $parPage);

            // Charger la vue
            require VIEW_PATH . '/bi.php';
        } catch (Exception $e) {
            // Logger l'erreur
            error_log('Erreur BI: ' . $e->getMessage());
            
            // Initialiser les variables pour éviter les erreurs
            $kpis = [
                'bus_actifs' => 0,
                'trajets_effectues' => 0,
                'passagers' => 0,
                'revenus' => 0,
                'tendance_trajets' => 0,
                'tendance_passagers' => 0,
                'tendance_revenus' => 0
            ];
            $trajetsParJour = [];
            $repartitionLignes = [];
            $top5Bus = [];
            $revenusMensuels = [];
            $statsDetailleesParBus = [];
            $periode = 30;
            $page = 1;
            $totalPages = 0;
            
            require VIEW_PATH . '/bi.php';
        }
    }

    /**
     * Récupérer les données pour les graphiques (AJAX)
     */
    public function getDonneesGraphiques() {
        header('Content-Type: application/json');
        
        try {
            $periode = isset($_GET['periode']) ? (int)$_GET['periode'] : 30;
            $periode = in_array($periode, [7, 30, 90, 365]) ? $periode : 30;

            $data = [
                'trajets_par_jour' => $this->biModel->getTrajetsParJour($periode),
                'repartition_lignes' => $this->biModel->getRepartitionParLigne(),
                'top5_bus' => $this->biModel->getTop5Bus(),
                'revenus_mensuels' => $this->biModel->getRevenusMensuels()
            ];

            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }
}
