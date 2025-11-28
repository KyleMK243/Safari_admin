<?php

require_once ROOT_PATH . '/Model/Dashboard.php';

class DashboardBCController {
    private $dashboardModel;

    public function __construct() {
        $this->dashboardModel = new Dashboard();
    }

    /**
     * Dashboard Bureau de conception
     * Stats maquette : lignes actives / inactives, trajets effectués / modifiés
     */
    public function index() {
        try {
            // On peut réutiliser les trajets du modèle Dashboard (maquette)
            $trajets = $this->dashboardModel->getTrajets();

            // Données complémentaires pour enrichir les infos de chaque trajet
            $buses = $this->dashboardModel->getBusActifs();
            $arrets = $this->dashboardModel->getArrets();
            $pointsShift = $this->dashboardModel->getPointsShift();

            // Initialiser les compteurs par trajet
            $infosParTrajet = [];
            foreach ($trajets as $trajet) {
                $trajetId = $trajet['id'];
                $infosParTrajet[$trajetId] = [
                    'nb_arrets' => 0,
                    'nb_points_shift' => 0,
                    'bus_actifs' => 0,
                    'bus_maintenance' => 0,
                    'bus_panne' => 0,
                ];
            }

            // Compter les arrêts par trajet
            foreach ($arrets as $arret) {
                $trajetId = $arret['trajet_id'];
                if (isset($infosParTrajet[$trajetId])) {
                    $infosParTrajet[$trajetId]['nb_arrets']++;
                }
            }

            // Compter les points de shift / prise de service par trajet
            foreach ($pointsShift as $point) {
                $trajetId = $point['trajet_id'];
                if (isset($infosParTrajet[$trajetId])) {
                    $infosParTrajet[$trajetId]['nb_points_shift']++;
                }
            }

            // Compter les bus par statut pour chaque trajet
            foreach ($buses as $bus) {
                $trajetId = $bus['trajet_id'];
                if (!isset($infosParTrajet[$trajetId])) {
                    continue;
                }

                switch ($bus['statut']) {
                    case 'actif':
                        $infosParTrajet[$trajetId]['bus_actifs']++;
                        break;
                    case 'maintenance':
                        $infosParTrajet[$trajetId]['bus_maintenance']++;
                        break;
                    case 'panne':
                        $infosParTrajet[$trajetId]['bus_panne']++;
                        break;
                }
            }

            // Enrichir chaque trajet avec son secteur et ses stats détaillées
            $secteursBC = [];
            foreach ($trajets as &$trajet) {
                $trajetId = $trajet['id'];

                // Stats détaillées (arrêts, points de shift, bus par statut)
                if (isset($infosParTrajet[$trajetId])) {
                    $trajet['stats_detaillees'] = $infosParTrajet[$trajetId];
                } else {
                    $trajet['stats_detaillees'] = [
                        'nb_arrets' => 0,
                        'nb_points_shift' => 0,
                        'bus_actifs' => 0,
                        'bus_maintenance' => 0,
                        'bus_panne' => 0,
                    ];
                }

                // Secteur maquette par trajet (à adapter plus tard avec la vraie BDD / API)
                $secteur = 'Autre secteur';
                if (isset($trajet['code'])) {
                    switch ($trajet['code']) {
                        case 'L1':
                            $secteur = 'Centre Ville / Kasapa';
                            break;
                        case 'L2':
                            $secteur = 'Kasapa / Plateau Karavia';
                            break;
                        case 'L3':
                            $secteur = 'Centre Ville / Zone Ouest';
                            break;
                    }
                }

                $trajet['secteur'] = $secteur;

                if (!in_array($secteur, $secteursBC, true)) {
                    $secteursBC[] = $secteur;
                }
            }
            unset($trajet);

            $lignesActives = count($trajets);
            $lignesInactives = 2;      // valeur de test
            $trajetsEffectues = 120;   // valeur de test
            $trajetsModifies = 8;      // valeur de test

            $statsBC = [
                'lignes_actives'    => $lignesActives,
                'lignes_inactives'  => $lignesInactives,
                'trajets_effectues' => $trajetsEffectues,
                'trajets_modifies'  => $trajetsModifies,
            ];

            // Trajets à afficher sur la carte du Bureau de conception
            $trajetsBC = $trajets;

            // Secteurs disponibles pour les filtres
            $secteursBC = $secteursBC;

            require VIEW_PATH . '/dashboard-bc.php';
        } catch (Exception $e) {
            error_log('Erreur Dashboard BC: ' . $e->getMessage());

            $statsBC = [
                'lignes_actives'    => 0,
                'lignes_inactives'  => 0,
                'trajets_effectues' => 0,
                'trajets_modifies'  => 0,
            ];

            // Aucun trajet disponible en cas d'erreur
            $trajetsBC = [];
            $secteursBC = [];

            require VIEW_PATH . '/dashboard-bc.php';
        }
    }
}
