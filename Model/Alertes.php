<?php

class Alertes {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer les alertes avec pagination et filtres
     */
    public function getAlertes($limit = 20, $offset = 0, $filters = []) {
        $sql = "SELECT a.*, 
                       b.numero as bus_numero,
                       e.nom as membre_nom,
                       r.nom as resolu_par_nom
                FROM alertes a
                LEFT JOIN bus b ON a.bus_id = b.id
                LEFT JOIN equipe_bord e ON a.membre_id = e.id
                LEFT JOIN equipe_bord r ON a.resolu_par = r.id
                WHERE 1=1";

        $params = [];

        // Filtres
        if (!empty($filters['type_alerte'])) {
            $sql .= " AND a.type_alerte = :type_alerte";
            $params[':type_alerte'] = $filters['type_alerte'];
        }

        if (!empty($filters['statut'])) {
            $sql .= " AND a.statut = :statut";
            $params[':statut'] = $filters['statut'];
        }

        if (!empty($filters['priorite'])) {
            $sql .= " AND a.priorite = :priorite";
            $params[':priorite'] = $filters['priorite'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (a.titre LIKE :search OR a.message LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $sql .= " ORDER BY 
                  CASE a.priorite 
                    WHEN 'haute' THEN 1 
                    WHEN 'moyenne' THEN 2 
                    WHEN 'basse' THEN 3 
                  END,
                  a.date_alerte DESC 
                  LIMIT :limit OFFSET :offset";

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
     * Compter le nombre total d'alertes
     */
    public function compterAlertes($filters = []) {
        $sql = "SELECT COUNT(*) FROM alertes WHERE 1=1";
        $params = [];

        if (!empty($filters['type_alerte'])) {
            $sql .= " AND type_alerte = :type_alerte";
            $params[':type_alerte'] = $filters['type_alerte'];
        }

        if (!empty($filters['statut'])) {
            $sql .= " AND statut = :statut";
            $params[':statut'] = $filters['statut'];
        }

        if (!empty($filters['priorite'])) {
            $sql .= " AND priorite = :priorite";
            $params[':priorite'] = $filters['priorite'];
        }

        if (!empty($filters['search'])) {
            $sql .= " AND (titre LIKE :search OR message LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }

        $stmt = $this->db->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    /**
     * Récupérer les statistiques des alertes
     */
    public function getStatistiques() {
        $sql = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN type_alerte = 'critical' THEN 1 ELSE 0 END) as critiques,
                    SUM(CASE WHEN type_alerte = 'warning' THEN 1 ELSE 0 END) as avertissements,
                    SUM(CASE WHEN type_alerte = 'info' THEN 1 ELSE 0 END) as informations,
                    SUM(CASE WHEN type_alerte = 'success' THEN 1 ELSE 0 END) as succes,
                    SUM(CASE WHEN statut = 'nouveau' THEN 1 ELSE 0 END) as nouveaux,
                    SUM(CASE WHEN statut = 'en_cours' THEN 1 ELSE 0 END) as en_cours,
                    SUM(CASE WHEN statut = 'resolu' THEN 1 ELSE 0 END) as resolus
                FROM alertes";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer une alerte par ID
     */
    public function getAlerteById($id) {
        $sql = "SELECT a.*, 
                       b.numero as bus_numero,
                       e.nom as membre_nom,
                       r.nom as resolu_par_nom
                FROM alertes a
                LEFT JOIN bus b ON a.bus_id = b.id
                LEFT JOIN equipe_bord e ON a.membre_id = e.id
                LEFT JOIN equipe_bord r ON a.resolu_par = r.id
                WHERE a.id = :id";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Créer une nouvelle alerte
     */
    public function creerAlerte($data) {
        $sql = "INSERT INTO alertes (
                    type_alerte, titre, message, bus_id, membre_id, 
                    statut, priorite, localisation
                ) VALUES (
                    :type_alerte, :titre, :message, :bus_id, :membre_id,
                    :statut, :priorite, :localisation
                )";
        
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':type_alerte' => $data['type_alerte'],
            ':titre' => $data['titre'],
            ':message' => $data['message'],
            ':bus_id' => $data['bus_id'] ?? null,
            ':membre_id' => $data['membre_id'] ?? null,
            ':statut' => $data['statut'] ?? 'nouveau',
            ':priorite' => $data['priorite'] ?? 'moyenne',
            ':localisation' => $data['localisation'] ?? null
        ]);
        
        return $success ? $this->db->lastInsertId() : false;
    }

    /**
     * Mettre à jour le statut d'une alerte
     */
    public function mettreAJourStatut($id, $statut, $userId = null) {
        $sql = "UPDATE alertes SET 
                    statut = :statut,
                    date_resolution = :date_resolution,
                    resolu_par = :resolu_par
                WHERE id = :id";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':statut' => $statut,
            ':date_resolution' => ($statut === 'resolu') ? date('Y-m-d H:i:s') : null,
            ':resolu_par' => ($statut === 'resolu') ? $userId : null,
            ':id' => $id
        ]);
    }

    /**
     * Marquer toutes les alertes comme lues
     */
    public function marquerToutesCommeLues() {
        $sql = "UPDATE alertes SET statut = 'en_cours' WHERE statut = 'nouveau'";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute();
    }

    /**
     * Supprimer une alerte
     */
    public function supprimerAlerte($id) {
        $stmt = $this->db->prepare("DELETE FROM alertes WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Enregistrer l'historique d'un traitement
     */
    public function enregistrerHistorique($alerteId, $action, $details, $userId) {
        $sql = "INSERT INTO alertes_historique 
                (alerte_id, action, type_traitement, solution, raison, commentaire, traite_par) 
                VALUES 
                (:alerte_id, :action, :type_traitement, :solution, :raison, :commentaire, :traite_par)";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':alerte_id' => $alerteId,
            ':action' => $action,
            ':type_traitement' => $details['type_traitement'] ?? null,
            ':solution' => $details['solution'] ?? null,
            ':raison' => $details['raison'] ?? null,
            ':commentaire' => $details['commentaire'] ?? null,
            ':traite_par' => $userId
        ]);
    }

    /**
     * Récupérer l'historique d'une alerte
     */
    public function getHistorique($alerteId) {
        $sql = "SELECT h.*, 
                       e.nom as traite_par_nom
                FROM alertes_historique h
                LEFT JOIN equipe_bord e ON h.traite_par = e.id
                WHERE h.alerte_id = :alerte_id
                ORDER BY h.date_action DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':alerte_id' => $alerteId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter les alertes non traitées (nouveau + en_cours)
     */
    public function compterAlertesNonTraitees() {
        $sql = "SELECT COUNT(*) FROM alertes WHERE statut IN ('nouveau', 'en_cours')";
        $stmt = $this->db->query($sql);
        return (int)$stmt->fetchColumn();
    }
}
