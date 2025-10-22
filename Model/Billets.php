<?php

class Billets {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer les statistiques de billetterie
     */
    public function getStatistiques($dateDebut = null, $dateFin = null) {
        if (!$dateDebut) $dateDebut = date('Y-m-d');
        if (!$dateFin) $dateFin = date('Y-m-d');

        // Billets vendus aujourd'hui
        $sql = "SELECT COUNT(*) as total,
                       SUM(prix_paye) as revenus
                FROM billets
                WHERE DATE(date_achat) = :date
                AND statut_billet IN ('paye', 'utilise')";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':date' => $dateDebut]);
        $ventesAujourdhui = $stmt->fetch(PDO::FETCH_ASSOC);

        // Billets vendus hier (pour comparaison)
        $hier = date('Y-m-d', strtotime('-1 day'));
        $stmt->execute([':date' => $hier]);
        $ventesHier = $stmt->fetch(PDO::FETCH_ASSOC);

        // Réservations en attente
        $sql = "SELECT COUNT(*) as total FROM billets WHERE statut_billet = 'reserve'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $reservations = $stmt->fetch(PDO::FETCH_ASSOC);

        // Cartes prépayées actives
        $sql = "SELECT COUNT(*) as total FROM cartes_prepayees WHERE statut_carte = 'active'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $cartes = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calculer les tendances
        $tendanceBillets = 0;
        $tendanceRevenus = 0;
        
        if ($ventesHier['total'] > 0) {
            $tendanceBillets = (($ventesAujourdhui['total'] - $ventesHier['total']) / $ventesHier['total']) * 100;
        }
        
        if ($ventesHier['revenus'] > 0) {
            $tendanceRevenus = (($ventesAujourdhui['revenus'] - $ventesHier['revenus']) / $ventesHier['revenus']) * 100;
        }

        return [
            'billets_vendus' => $ventesAujourdhui['total'] ?? 0,
            'revenus' => $ventesAujourdhui['revenus'] ?? 0,
            'reservations' => $reservations['total'] ?? 0,
            'cartes_actives' => $cartes['total'] ?? 0,
            'tendance_billets' => round($tendanceBillets, 1),
            'tendance_revenus' => round($tendanceRevenus, 1)
        ];
    }

