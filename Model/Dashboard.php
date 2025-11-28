<?php

class Dashboard {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer tous les bus actifs avec leurs informations
     */
    public function getBusActifs() {
        $buses = [
            [
                'id' => 421,
                'numero' => '421',
                'immatriculation' => 'CD-421-ABC',
                'marque' => 'Mercedes',
                'modele' => 'Citaro',
                'capacite' => 80,
                'kilometrage' => 123456,
                'trajet_id' => 1,
                'statut' => 'actif',
                'modules' => 'DATCHA,WIFI,POS',
                'derniere_activite' => date('Y-m-d H:i:s', strtotime('-5 minutes')),
                'latitude' => null,
                'longitude' => null,
                'trajet_nom' => 'Centre Ville - Kasapa',
                'trajet_code' => 'L1',
                'distance_totale' => 14.5
            ],
            [
                'id' => 105,
                'numero' => '105',
                'immatriculation' => 'CD-105-DEF',
                'marque' => 'Toyota',
                'modele' => 'Coaster',
                'capacite' => 60,
                'kilometrage' => 98765,
                'trajet_id' => 1,
                'statut' => 'actif',
                'modules' => 'DATCHA,POS',
                'derniere_activite' => date('Y-m-d H:i:s', strtotime('-12 minutes')),
                'latitude' => null,
                'longitude' => null,
                'trajet_nom' => 'Centre Ville - Kasapa',
                'trajet_code' => 'L1',
                'distance_totale' => 14.5
            ],
            [
                'id' => 202,
                'numero' => '202',
                'immatriculation' => 'CD-202-GHI',
                'marque' => 'Hyundai',
                'modele' => 'County',
                'capacite' => 55,
                'kilometrage' => 150230,
                'trajet_id' => 2,
                'statut' => 'maintenance',
                'modules' => 'DATCHA',
                'derniere_activite' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'latitude' => null,
                'longitude' => null,
                'trajet_nom' => 'Kasapa - Plateau Karavia',
                'trajet_code' => 'L2',
                'distance_totale' => 18.2
            ],
            [
                'id' => 512,
                'numero' => '512',
                'immatriculation' => 'CD-512-JKL',
                'marque' => 'Mercedes',
                'modele' => 'Sprinter',
                'capacite' => 40,
                'kilometrage' => 201500,
                'trajet_id' => 2,
                'statut' => 'panne',
                'modules' => 'DATCHA,WIFI',
                'derniere_activite' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'latitude' => null,
                'longitude' => null,
                'trajet_nom' => 'Kasapa - Plateau Karavia',
                'trajet_code' => 'L2',
                'distance_totale' => 18.2
            ],
            [
                'id' => 238,
                'numero' => '238',
                'immatriculation' => 'CD-238-MNO',
                'marque' => 'Mercedes',
                'modele' => 'Citaro',
                'capacite' => 80,
                'kilometrage' => 110000,
                'trajet_id' => 3,
                'statut' => 'actif',
                'modules' => 'DATCHA,WIFI',
                'derniere_activite' => date('Y-m-d H:i:s', strtotime('-20 minutes')),
                'latitude' => null,
                'longitude' => null,
                'trajet_nom' => 'Centre Ville - Zone Ouest',
                'trajet_code' => 'L3',
                'distance_totale' => 12.8
            ]
        ];

        $busAvecPosition = 0;
        $busSansPosition = 0;

        foreach ($buses as &$bus) {
            // Si le bus a des coordonnées GPS, les utiliser
            if (!empty($bus['latitude']) && !empty($bus['longitude'])) {
                $bus['position'] = [
                    'lat' => (float)$bus['latitude'],
                    'lng' => (float)$bus['longitude'],
                    'vitesse' => '-',
                    'carburant' => '-',
                    'temperature' => '-',
                    'localisation' => $this->genererNomLocalisation()
                ];
                $busAvecPosition++;
            } else {
                // Sinon, simuler une position
                $bus['position'] = $this->simulerPositionBus($bus);
                $busSansPosition++;
            }
        }

        return $buses;
    }

