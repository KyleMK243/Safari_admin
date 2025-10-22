<?php

class Bus {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // 1. Lister les bus avec pagination et filtre (statut ou ligne)
    public function getBusAvecPagination($limit, $offset, $filtreStatut = null, $ligne = null) {
        $sql = "
            SELECT 
                b.id, b.numero, b.immatriculation, b.marque, b.modele, b.annee,
                b.capacite, b.kilometrage, b.trajet_id, b.statut,
                b.derniere_activite, b.date_creation, b.modules, b.notes,
                COALESCE(
                    (SELECT e.nom 
                     FROM equipe_bord e 
                     WHERE e.bus_affecte = b.numero 
                     AND e.poste = 'chauffeur'
                     AND e.statut = 'actif'
                     LIMIT 1), 
                    '-'
                ) as chauffeur
            FROM bus b
            WHERE 1
        ";

        if ($filtreStatut !== null) {
            $sql .= " AND statut = :statut ";
        }
        if ($ligne !== null) {
            $sql .= " AND trajet_id = :ligne ";
        }

        $sql .= " ORDER BY date_creation DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        if ($filtreStatut !== null) {
            $stmt->bindValue(':statut', $filtreStatut, PDO::PARAM_STR);
        }
        if ($ligne !== null) {
            $stmt->bindValue(':ligne', $ligne, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lister les bus avec filtres (statut, trajet, recherche)
    public function getBusAvecFiltres($filtreStatut = null, $filtreTrajet = null, $recherche = null) {
        try {
            // Normaliser les valeurs vides en null
            $filtreStatut = ($filtreStatut === '' || $filtreStatut === null) ? null : $filtreStatut;
            $filtreTrajet = ($filtreTrajet === '' || $filtreTrajet === null) ? null : $filtreTrajet;
            $recherche = ($recherche === '' || $recherche === null) ? null : $recherche;
            
            $sql = "SELECT 
                    b.id, b.numero, b.immatriculation, b.marque, b.modele, b.annee,
                    b.capacite, b.kilometrage, b.trajet_id, b.statut,
                    b.derniere_activite, b.date_creation, b.modules, b.notes,
                    t.nom as trajet_nom,
                    '-' as chauffeur
                FROM bus b
                LEFT JOIN trajets t ON b.trajet_id = t.id
                WHERE 1=1";

            $params = [];

            if ($filtreStatut !== null) {
                $sql .= " AND b.statut = ?";
                $params[] = $filtreStatut;
            }
            
            if ($filtreTrajet !== null) {
                if ($filtreTrajet === 'non_affecte') {
                    $sql .= " AND (b.trajet_id IS NULL OR b.trajet_id = '')";
                } else {
                    $sql .= " AND b.trajet_id = ?";
                    $params[] = $filtreTrajet;
                }
            }
            
            if ($recherche !== null) {
                $sql .= " AND (b.numero LIKE ? OR b.immatriculation LIKE ? OR b.marque LIKE ? OR b.modele LIKE ?)";
                $params[] = '%' . $recherche . '%';
                $params[] = '%' . $recherche . '%';
                $params[] = '%' . $recherche . '%';
                $params[] = '%' . $recherche . '%';
            }

            $sql .= " ORDER BY b.date_creation DESC";

            error_log("=== DEBUG SQL ===");
            error_log("SQL: " . $sql);
            error_log("Params count: " . count($params));
            error_log("Params: " . print_r($params, true));

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Résultats: " . count($result) . " bus trouvés");
            
            return $result;
        } catch (Exception $e) {
            error_log("ERREUR SQL getBusAvecFiltres: " . $e->getMessage());
            error_log("SQL était: " . ($sql ?? 'non défini'));
            error_log("Params étaient: " . print_r($params ?? [], true));
            throw $e;
        }
    }

    // 2. Compter le nombre total de bus (avec filtre optionnel)
    public function compterTousLesBus($filtreStatut = null, $ligne = null) {
        $sql = "SELECT COUNT(*) FROM bus WHERE 1";

        if ($filtreStatut !== null) {
            $sql .= " AND statut = :statut ";
        }
        if ($ligne !== null) {
            $sql .= " AND trajet_id = :ligne ";
        }

        $stmt = $this->db->prepare($sql);
        if ($filtreStatut !== null) {
            $stmt->bindValue(':statut', $filtreStatut, PDO::PARAM_STR);
        }
        if ($ligne !== null) {
            $stmt->bindValue(':ligne', $ligne, PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // 3. Rechercher des bus par mot-clé (numéro, immatriculation, marque)
    public function chercherBus($motCle) {
        $sql = "
            SELECT 
                id, numero, immatriculation, marque, modele, trajet_id,
                capacite, statut, kilometrage
            FROM bus
            WHERE 
                numero LIKE :motCle 
                OR immatriculation LIKE :motCle
                OR marque LIKE :motCle
                OR modele LIKE :motCle
            ORDER BY numero ASC
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Récupérer les infos détaillées d'un bus par ID
    public function getBusParId($id) {
        $sql = "
            SELECT 
                b.*,
                t.nom AS trajet_nom
            FROM bus b
            LEFT JOIN trajets t ON b.trajet_id = t.id
            WHERE b.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Ajouter un nouveau bus
    public function ajouterBus($donnees) {
        $sql = "
            INSERT INTO bus (
                numero, immatriculation, marque, modele, annee, capacite, kilometrage,
                trajet_id, statut, modules, notes, derniere_activite
            ) VALUES (
                :numero, :immatriculation, :marque, :modele, :annee, :capacite, :kilometrage,
                :trajet_id, :statut, :modules, :notes, :derniere_activite
            )
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($donnees);
    }

    // 6. Mettre à jour les infos d’un bus
    public function mettreAJourBus($id, $donnees) {
        $sql = "
            UPDATE bus SET
                numero = :numero,
                immatriculation = :immatriculation,
                marque = :marque,
                modele = :modele,
                annee = :annee,
                capacite = :capacite,
                kilometrage = :kilometrage,
                trajet_id = :trajet_id,
                statut = :statut,
                modules = :modules,
                notes = :notes,
                derniere_activite = :derniere_activite
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);
        $donnees['id'] = $id;
        return $stmt->execute($donnees);
    }

    // 7. Supprimer un bus
    public function supprimerBus($id) {
        $stmt = $this->db->prepare("DELETE FROM bus WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // 8. Changer le statut d’un bus (actif, panne, maintenance, etc.)
    public function changerStatutBus($id, $statut) {
        $sql = "UPDATE bus SET statut = :statut WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['statut' => $statut, 'id' => $id]);
    }

    // 9. Récupérer les documents d’un bus
    public function getDocumentsBus($bus_id) {
        $sql = "
            SELECT id, designation, statut, date_emission, date_expiration, fichier_path
            FROM documents_bus
            WHERE bus_id = :bus_id
            ORDER BY date_creation DESC
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':bus_id', $bus_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 10. Ajouter un document à un bus
    public function ajouterDocumentBus($donnees) {
        $sql = "
            INSERT INTO documents_bus (bus_id, designation, statut, date_emission, date_expiration, fichier_path)
            VALUES (:bus_id, :designation, :statut, :date_emission, :date_expiration, :fichier_path)
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($donnees);
    }

    // 11. Mettre à jour un document
    public function mettreAJourDocument($id, $donnees) {
        $sql = "
            UPDATE documents_bus SET
                designation = :designation,
                statut = :statut,
                date_emission = :date_emission,
                date_expiration = :date_expiration,
                fichier_path = :fichier_path
            WHERE id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $donnees['id'] = $id;
        return $stmt->execute($donnees);
    }

    // 12. Supprimer un document
    public function supprimerDocument($id) {
        $stmt = $this->db->prepare("DELETE FROM documents_bus WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // 13. Mettre à jour le kilométrage du bus
    public function mettreAJourKilometrage($id, $kilometrage) {
        $sql = "UPDATE bus SET kilometrage = :km, derniere_activite = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['km' => $kilometrage, 'id' => $id]);
    }

    // 14. Récupérer la liste des lignes disponibles (pour filtre)
    public function getLignesDisponibles() {
        $sql = "SELECT DISTINCT trajet_id FROM bus WHERE trajet_id IS NOT NULL AND trajet_id <> '' ORDER BY trajet_id ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
    
    // 15. Récupérer l'équipe de bord affectée à un bus
    public function getEquipeBordBus($busNumero) {
        $sql = "
            SELECT id, nom, poste, telephone, email, statut
            FROM equipe_bord
            WHERE bus_affecte = :bus_numero
            AND statut = 'actif'
            ORDER BY 
                CASE poste
                    WHEN 'chauffeur' THEN 1
                    WHEN 'controleur' THEN 2
                    WHEN 'receveur' THEN 3
                END
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':bus_numero', $busNumero, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>
