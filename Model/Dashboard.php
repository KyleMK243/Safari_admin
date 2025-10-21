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
        try {
            error_log("=== MODEL: getBusActifs appelé ===");
            
            $sql = "SELECT 
                        b.id,
                        b.numero,
                        b.immatriculation,
                        b.marque,
                        b.modele,
                        b.capacite,
                        b.kilometrage,
                        b.ligne_affectee,
                        b.statut,
                        b.modules,
                        b.derniere_activite,
                        b.latitude,
                        b.longitude,
                        t.nom as trajet_nom,
                        t.code as trajet_code,
                        t.distance_totale,
                        COALESCE(
                            (SELECT e.nom 
                             FROM equipe_bord e 
                             WHERE e.bus_affecte = b.numero 
                             AND e.poste = 'chauffeur'
                             AND e.statut = 'actif'
                             LIMIT 1), 
                            'Non assigné'
                        ) as chauffeur_nom
                    FROM bus b
                    LEFT JOIN trajets t ON b.ligne_affectee = t.id
                    WHERE b.statut IN ('actif', 'maintenance', 'panne')
                    ORDER BY b.numero ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $buses = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Nombre de bus trouvés dans la BDD: " . count($buses));
        } catch (PDOException $e) {
            error_log("❌ ERREUR SQL getBusActifs: " . $e->getMessage());
            error_log("Code erreur: " . $e->getCode());
            return [];
        }
        
        // Ajouter les données de position pour chaque bus
        $busAvecPosition = 0;
        $busSansPosition = 0;
        
        foreach ($buses as &$bus) {
            // Si le bus a des coordonnées GPS, les utiliser
            if (!empty($bus['latitude']) && !empty($bus['longitude'])) {
                $bus['position'] = [
                    'lat' => (float)$bus['latitude'],
                    'lng' => (float)$bus['longitude'],
                    'vitesse' => '-',  // Non disponible pour l'instant
                    'carburant' => '-',  // Non disponible pour l'instant
                    'temperature' => '-',  // Non disponible pour l'instant
                    'localisation' => $this->genererNomLocalisation()
                ];
                $busAvecPosition++;
                error_log("✅ Bus #{$bus['numero']}: Position GPS ({$bus['latitude']}, {$bus['longitude']})");
            } else {
                // Sinon, simuler une position
                $bus['position'] = $this->simulerPositionBus($bus);
                $busSansPosition++;
                error_log("⚠️ Bus #{$bus['numero']}: Pas de GPS, position simulée");
            }
        }
        
        error_log("=== DASHBOARD: {$busAvecPosition} bus avec GPS, {$busSansPosition} bus simulés ===");
        
        return $buses;
    }

    /**
     * Récupérer tous les trajets avec leurs points
     */
    public function getTrajets() {
        $sql = "SELECT 
                    id,
                    code,
                    nom,
                    distance_totale,
                    duree_estimee,
                    statut,
                    latitude_depart,
                    longitude_depart,
                    latitude_arrivee,
                    longitude_arrivee
                FROM trajets
                WHERE statut = 'actif'
                ORDER BY id ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $trajets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
        $sql = "SELECT 
                    ps.id,
                    ps.trajet_id,
                    ps.nom,
                    ps.distance_avec_debut,
                    t.nom as trajet_nom
                FROM points_chifte ps
                LEFT JOIN trajets t ON ps.trajet_id = t.id
                ORDER BY ps.trajet_id, ps.distance_avec_debut ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $points = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
        $sql = "SELECT 
                    a.id,
                    a.trajet_id,
                    a.nom,
                    a.distance_avec_debut,
                    a.temps_arret,
                    t.nom as trajet_nom
                FROM arrets a
                LEFT JOIN trajets t ON a.trajet_id = t.id
                ORDER BY a.trajet_id, a.distance_avec_debut ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $arrets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
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
        $sql = "SELECT 
                    b.*,
                    t.nom as trajet_nom,
                    t.code as trajet_code,
                    t.distance_totale,
                    t.duree_estimee
                FROM bus b
                LEFT JOIN trajets t ON b.ligne_affectee = t.id
                WHERE b.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $busId]);
        $bus = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($bus) {
            // Récupérer les équipes
            $bus['equipes'] = $this->getEquipesBus($bus['numero']);
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
        $today = date('Y-m-d');
        
        // Bus actifs
        $sql = "SELECT COUNT(*) as total FROM bus WHERE statut = 'actif'";
        $stmt = $this->db->query($sql);
        $busActifs = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Shifts en cours
        $sql = "SELECT COUNT(*) as total FROM shifts 
                WHERE date_prevue = :today AND statut = 'actif'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':today' => $today]);
        $shiftsActifs = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Passagers du jour
        $sql = "SELECT COUNT(*) as total FROM billets 
                WHERE DATE(date_achat) = :today 
                AND statut_billet IN ('paye', 'utilise')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':today' => $today]);
        $passagers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        // Revenus du jour
        $sql = "SELECT COALESCE(SUM(prix_paye), 0) as total FROM billets 
                WHERE DATE(date_achat) = :today 
                AND statut_billet IN ('paye', 'utilise')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':today' => $today]);
        $revenus = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        return [
            'bus_actifs' => $busActifs,
            'shifts_actifs' => $shiftsActifs,
            'passagers' => $passagers,
            'revenus' => $revenus
        ];
    }
}