    /**
     * Récupérer tous les trajets avec leurs points
     */
    public function getTrajets() {
        $trajets = [
            [
                'id' => 1,
                'code' => 'L1',
                'nom' => 'Centre Ville - Kasapa',
                'distance_totale' => 14.5,
                'duree_estimee' => 35,
                'statut' => 'actif',
                'latitude_depart' => -11.666,
                'longitude_depart' => 27.480,
                'latitude_arrivee' => -11.675,
                'longitude_arrivee' => 27.500
            ],
            [
                'id' => 2,
                'code' => 'L2',
                'nom' => 'Kasapa - Plateau Karavia',
                'distance_totale' => 18.2,
                'duree_estimee' => 40,
                'statut' => 'actif',
                'latitude_depart' => -11.675,
                'longitude_depart' => 27.500,
                'latitude_arrivee' => -11.690,
                'longitude_arrivee' => 27.520
            ],
            [
                'id' => 3,
                'code' => 'L3',
                'nom' => 'Centre Ville - Zone Ouest',
                'distance_totale' => 12.8,
                'duree_estimee' => 30,
                'statut' => 'actif',
                'latitude_depart' => -11.660,
                'longitude_depart' => 27.470,
                'latitude_arrivee' => -11.670,
                'longitude_arrivee' => 27.450
            ]
        ];

        // Ajouter les coordonnées de départ et d'arrivée
        foreach ($trajets as &$trajet) {
            // Si les coordonnées GPS existent dans la BDD, les utiliser
            if (!empty($trajet['latitude_depart']) && !empty($trajet['longitude_depart']) &&
                !empty($trajet['latitude_arrivee']) && !empty($trajet['longitude_arrivee'])) {

                $trajet['coordonnees'] = [
                    'depart' => [
                        'lat' => (float)$trajet['latitude_depart'],
                        'lng' => (float)$trajet['longitude_depart']
                    ],
                    'arrivee' => [
                        'lat' => (float)$trajet['latitude_arrivee'],
                        'lng' => (float)$trajet['longitude_arrivee']
                    ]
                ];
            } else {
                // Sinon, générer des coordonnées simulées
                $trajet['coordonnees'] = $this->genererCoordonneesTrajet($trajet['id']);
            }
        }

        return $trajets;
    }

    /**
     * Récupérer tous les points de shift
     */
    public function getPointsShift() {
        $points = [
            [
                'id' => 1,
                'trajet_id' => 1,
                'nom' => 'Shift Matin - Centre Ville',
                'distance_avec_debut' => 5,
                'trajet_nom' => 'Centre Ville - Kasapa'
            ],
            [
                'id' => 2,
                'trajet_id' => 1,
                'nom' => 'Shift Soir - Kasapa',
                'distance_avec_debut' => 12,
                'trajet_nom' => 'Centre Ville - Kasapa'
            ],
            [
                'id' => 3,
                'trajet_id' => 2,
                'nom' => 'Shift Matin - Plateau Karavia',
                'distance_avec_debut' => 6,
                'trajet_nom' => 'Kasapa - Plateau Karavia'
            ]
        ];

        // Ajouter des coordonnées pour chaque point
        foreach ($points as &$point) {
            $point['coordonnees'] = $this->calculerPositionSurTrajet(
                $point['trajet_id'],
                $point['distance_avec_debut']
            );
        }

        return $points;
    }

    /**
     * Récupérer tous les arrêts
     */
    public function getArrets() {
        $arrets = [
            [
                'id' => 1,
                'trajet_id' => 1,
                'nom' => 'Arrêt Gecamine',
                'distance_avec_debut' => 3,
                'temps_arret' => 2,
                'trajet_nom' => 'Centre Ville - Kasapa'
            ],
            [
                'id' => 2,
                'trajet_id' => 1,
                'nom' => 'Arrêt Golf',
                'distance_avec_debut' => 8,
                'temps_arret' => 3,
                'trajet_nom' => 'Centre Ville - Kasapa'
            ],
            [
                'id' => 3,
                'trajet_id' => 2,
                'nom' => 'Arrêt Kasapa',
                'distance_avec_debut' => 4,
                'temps_arret' => 2,
                'trajet_nom' => 'Kasapa - Plateau Karavia'
            ]
        ];

        // Ajouter des coordonnées pour chaque arrêt
        foreach ($arrets as &$arret) {
            $arret['coordonnees'] = $this->calculerPositionSurTrajet(
                $arret['trajet_id'],
                $arret['distance_avec_debut']
            );
        }

        return $arrets;
    }

    /**
     * Récupérer les informations détaillées d'un bus
     */
    public function getDetailsBus($busId) {
        $buses = $this->getBusActifs();
        $bus = null;

        foreach ($buses as $item) {
            if ((int)$item['id'] === (int)$busId) {
                $bus = $item;
                break;
            }
        }

        if ($bus) {
            $bus['equipes'] = [
                [
                    'id' => 1,
                    'nom' => 'Jean-Pierre Mukendi',
                    'matricule' => 'DRV-2024-158',
                    'poste' => 'chauffeur',
                    'telephone' => '+243 812 345 678',
                    'email' => 'jean.mukendi@example.com'
                ],
                [
                    'id' => 2,
                    'nom' => 'Marie Tshala',
                    'matricule' => 'RCV-2024-089',
                    'poste' => 'receveur',
                    'telephone' => '+243 823 456 789',
                    'email' => 'marie.tshala@example.com'
                ]
            ];
            $bus['position'] = $this->simulerPositionBus($bus);
        }

        return $bus;
    }

