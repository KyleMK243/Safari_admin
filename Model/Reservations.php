<?php
/**
 * Model Reservations
 * Gestion des réservations de billets
 */

class Reservations {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer les statistiques des réservations
     */
    public function getStatistiquesHistorique($filters = []) {
        $whereClause = "WHERE 1=1";
        $params = [];

        if (!empty($filters['date_debut'])) {
            $whereClause .= " AND DATE(r.date_creation) >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }

        if (!empty($filters['date_fin'])) {
            $whereClause .= " AND DATE(r.date_creation) <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }

        if (!empty($filters['trajet_id'])) {
            $whereClause .= " AND r.trajet_id = :trajet_id";
            $params[':trajet_id'] = $filters['trajet_id'];
        }

        if (!empty($filters['statut'])) {
            $whereClause .= " AND r.statut_reservation = :statut";
            $params[':statut'] = $filters['statut'];
        }

        $sql = "SELECT 
                    COUNT(*) as total_reservations,
                    SUM(r.montant_total) as montant_total,
                    AVG(r.montant_total) as montant_moyen,
                    SUM(r.nombre_places) as total_places
                FROM reservations r
                $whereClause";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $stats = $stmt->fetch(PDO::FETCH_ASSOC);

        return [
            'total_reservations' => $stats['total_reservations'] ?? 0,
            'montant_total' => $stats['montant_total'] ?? 0,
            'montant_moyen' => $stats['montant_moyen'] ?? 0,
            'total_places' => $stats['total_places'] ?? 0
        ];
    }

    /**
     * Récupérer les réservations récentes
     */
    public function getReservationsRecentes($limit = 20, $offset = 0, $filters = []) {
        $sql = "SELECT r.*,
                       t.nom as trajet_nom,
                       t.code as trajet_code,
                       c.nom as client_nom,
                       c.prenom as client_prenom,
                       c.telephone as client_telephone,
                       c.email as client_email
                FROM reservations r
                LEFT JOIN trajets t ON r.trajet_id = t.id
                LEFT JOIN clients c ON r.client_id = c.id
                WHERE 1=1";

        $params = [];

        // Filtres
        if (!empty($filters['statut'])) {
            $sql .= " AND r.statut_reservation = :statut";
            $params[':statut'] = $filters['statut'];
        }

        if (!empty($filters['date_debut'])) {
            $sql .= " AND DATE(r.date_creation) >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }

        if (!empty($filters['date_fin'])) {
            $sql .= " AND DATE(r.date_creation) <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }

        if (!empty($filters['trajet_id'])) {
            $sql .= " AND r.trajet_id = :trajet_id";
            $params[':trajet_id'] = $filters['trajet_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (r.numero_reservation LIKE :search 
                      OR c.nom LIKE :search 
                      OR c.prenom LIKE :search
                      OR c.telephone LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY r.date_creation DESC LIMIT :limit OFFSET :offset";
        
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
     * Compter les réservations
     */
    public function compterReservations($filters = []) {
        $sql = "SELECT COUNT(*) as total FROM reservations r
                LEFT JOIN clients c ON r.client_id = c.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['statut'])) {
            $sql .= " AND r.statut_reservation = :statut";
            $params[':statut'] = $filters['statut'];
        }

        if (!empty($filters['date_debut'])) {
            $sql .= " AND DATE(r.date_creation) >= :date_debut";
            $params[':date_debut'] = $filters['date_debut'];
        }

        if (!empty($filters['date_fin'])) {
            $sql .= " AND DATE(r.date_creation) <= :date_fin";
            $params[':date_fin'] = $filters['date_fin'];
        }

        if (!empty($filters['trajet_id'])) {
            $sql .= " AND r.trajet_id = :trajet_id";
            $params[':trajet_id'] = $filters['trajet_id'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (r.numero_reservation LIKE :search 
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
     * Récupérer une réservation par ID
     */
    public function getReservationById($id) {
        $sql = "SELECT r.*,
                       t.nom as trajet_nom,
                       t.code as trajet_code,
                       c.nom as client_nom,
                       c.prenom as client_prenom,
                       c.telephone as client_telephone,
                       c.email as client_email
                FROM reservations r
                LEFT JOIN trajets t ON r.trajet_id = t.id
                LEFT JOIN clients c ON r.client_id = c.id
                WHERE r.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Annuler une réservation
     */
    public function annulerReservation($id) {
        $sql = "UPDATE reservations 
                SET statut_reservation = 'annulee'
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
