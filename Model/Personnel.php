<?php

class Personnel {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer tous les agents avec filtres et pagination
     */
    public function getAgentsAvecFiltres($filtrePoste = null, $filtreStatut = null, $recherche = null, $limit = null, $offset = null) {
        $sql = "
            SELECT 
                id, nom, matricule, poste, telephone, email, 
                adresse, date_naissance, date_embauche, type_contrat, 
                salaire, statut, photo, bus_affecte, notes, date_creation
            FROM equipe_bord
            WHERE 1
        ";

        $params = [];

        if ($filtrePoste !== null && $filtrePoste !== '') {
            $sql .= " AND poste = :poste ";
            $params[':poste'] = $filtrePoste;
        }

        if ($filtreStatut !== null && $filtreStatut !== '') {
            $sql .= " AND statut = :statut ";
            $params[':statut'] = $filtreStatut;
        }

        if ($recherche !== null && $recherche !== '') {
            $sql .= " AND (nom LIKE :recherche OR matricule LIKE :recherche OR email LIKE :recherche) ";
            $params[':recherche'] = '%' . $recherche . '%';
        }

        $sql .= " ORDER BY date_creation DESC";

        // Pagination
        if ($limit !== null && $offset !== null) {
            $sql .= " LIMIT :limit OFFSET :offset";
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;
        }

        $stmt = $this->db->prepare($sql);
        
        // Bind des paramètres LIMIT et OFFSET en tant qu'entiers
        foreach ($params as $key => $value) {
            if ($key === ':limit' || $key === ':offset') {
                $stmt->bindValue($key, $value, PDO::PARAM_INT);
            } else {
                $stmt->bindValue($key, $value);
            }
        }
        
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Compter le total d'agents avec filtres
     */
    public function compterAgents($filtrePoste = null, $filtreStatut = null, $recherche = null) {
        $sql = "SELECT COUNT(*) as total FROM equipe_bord WHERE 1";
        $params = [];

        if ($filtrePoste !== null && $filtrePoste !== '') {
            $sql .= " AND poste = :poste ";
            $params[':poste'] = $filtrePoste;
        }

        if ($filtreStatut !== null && $filtreStatut !== '') {
            $sql .= " AND statut = :statut ";
            $params[':statut'] = $filtreStatut;
        }

        if ($recherche !== null && $recherche !== '') {
            $sql .= " AND (nom LIKE :recherche OR matricule LIKE :recherche OR email LIKE :recherche) ";
            $params[':recherche'] = '%' . $recherche . '%';
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) $result['total'];
    }

    /**
     * Récupérer un agent par ID
     */
    public function getAgentParId($id) {
        $sql = "SELECT * FROM equipe_bord WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Créer un nouvel agent
     */
    public function creerAgent($data) {
        $sql = "
            INSERT INTO equipe_bord (
                nom, matricule, poste, telephone, email, 
                adresse, date_naissance, date_embauche, type_contrat, 
                salaire, statut, photo, notes
            ) VALUES (
                :nom, :matricule, :poste, :telephone, :email,
                :adresse, :date_naissance, :date_embauche, :type_contrat,
                :salaire, :statut, :photo, :notes
            )
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nom' => $data['nom'],
            ':matricule' => $data['matricule'],
            ':poste' => $data['poste'],
            ':telephone' => $data['telephone'] ?? null,
            ':email' => $data['email'] ?? null,
            ':adresse' => $data['adresse'] ?? null,
            ':date_naissance' => $data['date_naissance'] ?? null,
            ':date_embauche' => $data['date_embauche'],
            ':type_contrat' => $data['type_contrat'],
            ':salaire' => $data['salaire'] ?? null,
            ':statut' => $data['statut'] ?? 'actif',
            ':photo' => $data['photo'] ?? null,
            ':notes' => $data['notes'] ?? null
        ]);
    }

    /**
     * Modifier un agent
     */
    public function modifierAgent($id, $data) {
        $sql = "
            UPDATE equipe_bord SET
                nom = :nom,
                poste = :poste,
                telephone = :telephone,
                email = :email,
                adresse = :adresse,
                date_naissance = :date_naissance,
                date_embauche = :date_embauche,
                type_contrat = :type_contrat,
                salaire = :salaire,
                statut = :statut,
                bus_affecte = :bus_affecte,
                notes = :notes
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':nom' => $data['nom'],
            ':poste' => $data['poste'],
            ':telephone' => $data['telephone'] ?? null,
            ':email' => $data['email'] ?? null,
            ':adresse' => $data['adresse'] ?? null,
            ':date_naissance' => $data['date_naissance'] ?? null,
            ':date_embauche' => $data['date_embauche'],
            ':type_contrat' => $data['type_contrat'],
            ':salaire' => $data['salaire'] ?? null,
            ':statut' => $data['statut'],
            ':bus_affecte' => $data['bus_affecte'] ?? null,
            ':notes' => $data['notes'] ?? null
        ]);
    }

    /**
     * Supprimer un agent
     */
    public function supprimerAgent($id) {
        // Vérifier si l'agent est affecté à un bus
        $sql = "SELECT bus_affecte FROM equipe_bord WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        $agent = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($agent && $agent['bus_affecte'] !== null) {
            throw new Exception("Impossible de supprimer cet agent car il est affecté au bus #" . $agent['bus_affecte']);
        }

        // Supprimer l'agent
        $sql = "DELETE FROM equipe_bord WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    /**
     * Générer un matricule unique
     */
    public function genererMatricule() {
        $annee = date('Y');
        
        // Compter le nombre d'agents créés cette année
        $sql = "SELECT COUNT(*) as total FROM equipe_bord WHERE YEAR(date_creation) = :annee";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':annee' => $annee]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $numero = str_pad($result['total'] + 1, 3, '0', STR_PAD_LEFT);
        return "EMP-{$annee}-{$numero}";
    }

    /**
     * Vérifier si un matricule existe déjà
     */
    public function matriculeExiste($matricule, $excludeId = null) {
        $sql = "SELECT COUNT(*) as total FROM equipe_bord WHERE matricule = :matricule";
        
        if ($excludeId !== null) {
            $sql .= " AND id != :id";
        }
        
        $stmt = $this->db->prepare($sql);
        $params = [':matricule' => $matricule];
        
        if ($excludeId !== null) {
            $params[':id'] = $excludeId;
        }
        
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }

    /**
     * Récupérer les statistiques du personnel
     */
    public function getStatistiques() {
        $stats = [];

        // Total agents
        $sql = "SELECT COUNT(*) as total FROM equipe_bord";
        $stmt = $this->db->query($sql);
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Par statut
        $sql = "SELECT statut, COUNT(*) as total FROM equipe_bord GROUP BY statut";
        $stmt = $this->db->query($sql);
        $statuts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($statuts as $statut) {
            $stats['statut_' . $statut['statut']] = $statut['total'];
        }

        // Par poste
        $sql = "SELECT poste, COUNT(*) as total FROM equipe_bord GROUP BY poste";
        $stmt = $this->db->query($sql);
        $postes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($postes as $poste) {
            $stats['poste_' . $poste['poste']] = $poste['total'];
        }

        // Par type de contrat
        $sql = "SELECT type_contrat, COUNT(*) as total FROM equipe_bord GROUP BY type_contrat";
        $stmt = $this->db->query($sql);
        $contrats = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($contrats as $contrat) {
            $stats['contrat_' . $contrat['type_contrat']] = $contrat['total'];
        }

        return $stats;
    }
}

?>
