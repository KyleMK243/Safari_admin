<?php

class Shifts {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Trouver un shift existant pour un bus/date/horaire donné
     */
    public function trouverShiftExistant($busNumero, $datePrevue, $heureDebut, $heureFin) {
        $sql = "
            SELECT id, chauffeur_id, controleur_id, receveur_id, trajet_id, statut
            FROM shifts
            WHERE bus_numero = ?
              AND date_prevue = ?
              AND heure_debut = ?
              AND heure_fin = ?
              AND statut IN ('planifie', 'actif')
            LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$busNumero, $datePrevue, $heureDebut, $heureFin]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Créer un nouveau shift
     */
    public function creerShift($donnees) {
        $sql = "
            INSERT INTO shifts (
                bus_numero, date_prevue, heure_debut, heure_fin,
                chauffeur_id, controleur_id, receveur_id, trajet_id, statut, notes
            ) VALUES (
                :bus_numero, :date_prevue, :heure_debut, :heure_fin,
                :chauffeur_id, :controleur_id, :receveur_id, :trajet_id, :statut, :notes
            )
        ";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute($donnees);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Mettre à jour un shift existant avec de nouveaux membres
     */
    public function mettreAJourShift($shiftId, $chauffeurId = null, $controleurId = null, $receveurId = null) {
        $updates = [];
        $params = [];

        if ($chauffeurId !== null) {
            $updates[] = "chauffeur_id = ?";
            $params[] = $chauffeurId;
        }

        if ($controleurId !== null) {
            $updates[] = "controleur_id = ?";
            $params[] = $controleurId;
        }

        if ($receveurId !== null) {
            $updates[] = "receveur_id = ?";
            $params[] = $receveurId;
        }

        if (empty($updates)) {
            return false;
        }

        $params[] = $shiftId;
        $sql = "UPDATE shifts SET " . implode(', ', $updates) . " WHERE id = ?";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    /**
     * Vérifier si un membre a un conflit horaire
     * Retourne true si conflit détecté, false sinon
     */
    public function verifierConflitHoraire($membreId, $date, $heureDebut, $heureFin, $excludeShiftId = null) {
        $sql = "
            SELECT s.id, s.bus_numero, s.heure_debut, s.heure_fin,
                   CASE 
                       WHEN s.chauffeur_id = ? THEN 'chauffeur'
                       WHEN s.controleur_id = ? THEN 'controleur'
                       WHEN s.receveur_id = ? THEN 'receveur'
                   END as role
            FROM shifts s
            WHERE (s.chauffeur_id = ? 
                   OR s.controleur_id = ? 
                   OR s.receveur_id = ?)
              AND s.date_prevue = ?
              AND s.statut IN ('planifie', 'actif')
              AND (
                  (s.heure_debut < ? AND s.heure_fin > ?)
              )
        ";

        $params = [$membreId, $membreId, $membreId, $membreId, $membreId, $membreId, $date, $heureFin, $heureDebut];

        if ($excludeShiftId) {
            $sql .= " AND s.id != ?";
            $params[] = $excludeShiftId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $conflits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return count($conflits) > 0 ? $conflits : false;
    }

    /**
     * Vérifier si un bus a un conflit horaire
     */
    public function verifierConflitBus($busNumero, $date, $heureDebut, $heureFin, $excludeShiftId = null) {
        $sql = "
            SELECT id, heure_debut, heure_fin
            FROM shifts
            WHERE bus_numero = ?
              AND date_prevue = ?
              AND statut IN ('planifie', 'actif')
              AND (
                  (heure_debut < ? AND heure_fin > ?)
              )
        ";

        $params = [$busNumero, $date, $heureFin, $heureDebut];

        if ($excludeShiftId) {
            $sql .= " AND id != ?";
            $params[] = $excludeShiftId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $conflits = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return count($conflits) > 0 ? $conflits : false;
    }

    /**
     * Récupérer tous les shifts avec pagination et filtres
     */
    public function getShiftsAvecFiltres($filtreStatut = null, $filtreDate = null, $filtreBus = null, $limit = 50, $offset = 0) {
        $sql = "
            SELECT 
                s.*,
                b.immatriculation as bus_immatriculation,
                c.nom as chauffeur_nom,
                co.nom as controleur_nom,
                r.nom as receveur_nom,
                t.nom as trajet_nom
            FROM shifts s
            LEFT JOIN bus b ON s.bus_numero = b.numero
            LEFT JOIN equipe_bord c ON s.chauffeur_id = c.id
            LEFT JOIN equipe_bord co ON s.controleur_id = co.id
            LEFT JOIN equipe_bord r ON s.receveur_id = r.id
            LEFT JOIN trajets t ON s.trajet_id = t.id
            WHERE 1=1
        ";

        $params = [];

        if ($filtreStatut) {
            $sql .= " AND s.statut = ?";
            $params[] = $filtreStatut;
        }

        if ($filtreDate) {
            $sql .= " AND s.date_prevue = ?";
            $params[] = $filtreDate;
        }

        if ($filtreBus) {
            $sql .= " AND s.bus_numero = ?";
            $params[] = $filtreBus;
        }

        $sql .= " ORDER BY s.date_prevue DESC, s.heure_debut DESC LIMIT ? OFFSET ?";
        $params[] = $limit;
        $params[] = $offset;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un shift par ID
     */
    public function getShiftParId($id) {
        $sql = "
            SELECT 
                s.*,
                b.immatriculation as bus_immatriculation,
                c.nom as chauffeur_nom,
                co.nom as controleur_nom,
                r.nom as receveur_nom,
                t.nom as trajet_nom
            FROM shifts s
            LEFT JOIN bus b ON s.bus_numero = b.numero
            LEFT JOIN equipe_bord c ON s.chauffeur_id = c.id
            LEFT JOIN equipe_bord co ON s.controleur_id = co.id
            LEFT JOIN equipe_bord r ON s.receveur_id = r.id
            LEFT JOIN trajets t ON s.trajet_id = t.id
            WHERE s.id = ?
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Mettre à jour le statut d'un shift
     */
    public function changerStatut($shiftId, $statut) {
        $sql = "UPDATE shifts SET statut = :statut WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['statut' => $statut, 'id' => $shiftId]);
    }

    /**
     * Annuler un shift
     */
    public function annulerShift($shiftId, $motif = null) {
        $sql = "UPDATE shifts SET statut = 'annule', notes = CONCAT(COALESCE(notes, ''), '\nAnnulé: ', :motif) WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['motif' => $motif ?? 'Non spécifié', 'id' => $shiftId]);
    }

    /**
     * Compter le nombre total de shifts
     */
    public function compterShifts($filtreStatut = null, $filtreDate = null) {
        $sql = "SELECT COUNT(*) FROM shifts WHERE 1=1";
        $params = [];

        if ($filtreStatut) {
            $sql .= " AND statut = ?";
            $params[] = $filtreStatut;
        }

        if ($filtreDate) {
            $sql .= " AND date_prevue = ?";
            $params[] = $filtreDate;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Générer des suggestions de shifts basées sur l'historique
     */
    public function genererSuggestions($date = null, $limit = 10) {
        if (!$date) {
            $date = date('Y-m-d', strtotime('+1 day'));
        }

        // D'abord, essayer de trouver des suggestions basées sur l'historique
        $suggestions = $this->getSuggestionsFromHistory($date, $limit);
        
        // Si pas assez de suggestions, compléter avec des suggestions aléatoires
        if (count($suggestions) < $limit) {
            $randomSuggestions = $this->getRandomSuggestions($date, $limit - count($suggestions));
            $suggestions = array_merge($suggestions, $randomSuggestions);
        }
        
        return $suggestions;
    }

    /**
     * Suggestions basées sur l'historique
     */
    private function getSuggestionsFromHistory($date, $limit) {
        try {
            $sql = "
            SELECT 
                s.bus_numero,
                s.heure_debut,
                s.heure_fin,
                s.trajet_id,
                t.nom as trajet_nom,
                b.immatriculation as bus_immatriculation,
                -- Chauffeur le plus fréquent pour ce bus
                (SELECT eb.id 
                 FROM equipe_bord eb 
                 INNER JOIN shifts s2 ON s2.chauffeur_id = eb.id 
                 WHERE s2.bus_numero = s.bus_numero 
                   AND eb.poste = 'chauffeur' 
                   AND eb.statut = 'actif'
                   AND s2.statut = 'termine'
                   AND s2.date_prevue >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY eb.id 
                 ORDER BY COUNT(*) DESC 
                 LIMIT 1
                ) as chauffeur_suggere_id,
                (SELECT eb.nom 
                 FROM equipe_bord eb 
                 INNER JOIN shifts s2 ON s2.chauffeur_id = eb.id 
                 WHERE s2.bus_numero = s.bus_numero 
                   AND eb.poste = 'chauffeur' 
                   AND eb.statut = 'actif'
                   AND s2.statut = 'termine'
                   AND s2.date_prevue >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY eb.id 
                 ORDER BY COUNT(*) DESC 
                 LIMIT 1
                ) as chauffeur_nom,
                -- Contrôleur le plus fréquent
                (SELECT eb.id 
                 FROM equipe_bord eb 
                 INNER JOIN shifts s2 ON s2.controleur_id = eb.id 
                 WHERE s2.bus_numero = s.bus_numero 
                   AND eb.poste = 'controleur' 
                   AND eb.statut = 'actif'
                   AND s2.statut = 'termine'
                   AND s2.date_prevue >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY eb.id 
                 ORDER BY COUNT(*) DESC 
                 LIMIT 1
                ) as controleur_suggere_id,
                (SELECT eb.nom 
                 FROM equipe_bord eb 
                 INNER JOIN shifts s2 ON s2.controleur_id = eb.id 
                 WHERE s2.bus_numero = s.bus_numero 
                   AND eb.poste = 'controleur' 
                   AND eb.statut = 'actif'
                   AND s2.statut = 'termine'
                   AND s2.date_prevue >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY eb.id 
                 ORDER BY COUNT(*) DESC 
                 LIMIT 1
                ) as controleur_nom,
                -- Receveur le plus fréquent
                (SELECT eb.id 
                 FROM equipe_bord eb 
                 INNER JOIN shifts s2 ON s2.receveur_id = eb.id 
                 WHERE s2.bus_numero = s.bus_numero 
                   AND eb.poste = 'receveur' 
                   AND eb.statut = 'actif'
                   AND s2.statut = 'termine'
                   AND s2.date_prevue >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY eb.id 
                 ORDER BY COUNT(*) DESC 
                 LIMIT 1
                ) as receveur_suggere_id,
                (SELECT eb.nom 
                 FROM equipe_bord eb 
                 INNER JOIN shifts s2 ON s2.receveur_id = eb.id 
                 WHERE s2.bus_numero = s.bus_numero 
                   AND eb.poste = 'receveur' 
                   AND eb.statut = 'actif'
                   AND s2.statut = 'termine'
                   AND s2.date_prevue >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                 GROUP BY eb.id 
                 ORDER BY COUNT(*) DESC 
                 LIMIT 1
                ) as receveur_nom,
                COUNT(*) as frequence,
                'historique' as source
            FROM shifts s
            LEFT JOIN trajets t ON s.trajet_id = t.id
            LEFT JOIN bus b ON s.bus_numero = b.numero
            WHERE s.statut = 'termine'
              AND s.date_prevue >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
              AND DAYOFWEEK(s.date_prevue) = DAYOFWEEK(?)
            GROUP BY s.bus_numero, s.heure_debut, s.heure_fin, s.trajet_id
            HAVING chauffeur_suggere_id IS NOT NULL
            ORDER BY frequence DESC
            LIMIT ?
        ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$date, $limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL getSuggestionsFromHistory: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Suggestions aléatoires quand pas d'historique
     */
    private function getRandomSuggestions($date, $limit) {
        try {
            $sql = "
            SELECT 
                b.numero as bus_numero,
                b.immatriculation as bus_immatriculation,
                '06:00:00' as heure_debut,
                '14:00:00' as heure_fin,
                COALESCE(t.id, 0) as trajet_id,
                COALESCE(t.nom, 'Non affecté') as trajet_nom,
                NULL as chauffeur_suggere_id,
                NULL as chauffeur_nom,
                NULL as controleur_suggere_id,
                NULL as controleur_nom,
                NULL as receveur_suggere_id,
                NULL as receveur_nom,
                0 as frequence,
                'aleatoire' as source
            FROM bus b
            LEFT JOIN trajets t ON b.trajet_id = t.id
            WHERE b.statut = 'actif'
            ORDER BY RAND()
            LIMIT ?
        ";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur SQL getRandomSuggestions: " . $e->getMessage());
            return [];
        }
    }
}

?>
