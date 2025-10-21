<?php

class EquipeBord {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    // 1. Lister les membres de l'équipe avec pagination et filtres
    public function getEquipeAvecPagination($limit, $offset, $poste = null, $statut = null, $bus_affecte = null) {
        $sql = "
            SELECT 
                e.id, e.nom, e.poste, e.telephone, e.email, e.statut,
                e.date_embauche, e.date_creation, e.bus_affecte,
                b.immatriculation AS bus_immatriculation,
                b.numero AS bus_numero
            FROM equipe_bord e
            LEFT JOIN bus b ON e.bus_affecte = b.numero
            WHERE 1
        ";

        if ($poste !== null) {
            $sql .= " AND e.poste = :poste ";
        }
        if ($statut !== null) {
            $sql .= " AND e.statut = :statut ";
        }
        if ($bus_affecte !== null) {
            $sql .= " AND e.bus_affecte = :bus_affecte ";
        }

        $sql .= " ORDER BY e.date_creation DESC
                  LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);

        if ($poste !== null) {
            $stmt->bindValue(':poste', $poste, PDO::PARAM_STR);
        }
        if ($statut !== null) {
            $stmt->bindValue(':statut', $statut, PDO::PARAM_STR);
        }
        if ($bus_affecte !== null) {
            $stmt->bindValue(':bus_affecte', $bus_affecte, PDO::PARAM_STR);
        }

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    // Lister les membres avec filtres (poste, statut, recherche) - comme getBusAvecFiltres
    public function getEquipeAvecFiltres($filtrePoste = null, $filtreStatut = null, $recherche = null) {
        try {
            // Normaliser les valeurs vides en null
            $filtrePoste = ($filtrePoste === '' || $filtrePoste === null) ? null : $filtrePoste;
            $filtreStatut = ($filtreStatut === '' || $filtreStatut === null) ? null : $filtreStatut;
            $recherche = ($recherche === '' || $recherche === null) ? null : $recherche;
            
            $sql = "SELECT 
                    e.id, e.nom, e.poste, e.telephone, e.email, e.statut,
                    e.date_embauche, e.date_creation, e.bus_affecte,
                    b.immatriculation AS bus_immatriculation,
                    b.numero AS bus_numero
                FROM equipe_bord e
                LEFT JOIN bus b ON e.bus_affecte = b.numero
                WHERE 1=1";

            $params = [];

            if ($filtrePoste !== null) {
                $sql .= " AND e.poste = ?";
                $params[] = $filtrePoste;
            }
            
            if ($filtreStatut !== null) {
                $sql .= " AND e.statut = ?";
                $params[] = $filtreStatut;
            }
            
            if ($recherche !== null) {
                $sql .= " AND (e.nom LIKE ? OR e.telephone LIKE ? OR e.email LIKE ?)";
                $params[] = '%' . $recherche . '%';
                $params[] = '%' . $recherche . '%';
                $params[] = '%' . $recherche . '%';
            }

            $sql .= " ORDER BY e.date_creation DESC";

            error_log("=== DEBUG SQL EQUIPE ===");
            error_log("SQL: " . $sql);
            error_log("Params count: " . count($params));
            error_log("Params: " . print_r($params, true));

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("Résultats: " . count($result) . " membres trouvés");
            
            return $result;
        } catch (Exception $e) {
            error_log("ERREUR SQL getEquipeAvecFiltres: " . $e->getMessage());
            error_log("SQL était: " . ($sql ?? 'non défini'));
            error_log("Params étaient: " . print_r($params ?? [], true));
            throw $e;
        }
    }

