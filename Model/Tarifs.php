<?php

class Tarifs {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer tous les tarifs groupés par trajet
     */
    public function getTarifsParTrajet() {
        $sql = "
            SELECT 
                t.id as trajet_id,
                t.nom as trajet_nom,
                t.distance_totale,
                t.duree_estimee,
                t.statut as trajet_statut,
                tar.id as tarif_id,
                tar.type_tarif,
                tar.prix,
                tar.devise,
                tar.statut as tarif_statut
            FROM trajets t
            LEFT JOIN tarifs tar ON t.id = tar.trajet_id
            WHERE t.statut = 'actif'
            ORDER BY t.nom, 
                FIELD(tar.type_tarif, 'normal', 'etudiant', 'senior', 'enfant', 'entreprise', 'touriste')
        ";
        
        $stmt = $this->db->query($sql);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Grouper les tarifs par trajet
        $trajets = [];
        foreach ($results as $row) {
            $trajetId = $row['trajet_id'];
            
            if (!isset($trajets[$trajetId])) {
                $trajets[$trajetId] = [
                    'id' => $row['trajet_id'],
                    'nom' => $row['trajet_nom'],
                    'distance_totale' => $row['distance_totale'],
                    'duree_estimee' => $row['duree_estimee'],
                    'statut' => $row['trajet_statut'],
                    'tarifs' => []
                ];
            }
            
            if ($row['tarif_id']) {
                $trajets[$trajetId]['tarifs'][$row['type_tarif']] = [
                    'id' => $row['tarif_id'],
                    'prix' => $row['prix'],
                    'devise' => $row['devise'],
                    'statut' => $row['tarif_statut']
                ];
            }
        }
        
        return array_values($trajets);
    }

    /**
     * Récupérer les statistiques des tarifs
     */
    public function getStatistiques() {
        $sql = "
            SELECT 
                COUNT(DISTINCT t.id) as total_trajets,
                AVG(tar.prix) as prix_moyen,
                MIN(tar.prix) as prix_min,
                MAX(tar.prix) as prix_max
            FROM trajets t
            LEFT JOIN tarifs tar ON t.id = tar.trajet_id AND tar.type_tarif = 'normal'
            WHERE t.statut = 'actif'
        ";
        
        $stmt = $this->db->query($sql);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer un tarif par ID
     */
    public function getTarifById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tarifs WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupérer tous les tarifs d'un trajet
     */
    public function getTarifsByTrajet($trajetId) {
        $stmt = $this->db->prepare("
            SELECT * FROM tarifs 
            WHERE trajet_id = :trajet_id 
            ORDER BY FIELD(type_tarif, 'normal', 'etudiant', 'senior', 'enfant', 'entreprise', 'touriste')
        ");
        $stmt->execute(['trajet_id' => $trajetId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Ajouter un nouveau tarif
     */
    public function ajouterTarif($data) {
        $sql = "
            INSERT INTO tarifs (nom, trajet_id, type_tarif, prix, devise, statut, date_debut, date_fin)
            VALUES (:nom, :trajet_id, :type_tarif, :prix, :devise, :statut, :date_debut, :date_fin)
        ";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nom' => $data['nom'],
            'trajet_id' => $data['trajet_id'],
            'type_tarif' => $data['type_tarif'],
            'prix' => $data['prix'],
            'devise' => $data['devise'] ?? 'CDF',
            'statut' => $data['statut'] ?? 'actif',
            'date_debut' => $data['date_debut'] ?? null,
            'date_fin' => $data['date_fin'] ?? null
        ]);
    }

    /**
     * Mettre à jour un tarif
     */
    public function mettreAJourTarif($id, $data) {
        $sql = "
            UPDATE tarifs SET
                nom = :nom,
                trajet_id = :trajet_id,
                type_tarif = :type_tarif,
                prix = :prix,
                devise = :devise,
                statut = :statut,
                date_debut = :date_debut,
                date_fin = :date_fin
            WHERE id = :id
        ";
        
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nom' => $data['nom'],
            'trajet_id' => $data['trajet_id'],
            'type_tarif' => $data['type_tarif'],
            'prix' => $data['prix'],
            'devise' => $data['devise'] ?? 'CDF',
            'statut' => $data['statut'] ?? 'actif',
            'date_debut' => $data['date_debut'] ?? null,
            'date_fin' => $data['date_fin'] ?? null,
            'id' => $id
        ]);
    }

    /**
     * Supprimer un tarif
     */
    public function supprimerTarif($id) {
        $stmt = $this->db->prepare("DELETE FROM tarifs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Créer automatiquement les tarifs pour un trajet
     */
    public function creerTarifsAutomatiques($trajetId, $prixNormal) {
        $trajets = new Trajets();
        $trajet = $trajets->getTrajetById($trajetId);
        
        if (!$trajet) {
            throw new Exception("Trajet introuvable");
        }
        
        $tarifs = [
            ['type' => 'normal', 'reduction' => 0, 'nom' => "Tarif Normal - {$trajet['nom']}"],
            ['type' => 'etudiant', 'reduction' => 0.15, 'nom' => "Tarif Étudiant - {$trajet['nom']}"],
            ['type' => 'senior', 'reduction' => 0.10, 'nom' => "Tarif Senior - {$trajet['nom']}"],
            ['type' => 'enfant', 'reduction' => 0.20, 'nom' => "Tarif Enfant - {$trajet['nom']}"]
        ];
        
        foreach ($tarifs as $tarif) {
            $prix = $prixNormal * (1 - $tarif['reduction']);
            
            $this->ajouterTarif([
                'nom' => $tarif['nom'],
                'trajet_id' => $trajetId,
                'type_tarif' => $tarif['type'],
                'prix' => round($prix, 2),
                'devise' => 'CDF',
                'statut' => 'actif'
            ]);
        }
        
        return true;
    }
}
