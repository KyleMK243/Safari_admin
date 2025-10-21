<?php

class BusinessIntelligence {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer les KPIs principaux
     */
    public function getKPIs($periode = 30) {
        $dateDebut = date('Y-m-d', strtotime("-$periode days"));
        $dateFin = date('Y-m-d');

        // Bus actifs
        $sql = "SELECT COUNT(*) as total FROM bus WHERE statut = 'actif'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $busActifs = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Trajets effectués
        $sql = "SELECT COUNT(*) as total FROM trajets_effectues 
                WHERE DATE(date_depart) BETWEEN :debut AND :fin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':debut' => $dateDebut, ':fin' => $dateFin]);
        $trajetsEffectues = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Passagers (estimation basée sur les billets vendus)
        $sql = "SELECT COUNT(*) as total FROM billets 
                WHERE DATE(date_achat) BETWEEN :debut AND :fin
                AND statut_billet IN ('paye', 'utilise')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':debut' => $dateDebut, ':fin' => $dateFin]);
        $passagers = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Revenus
        $sql = "SELECT SUM(prix_paye) as total FROM billets 
                WHERE DATE(date_achat) BETWEEN :debut AND :fin
                AND statut_billet IN ('paye', 'utilise')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':debut' => $dateDebut, ':fin' => $dateFin]);
        $revenus = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Calculer les tendances (comparaison avec la période précédente)
        $dateDebutPrecedent = date('Y-m-d', strtotime("-" . ($periode * 2) . " days"));
        $dateFinPrecedent = date('Y-m-d', strtotime("-$periode days"));

        // Trajets période précédente
        $sql = "SELECT COUNT(*) as total FROM trajets_effectues 
                WHERE DATE(date_depart) BETWEEN :debut AND :fin";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':debut' => $dateDebutPrecedent, ':fin' => $dateFinPrecedent]);
        $trajetsPrecedent = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Passagers période précédente
        $sql = "SELECT COUNT(*) as total FROM billets 
                WHERE DATE(date_achat) BETWEEN :debut AND :fin
                AND statut_billet IN ('paye', 'utilise')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':debut' => $dateDebutPrecedent, ':fin' => $dateFinPrecedent]);
        $passagersPrecedent = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Revenus période précédente
        $sql = "SELECT SUM(prix_paye) as total FROM billets 
                WHERE DATE(date_achat) BETWEEN :debut AND :fin
                AND statut_billet IN ('paye', 'utilise')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':debut' => $dateDebutPrecedent, ':fin' => $dateFinPrecedent]);
        $revenusPrecedent = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

        // Calculer les pourcentages
        $tendanceTrajets = $trajetsPrecedent > 0 ? (($trajetsEffectues - $trajetsPrecedent) / $trajetsPrecedent) * 100 : 0;
        $tendancePassagers = $passagersPrecedent > 0 ? (($passagers - $passagersPrecedent) / $passagersPrecedent) * 100 : 0;
        $tendanceRevenus = $revenusPrecedent > 0 ? (($revenus - $revenusPrecedent) / $revenusPrecedent) * 100 : 0;

        return [
            'bus_actifs' => $busActifs,
            'trajets_effectues' => $trajetsEffectues,
            'passagers' => $passagers,
            'revenus' => $revenus,
            'tendance_trajets' => round($tendanceTrajets, 1),
            'tendance_passagers' => round($tendancePassagers, 1),
            'tendance_revenus' => round($tendanceRevenus, 1)
        ];
    }

    /**
     * Récupérer les données pour le graphique des trajets par jour
     */
    public function getTrajetsParJour($periode = 30) {
        $sql = "SELECT 
                    DATE(date_depart) as date,
                    COUNT(*) as total
                FROM trajets_effectues
                WHERE DATE(date_depart) >= DATE_SUB(CURDATE(), INTERVAL :periode DAY)
                GROUP BY DATE(date_depart)
                ORDER BY date ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':periode' => $periode]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer la répartition par ligne
     */
    public function getRepartitionParLigne() {
        $sql = "SELECT 
                    t.nom as ligne,
                    t.code as ligne_numero,
                    COUNT(te.id) as total_trajets
                FROM trajets t
                LEFT JOIN trajets_effectues te ON t.id = te.trajet_id
                WHERE te.date_depart >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                GROUP BY t.id
                ORDER BY total_trajets DESC
                LIMIT 5";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer la performance des bus
     */
    public function getPerformanceBus() {
        $sql = "SELECT 
                    b.numero,
                    b.immatriculation,
                    COUNT(DISTINCT te.id) as total_trajets,
                    COUNT(DISTINCT bi.id) as total_passagers,
                    COALESCE(SUM(bi.prix_paye), 0) as revenus,
                    ROUND(AVG(CASE 
                        WHEN b.capacite > 0 THEN (COUNT(DISTINCT bi.id) / b.capacite) * 100 
                        ELSE 0 
                    END), 1) as taux_remplissage
                FROM bus b
                LEFT JOIN trajets_effectues te ON b.id = te.bus_id 
                    AND te.date_depart >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                LEFT JOIN billets bi ON b.id = bi.bus_id 
                    AND bi.date_achat >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                    AND bi.statut_billet IN ('paye', 'utilise')
                WHERE b.statut = 'actif'
                GROUP BY b.id
                ORDER BY total_trajets DESC
                LIMIT 10";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les revenus mensuels (6 derniers mois)
     */
    public function getRevenusMensuels() {
        $sql = "SELECT 
                    DATE_FORMAT(date_achat, '%Y-%m') as mois,
                    SUM(prix_paye) as revenus
                FROM billets
                WHERE date_achat >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
                AND statut_billet IN ('paye', 'utilise')
                GROUP BY DATE_FORMAT(date_achat, '%Y-%m')
                ORDER BY mois ASC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les top 5 bus par performance
     */
    public function getTop5Bus() {
        $sql = "SELECT 
                    b.numero,
                    b.immatriculation,
                    COUNT(DISTINCT te.id) as total_trajets
                FROM bus b
                LEFT JOIN trajets_effectues te ON b.id = te.bus_id 
                    AND te.date_depart >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                WHERE b.statut = 'actif'
                GROUP BY b.id
                ORDER BY total_trajets DESC
                LIMIT 5";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer les statistiques détaillées par bus avec pagination
     */
    public function getStatistiquesDetailleesParBus($limit = 10, $offset = 0) {
        $sql = "SELECT 
                    b.numero,
                    b.immatriculation,
                    b.capacite,
                    COUNT(DISTINCT te.id) as total_trajets,
                    COUNT(DISTINCT bi.id) as total_passagers,
                    COALESCE(SUM(bi.prix_paye), 0) as revenus,
                    CASE 
                        WHEN b.capacite > 0 AND COUNT(DISTINCT te.id) > 0 
                        THEN ROUND((COUNT(DISTINCT bi.id) / (b.capacite * COUNT(DISTINCT te.id))) * 100, 1)
                        ELSE 0 
                    END as taux_remplissage
                FROM bus b
                LEFT JOIN trajets_effectues te ON b.id = te.bus_id 
                    AND te.date_depart >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                LEFT JOIN billets bi ON b.id = bi.bus_id 
                    AND bi.date_achat >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                    AND bi.statut_billet IN ('paye', 'utilise')
                WHERE b.statut = 'actif'
                GROUP BY b.id
                ORDER BY revenus DESC
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer le nombre total de bus actifs
     */
    public function getTotalBusActifs() {
        $sql = "SELECT COUNT(*) as total FROM bus WHERE statut = 'actif'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
}