    // 2. Compter le nombre total de membres de l’équipe (avec filtres)
    public function compterTous($poste = null, $statut = null) {
        $sql = "SELECT COUNT(*) FROM equipe_bord WHERE 1";

        if ($poste !== null) {
            $sql .= " AND poste = :poste ";
        }
        if ($statut !== null) {
            $sql .= " AND statut = :statut ";
        }

        $stmt = $this->db->prepare($sql);
        if ($poste !== null) {
            $stmt->bindValue(':poste', $poste, PDO::PARAM_STR);
        }
        if ($statut !== null) {
            $stmt->bindValue(':statut', $statut, PDO::PARAM_STR);
        }

        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    // 3. Rechercher un membre par mot-clé (nom, téléphone, email)
    public function chercherEquipe($motCle) {
        $sql = "
            SELECT 
                id, nom, poste, telephone, email, statut, bus_affecte
            FROM equipe_bord
            WHERE 
                nom LIKE :motCle
                OR telephone LIKE :motCle
                OR email LIKE :motCle
                OR poste LIKE :motCle
            ORDER BY nom ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':motCle', '%' . $motCle . '%', PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 4. Récupérer les infos détaillées d’un membre par ID
    public function getMembreParId($id) {
        $sql = "
            SELECT e.*, b.numero AS bus_numero, b.immatriculation AS bus_immatriculation
            FROM equipe_bord e
            LEFT JOIN bus b ON e.bus_affecte = b.numero
            WHERE e.id = :id
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // 5. Ajouter un nouveau membre
    public function ajouterMembre($donnees) {
        $sql = "
            INSERT INTO equipe_bord (
                nom, poste, telephone, email, statut, bus_affecte, date_embauche
            ) VALUES (
                :nom, :poste, :telephone, :email, :statut, :bus_affecte, :date_embauche
            )
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($donnees);
    }

    // 6. Mettre à jour les infos d’un membre
    public function mettreAJourMembre($id, $donnees) {
        $sql = "
            UPDATE equipe_bord SET
                nom = :nom,
                poste = :poste,
                telephone = :telephone,
                email = :email,
                statut = :statut,
                bus_affecte = :bus_affecte,
                date_embauche = :date_embauche
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);
        $donnees['id'] = $id;
        return $stmt->execute($donnees);
    }

    // 7. Supprimer un membre
    public function supprimerMembre($id) {
        $stmt = $this->db->prepare("DELETE FROM equipe_bord WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    // 8. Changer le statut d’un membre (actif, suspendu, en congé, etc.)
    public function changerStatut($id, $statut) {
        $sql = "UPDATE equipe_bord SET statut = :statut WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['statut' => $statut, 'id' => $id]);
    }

    // 9. Affecter un membre à un bus
    public function affecterBus($idMembre, $numeroBus) {
        $sql = "UPDATE equipe_bord SET bus_affecte = :bus WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['bus' => $numeroBus, 'id' => $idMembre]);
    }

    // 10. Retirer un membre de son bus
    public function retirerDuBus($idMembre) {
        $sql = "UPDATE equipe_bord SET bus_affecte = NULL WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $idMembre]);
    }

    // 11. Récupérer les membres d’un bus donné
    public function getMembresParBus($numeroBus) {
        $sql = "
            SELECT id, nom, poste, telephone, email, statut
            FROM equipe_bord
            WHERE bus_affecte = :bus
            ORDER BY 
                CASE poste
                    WHEN 'chauffeur' THEN 1
                    WHEN 'controleur' THEN 2
                    WHEN 'receveur' THEN 3
                    ELSE 4
                END
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':bus', $numeroBus, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 12. Lister les postes disponibles
    public function getPostesDisponibles() {
        $sql = "SELECT DISTINCT poste FROM equipe_bord ORDER BY poste ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // 13. Lister les chauffeurs disponibles (non affectés à un bus actif)
    public function getChauffeursDisponibles() {
        $sql = "
            SELECT e.id, e.nom, e.telephone
            FROM equipe_bord e
            WHERE e.poste = 'chauffeur'
            AND (e.bus_affecte IS NULL OR e.bus_affecte = '')
            AND e.statut = 'actif'
            ORDER BY e.nom ASC
        ";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // 14. Vérifier si un bus a déjà un chauffeur actif
    public function verifierChauffeurActif($numeroBus) {
        $sql = "
            SELECT COUNT(*) 
            FROM equipe_bord
            WHERE bus_affecte = :bus
            AND poste = 'chauffeur'
            AND statut = 'actif'
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':bus', $numeroBus, PDO::PARAM_STR);
        $stmt->execute();
        return (int) $stmt->fetchColumn() > 0;
    }

    // 15. Historique des affectations (si tu veux le gérer plus tard)
    public function enregistrerHistoriqueAffectation($idMembre, $numeroBus, $action) {
        $sql = "
            INSERT INTO historique_affectations (id_membre, numero_bus, action, date_action)
            VALUES (:id_membre, :numero_bus, :action, NOW())
        ";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id_membre' => $idMembre,
            'numero_bus' => $numeroBus,
            'action' => $action
        ]);
    }
}

?>
