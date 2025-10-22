<?php

class Trajets {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // ===============================
    // TRAJETS
    // ===============================

    // Lister tous les trajets avec pagination et filtre statut
    public function getTrajets($limit = 50, $offset = 0, $statut = null) {
        $sql = "SELECT * FROM trajets WHERE 1";

        if ($statut !== null) {
            $sql .= " AND statut = :statut";
        }

        $sql .= " ORDER BY date_creation DESC LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        if ($statut !== null) {
            $stmt->bindValue(':statut', $statut, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Compter le nombre total de trajets (optionnellement par statut)
    public function compterTrajets($statut = null) {
        $sql = "SELECT COUNT(*) FROM trajets WHERE 1";
        if ($statut !== null) {
            $sql .= " AND statut = :statut";
        }

        $stmt = $this->db->prepare($sql);

        if ($statut !== null) {
            $stmt->bindValue(':statut', $statut, PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    // Récupérer un trajet par ID
    public function getTrajetById($id) {
        $stmt = $this->db->prepare("SELECT * FROM trajets WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    // Récupérer tous les trajets (pour les filtres)
    public function getTousLesTrajets() {
        $sql = "SELECT id, nom FROM trajets WHERE statut = 'actif' ORDER BY nom ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un nouveau trajet
    public function ajouterTrajet($data) {
        try {
            $this->db->beginTransaction();
            
            // Générer le code du trajet (L1, L2, L3, etc.)
            $code = $this->genererCodeTrajet();
            
            // Insérer le trajet
            $sql = "
                INSERT INTO trajets (code, nom, distance_totale, statut, couleur, latitude_depart, longitude_depart, latitude_arrivee, longitude_arrivee)
                VALUES (:code, :nom, :distance_totale, :statut, :couleur, :latitude_depart, :longitude_depart, :latitude_arrivee, :longitude_arrivee)
            ";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                'code' => $code,
                'nom' => $data['nom'],
                'distance_totale' => $data['distance_totale'] ?? 0,
                'statut' => $data['statut'] ?? 'actif',
                'couleur' => $data['couleur'] ?? null,
                'latitude_depart' => $data['lat_depart'] ?? null,
                'longitude_depart' => $data['lon_depart'] ?? null,
                'latitude_arrivee' => $data['lat_arrivee'] ?? null,
                'longitude_arrivee' => $data['lon_arrivee'] ?? null
            ]);
            
            if (!$success) {
                throw new Exception("Erreur lors de l'insertion du trajet");
            }
            
            $trajetId = $this->db->lastInsertId();
            
            // Insérer les arrêts
            if (!empty($data['arrets'])) {
                foreach ($data['arrets'] as $arret) {
                    $this->ajouterArret([
                        'trajet_id' => $trajetId,
                        'nom' => $arret['nom'],
                        'distance_avec_debut' => $arret['distance_avec_debut'] ?? 0,
                        'temps_arret' => $arret['temps_arret'] ?? 0
                    ]);
                }
            }
            
            // Insérer les points de chifte
            if (!empty($data['points_chifte'])) {
                foreach ($data['points_chifte'] as $chifte) {
                    $this->ajouterPointChifte([
                        'trajet_id' => $trajetId,
                        'nom' => $chifte['nom'],
                        'distance_avec_debut' => $chifte['distance_avec_debut'] ?? 0
                    ]);
                }
            }
            
            $this->db->commit();
            return $trajetId;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Mettre à jour un trajet
    public function mettreAJourTrajet($id, $data) {
        try {
            $this->db->beginTransaction();
            
            // Mettre à jour le trajet
            $sql = "
                UPDATE trajets SET
                    nom = :nom,
                    distance_totale = :distance_totale,
                    statut = :statut,
                    couleur = :couleur,
                    latitude_depart = :latitude_depart,
                    longitude_depart = :longitude_depart,
                    latitude_arrivee = :latitude_arrivee,
                    longitude_arrivee = :longitude_arrivee
                WHERE id = :id
            ";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                'nom' => $data['nom'],
                'distance_totale' => $data['distance_totale'] ?? 0,
                'statut' => $data['statut'] ?? 'actif',
                'couleur' => $data['couleur'] ?? null,
                'latitude_depart' => $data['lat_depart'] ?? null,
                'longitude_depart' => $data['lon_depart'] ?? null,
                'latitude_arrivee' => $data['lat_arrivee'] ?? null,
                'longitude_arrivee' => $data['lon_arrivee'] ?? null,
                'id' => $id
            ]);
            
            if (!$success) {
                throw new Exception("Erreur lors de la mise à jour du trajet");
            }
            
            // Gérer les arrêts
            if (isset($data['arrets'])) {
                foreach ($data['arrets'] as $arret) {
                    if (!empty($arret['id'])) {
                        // Mettre à jour l'arrêt existant
                        $this->mettreAJourArret($arret['id'], [
                            'nom' => $arret['nom'],
                            'distance_avec_debut' => $arret['distance_avec_debut'] ?? 0,
                            'temps_arret' => $arret['temps_arret'] ?? 0
                        ]);
                    } else {
                        // Ajouter un nouvel arrêt
                        $this->ajouterArret([
                            'trajet_id' => $id,
                            'nom' => $arret['nom'],
                            'distance_avec_debut' => $arret['distance_avec_debut'] ?? 0,
                            'temps_arret' => $arret['temps_arret'] ?? 0
                        ]);
                    }
                }
            }
            
            // Gérer les points de chifte
            if (isset($data['points_chifte'])) {
                foreach ($data['points_chifte'] as $chifte) {
                    if (!empty($chifte['id'])) {
                        // Mettre à jour le point existant
                        $this->mettreAJourPointChifte($chifte['id'], [
                            'nom' => $chifte['nom'],
                            'distance_avec_debut' => $chifte['distance_avec_debut'] ?? 0
                        ]);
                    } else {
                        // Ajouter un nouveau point
                        $this->ajouterPointChifte([
                            'trajet_id' => $id,
                            'nom' => $chifte['nom'],
                            'distance_avec_debut' => $chifte['distance_avec_debut'] ?? 0
                        ]);
                    }
                }
            }
            
            $this->db->commit();
            return true;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    // Supprimer un trajet
    public function supprimerTrajet($id) {
        $stmt = $this->db->prepare("DELETE FROM trajets WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // ===============================
    // ARRETS
    // ===============================

    // Lister les arrêts d'un trajet
    public function getArretsByTrajet($trajetId) {
        $stmt = $this->db->prepare("
            SELECT * FROM arrets 
            WHERE trajet_id = :trajet_id
            ORDER BY id ASC
        ");
        $stmt->bindValue(':trajet_id', $trajetId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un arrêt
    public function ajouterArret($data) {
        $sql = "
            INSERT INTO arrets (trajet_id, nom, latitude, longitude, distance_avec_debut, temps_arret)
            VALUES (:trajet_id, :nom, :latitude, :longitude, :distance_avec_debut, :temps_arret)
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'trajet_id' => $data['trajet_id'],
            'nom' => $data['nom'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'distance_avec_debut' => $data['distance_avec_debut'] ?? 0,
            'temps_arret' => $data['temps_arret'] ?? 0
        ]);
    }

    // Mettre à jour un arrêt
    public function mettreAJourArret($id, $data) {
        $sql = "
            UPDATE arrets SET
                nom = :nom,
                distance_avec_debut = :distance_avec_debut,
                temps_arret = :temps_arret
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nom' => $data['nom'],
            'distance_avec_debut' => $data['distance_avec_debut'] ?? 0,
            'temps_arret' => $data['temps_arret'] ?? 0,
            'id' => $id
        ]);
    }

    // Supprimer un arrêt
    public function supprimerArret($id) {
        $stmt = $this->db->prepare("DELETE FROM arrets WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // ===============================
    // POINTS DE CHIFTE
    // ===============================

    // Lister les points de chifte d'un trajet
    public function getPointsChifteByTrajet($trajetId) {
        $stmt = $this->db->prepare("
            SELECT * FROM points_chifte
            WHERE trajet_id = :trajet_id
            ORDER BY id ASC
        ");
        $stmt->bindValue(':trajet_id', $trajetId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter un point de chifte
    public function ajouterPointChifte($data) {
        $sql = "
            INSERT INTO points_chifte (trajet_id, nom, latitude, longitude, distance_avec_debut, temp_parcour)
            VALUES (:trajet_id, :nom, :latitude, :longitude, :distance_avec_debut, :temp_parcour)
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'trajet_id' => $data['trajet_id'],
            'nom' => $data['nom'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'distance_avec_debut' => $data['distance_avec_debut'] ?? 0,
            'temp_parcour' => $data['temp_parcour'] ?? 0
        ]);
    }

    // Mettre à jour un point de chifte
    public function mettreAJourPointChifte($id, $data) {
        $sql = "
            UPDATE points_chifte SET
                nom = :nom,
                latitude = :latitude,
                longitude = :longitude,
                distance_avec_debut = :distance_avec_debut,
                temp_parcour = :temp_parcour
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'nom' => $data['nom'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'distance_avec_debut' => $data['distance_avec_debut'] ?? 0,
            'temp_parcour' => $data['temp_parcour'] ?? 0,
            'id' => $id
        ]);
    }

    // Supprimer un point de chifte
    public function supprimerPointChifte($id) {
        $stmt = $this->db->prepare("DELETE FROM points_chifte WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // Supprimer tous les arrêts d'un trajet
    public function supprimerArretsByTrajet($trajetId) {
        $stmt = $this->db->prepare("DELETE FROM arrets WHERE trajet_id = :trajet_id");
        return $stmt->execute(['trajet_id' => $trajetId]);
    }

    // Supprimer tous les shifts d'un trajet
    public function supprimerShiftsByTrajet($trajetId) {
        $stmt = $this->db->prepare("DELETE FROM points_chifte WHERE trajet_id = :trajet_id");
        return $stmt->execute(['trajet_id' => $trajetId]);
    }

    // Ajouter un shift (point de chifte)
    public function ajouterShift($data) {
        $sql = "
            INSERT INTO points_chifte (trajet_id, nom, latitude, longitude, distance_avec_debut, temp_parcour)
            VALUES (:trajet_id, :nom, :latitude, :longitude, :distance_avec_debut, :temp_parcour)
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'trajet_id' => $data['trajet_id'],
            'nom' => $data['nom'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
            'distance_avec_debut' => $data['distance_avec_debut'] ?? 0,
            'temp_parcour' => $data['temp_parcour'] ?? 0
        ]);
    }

    // Générer un code unique pour le trajet (L1, L2, L3, etc.)
    private function genererCodeTrajet() {
        // Récupérer le dernier code utilisé
        $sql = "SELECT code FROM trajets WHERE code LIKE 'L%' ORDER BY CAST(SUBSTRING(code, 2) AS UNSIGNED) DESC LIMIT 1";
        $stmt = $this->db->query($sql);
        $dernierCode = $stmt->fetchColumn();
        
        if ($dernierCode) {
            // Extraire le numéro du dernier code (ex: "L11" -> 11)
            $dernierNumero = (int) substr($dernierCode, 1);
            $nouveauNumero = $dernierNumero + 1;
        } else {
            // Premier trajet
            $nouveauNumero = 1;
        }
        
        return 'L' . $nouveauNumero;
    }
}