    /**
     * Récupérer les transactions récentes (depuis la table billets)
     */
    public function getTransactionsRecentes($limit = 20, $offset = 0) {
        $sql = "SELECT 
                    b.id as billet_id,
                    b.numero_billet,
                    b.arret_depart,
                    b.arret_arrivee,
                    b.date_voyage,
                    b.prix_paye as montant,
                    b.devise,
                    b.mode_paiement,
                    b.statut_billet,
                    b.date_achat,
                    'vente' as type_transaction,
                    CASE 
                        WHEN b.statut_billet = 'paye' THEN 'reussie'
                        WHEN b.statut_billet = 'reserve' THEN 'en_attente'
                        WHEN b.statut_billet = 'annule' THEN 'annulee'
                        ELSE 'en_attente'
                    END as statut_transaction,
                    '' as client_nom,
                    '' as client_prenom
                FROM billets b
                ORDER BY b.date_achat DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le total de transactions
     */
    public function compterTransactions() {
        $sql = "SELECT COUNT(*) as total FROM transactions_billeterie";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Récupérer les billets récents
     */
    public function getBilletsRecents($limit = 20, $offset = 0, $filters = []) {
        $sql = "SELECT b.*,
                       t.nom as trajet_nom,
                       t.code as trajet_code,
                       c.nom as client_nom,
                       c.prenom as client_prenom,
                       c.telephone as client_telephone,
                       e.nom as vendeur_nom
                FROM billets b
                LEFT JOIN trajets t ON b.trajet_id = t.id
                LEFT JOIN clients c ON b.client_id = c.id
                LEFT JOIN equipe_bord e ON b.vendu_par = e.id
                WHERE 1=1";

        $params = [];

        // Filtres
        if (!empty($filters['statut'])) {
            $sql .= " AND b.statut_billet = :statut";
            $params[':statut'] = $filters['statut'];
        }

        if (!empty($filters['date_debut'])) {
            $sql .= " AND DATE(b.date_achat) >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }

        if (!empty($filters['date_fin'])) {
            $sql .= " AND DATE(b.date_achat) <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }

        if (!empty($filters['trajet_id'])) {
            $sql .= " AND b.trajet_id = :trajet_id";
            $params[':trajet_id'] = $filters['trajet_id'];
        }

        if (!empty($filters['mode_paiement'])) {
            $sql .= " AND b.mode_paiement = :mode_paiement";
            $params[':mode_paiement'] = $filters['mode_paiement'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (b.numero_billet LIKE :search 
                      OR c.nom LIKE :search 
                      OR c.prenom LIKE :search
                      OR c.telephone LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY b.date_achat DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter les billets
     */
    public function compterBillets($filters = []) {
        $sql = "SELECT COUNT(*) as total FROM billets b
                LEFT JOIN clients c ON b.client_id = c.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['statut'])) {
            $sql .= " AND b.statut_billet = :statut";
            $params[':statut'] = $filters['statut'];
        }

        if (!empty($filters['date_debut'])) {
            $sql .= " AND DATE(b.date_achat) >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }

        if (!empty($filters['date_fin'])) {
            $sql .= " AND DATE(b.date_achat) <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }

        if (!empty($filters['trajet_id'])) {
            $sql .= " AND b.trajet_id = :trajet_id";
            $params[':trajet_id'] = $filters['trajet_id'];
        }

        if (!empty($filters['mode_paiement'])) {
            $sql .= " AND b.mode_paiement = :mode_paiement";
            $params[':mode_paiement'] = $filters['mode_paiement'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (b.numero_billet LIKE :search 
                      OR c.nom LIKE :search 
                      OR c.prenom LIKE :search
                      OR c.telephone LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    /**
     * Récupérer un billet par ID
     */
    public function getBilletById($id) {
        $sql = "SELECT b.*,
                       t.nom as trajet_nom,
                       t.code as trajet_code,
                       c.nom as client_nom,
                       c.prenom as client_prenom,
                       c.telephone as client_telephone,
                       c.email as client_email
                FROM billets b
                LEFT JOIN trajets t ON b.trajet_id = t.id
                LEFT JOIN clients c ON b.client_id = c.id
                WHERE b.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Annuler un billet
     */
    public function annulerBillet($id, $motif, $userId) {
        $sql = "UPDATE billets 
                SET statut_billet = 'annule',
                    date_annulation = NOW(),
                    motif_annulation = :motif
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':motif' => $motif
        ]);
    }

    /**
     * Récupérer les bus disponibles pour un trajet (sans calcul de prix)
     */
    public function getBusDisponiblesPourTrajet($trajetId, $typeTarif = 'normal', $dateVoyage = null) {
        // Si pas de date spécifiée, utiliser aujourd'hui
        if (!$dateVoyage) {
            $dateVoyage = date('Y-m-d');
        }
        
        $sql = "
            SELECT 
                b.id,
                b.numero,
                b.marque,
                b.modele,
                b.capacite,
                b.statut,
                tr.nom as trajet_nom,
                tr.distance_totale,
                COALESCE(t.prix, 0) as prix_par_km,
                COALESCE(t.devise, 'CDF') as devise,
                COALESCE(COUNT(bil.id), 0) as billets_vendus,
                (b.capacite - COALESCE(COUNT(bil.id), 0)) as places_disponibles
            FROM bus b
            INNER JOIN trajets tr ON b.trajet_id = tr.id
            LEFT JOIN tarifs t ON t.trajet_id = tr.id 
                AND t.type_tarif = :type_tarif 
                AND t.statut = 'actif'
            LEFT JOIN billets bil ON bil.bus_id = b.id
                AND bil.date_voyage = :date_voyage
                AND bil.statut_billet IN ('reserve', 'paye', 'utilise')
            WHERE b.trajet_id = :trajet_id
                AND b.statut = 'actif'
                AND tr.statut = 'actif'
            GROUP BY b.id, b.numero, b.marque, b.modele, b.capacite, b.statut, 
                     tr.nom, tr.distance_totale, t.prix, t.devise
            HAVING places_disponibles > 0
            ORDER BY places_disponibles DESC, b.numero ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':trajet_id', $trajetId, PDO::PARAM_INT);
        $stmt->bindValue(':type_tarif', $typeTarif, PDO::PARAM_STR);
        $stmt->bindValue(':date_voyage', $dateVoyage, PDO::PARAM_STR);
        $stmt->execute();
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un billet par son ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM billets WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les statistiques pour l'historique des ventes
     */
    public function getStatistiquesHistorique($filters = []) {
        $whereClause = "WHERE 1=1";
        $params = [];

        if (!empty($filters['date_debut'])) {
            $whereClause .= " AND DATE(b.date_achat) >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }

        if (!empty($filters['date_fin'])) {
            $whereClause .= " AND DATE(b.date_achat) <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }

        if (!empty($filters['trajet_id'])) {
            $whereClause .= " AND b.trajet_id = :trajet_id";
            $params[':trajet_id'] = $filters['trajet_id'];
        }

        if (!empty($filters['mode_paiement'])) {
            $whereClause .= " AND b.mode_paiement = :mode_paiement";
            $params[':mode_paiement'] = $filters['mode_paiement'];
        }

        $sql = "SELECT 
                    COUNT(*) as total_billets,
                    SUM(b.prix_paye) as revenus_totaux,
                    AVG(b.prix_paye) as prix_moyen,
                    COUNT(DISTINCT b.bus_id) as total_bus
                FROM billets b
                $whereClause
                AND b.statut_billet IN ('paye', 'utilise')";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calculer le taux d'occupation (estimation)
        $sqlCapacite = "SELECT SUM(bus.capacite) as capacite_totale
                        FROM billets b
                        LEFT JOIN bus ON b.bus_id = bus.id
                        $whereClause
                        AND b.statut_billet IN ('paye', 'utilise')";
        
        $stmt = $this->db->prepare($sqlCapacite);
        $stmt->execute($params);
        $capacite = $stmt->fetch(PDO::FETCH_ASSOC);

        $tauxOccupation = 0;
        if ($capacite['capacite_totale'] > 0) {
            $tauxOccupation = ($stats['total_billets'] / $capacite['capacite_totale']) * 100;
        }

        return [
            'total_billets' => $stats['total_billets'] ?? 0,
            'revenus_totaux' => $stats['revenus_totaux'] ?? 0,
            'prix_moyen' => $stats['prix_moyen'] ?? 0,
            'taux_occupation' => round($tauxOccupation, 0)
        ];
    }

    /**
     * Créer un nouveau billet
     */
    public function creerBillet($data) {
        // Générer le numéro de billet
        $numeroBillet = 'BT-' . date('Y') . '-' . str_pad(rand(1, 999999), 6, '0', STR_PAD_LEFT);
        
        $sql = "INSERT INTO billets (
                    numero_billet, trajet_id, tarif_id, bus_id,
                    arret_depart, arret_arrivee, date_voyage, heure_depart,
                    prix_paye, devise, statut_billet, mode_paiement,
                    date_achat
                ) VALUES (
                    :numero_billet, :trajet_id, :tarif_id, :bus_id,
                    :arret_depart, :arret_arrivee, :date_voyage, :heure_depart,
                    :prix_paye, :devise, 'paye', :mode_paiement,
                    NOW()
                )";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':numero_billet' => $numeroBillet,
            ':trajet_id' => $data['trajet_id'],
            ':tarif_id' => $data['tarif_id'] ?? 1,
            ':bus_id' => $data['bus_id'],
            ':arret_depart' => $data['arret_depart'],
            ':arret_arrivee' => $data['arret_arrivee'],
            ':date_voyage' => $data['date_voyage'],
            ':heure_depart' => $data['heure_depart'] ?? null,
            ':prix_paye' => $data['prix_paye'],
            ':devise' => $data['devise'] ?? 'CDF',
            ':mode_paiement' => $data['mode_paiement']
        ]);
        
        return $this->db->lastInsertId();
    }
}