    /**
     * Récupérer les équipes d'un bus (2 shifts)
     */
    private function getEquipesBus($busNumero) {
        $sql = "SELECT 
                    e.id,
                    e.nom,
                    e.matricule,
                    e.poste,
                    e.telephone,
                    e.email
                FROM equipe_bord e
                WHERE e.bus_affecte = :bus_numero
                AND e.statut = 'actif'
                ORDER BY 
                    FIELD(e.poste, 'chauffeur', 'controleur', 'receveur')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':bus_numero' => $busNumero]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Simuler la position d'un bus sur son trajet
     */
    private function simulerPositionBus($bus) {
        // Position de base (Kinshasa centre)
        $baseLat = -4.3276;
        $baseLng = 15.3136;
        
        // Générer une position aléatoire dans un rayon de 10km
        $radius = 0.1; // ~10km
        $angle = mt_rand(0, 360) * (M_PI / 180);
        $distance = (mt_rand(0, 100) / 100) * $radius;
        
        $lat = $baseLat + ($distance * cos($angle));
        $lng = $baseLng + ($distance * sin($angle));
        
        // Simuler d'autres données
        $vitesse = $bus['statut'] === 'actif' ? mt_rand(20, 60) : 0;
        $carburant = mt_rand(30, 95);
        $temperature = mt_rand(18, 28);
        
        return [
            'lat' => $lat,
            'lng' => $lng,
            'vitesse' => $vitesse,
            'carburant' => $carburant,
            'temperature' => $temperature,
            'localisation' => $this->genererNomLocalisation()
        ];
    }

    /**
     * Générer des coordonnées pour un trajet
     */
    private function genererCoordonneesTrajet($trajetId) {
        $baseLat = -11.6667;
        $baseLng = 27.4833;
        
        // Générer un point de départ et d'arrivée
        $depart = [
            'lat' => $baseLat + (mt_rand(-50, 50) / 1000),
            'lng' => $baseLng + (mt_rand(-50, 50) / 1000)
        ];
        
        $arrivee = [
            'lat' => $baseLat + (mt_rand(-100, 100) / 1000),
            'lng' => $baseLng + (mt_rand(-100, 100) / 1000)
        ];
        
        // Générer des points intermédiaires
        $points = [$depart];
        $nbPoints = mt_rand(3, 6);
        
        for ($i = 1; $i < $nbPoints; $i++) {
            $ratio = $i / $nbPoints;
            $points[] = [
                'lat' => $depart['lat'] + ($arrivee['lat'] - $depart['lat']) * $ratio + (mt_rand(-20, 20) / 1000),
                'lng' => $depart['lng'] + ($arrivee['lng'] - $depart['lng']) * $ratio + (mt_rand(-20, 20) / 1000)
            ];
        }
        
        $points[] = $arrivee;
        
        return $points;
    }

    /**
     * Calculer la position d'un point sur un trajet
     */
    private function calculerPositionSurTrajet($trajetId, $distance) {
        $coordonnees = $this->genererCoordonneesTrajet($trajetId);
        
        if (empty($coordonnees)) {
            return ['lat' => -11.6667, 'lng' => 27.4833];
        }
        
        // Retourner un point aléatoire sur le trajet
        $index = mt_rand(0, count($coordonnees) - 1);
        return $coordonnees[$index];
    }

    /**
     * Générer un nom de localisation aléatoire
     */
    private function genererNomLocalisation() {
        $localisations = [
            'Centre Ville',
            'Lemba',
            'Kalamu',
            'Ngaliema',
            'Kasapa',
            'Plateau Karavia',
            'Bel-air Camp',
            'Gecamine',
            'Kalubwe',
            'Golf Maisha',
            'Kamalondo',
            'Kenya',
            'Katuba'
        ];
        
        return $localisations[array_rand($localisations)];
    }

    /**
     * Récupérer les zones géographiques
     */
    public function getZones() {
        return [
            [
                'id' => 1,
                'nom' => 'Centre Ville',
                'bounds' => [
                    ['lat' => -11.65, 'lng' => 27.47],
                    ['lat' => -11.65, 'lng' => 27.49],
                    ['lat' => -11.67, 'lng' => 27.49],
                    ['lat' => -11.67, 'lng' => 27.47]
                ],
                'couleur' => 'rgba(59, 130, 246, 0.1)'
            ],
            [
                'id' => 2,
                'nom' => 'Zone Est',
                'bounds' => [
                    ['lat' => -11.65, 'lng' => 27.49],
                    ['lat' => -11.65, 'lng' => 27.51],
                    ['lat' => -11.67, 'lng' => 27.51],
                    ['lat' => -11.67, 'lng' => 27.49]
                ],
                'couleur' => 'rgba(34, 197, 94, 0.1)'
            ],
            [
                'id' => 3,
                'nom' => 'Zone Ouest',
                'bounds' => [
                    ['lat' => -11.65, 'lng' => 27.45],
                    ['lat' => -11.65, 'lng' => 27.47],
                    ['lat' => -11.67, 'lng' => 27.47],
                    ['lat' => -11.67, 'lng' => 27.45]
                ],
                'couleur' => 'rgba(245, 158, 11, 0.1)'
            ]
        ];
    }

    /**
     * Récupérer les statistiques du jour
     */
    public function getStatistiquesJour() {
        $buses = $this->getBusActifs();
        $busActifs = 0;

        foreach ($buses as $bus) {
            if ($bus['statut'] === 'actif') {
                $busActifs++;
            }
        }

        return [
            'bus_actifs' => $busActifs,
            'shifts_actifs' => 4,
            'passagers' => 320,
            'revenus' => 125000
        ];
    }
}
