<?php
/**
 * Model Clients
 * Gestion des clients et de leurs données
 */

class Clients {
    private $db;

    public function __construct() {
        // Utiliser la classe Database existante comme les autres Models
        $database = Database::getInstance();
        $this->db = $database->getConnection();
    }

    /**
     * Récupérer les statistiques des clients
     */
    public function getStatistiques() {
        $stats = [];

        // Total clients
        $sql = "SELECT COUNT(*) as total FROM clients";
        $stmt = $this->db->query($sql);
        $stats['total_clients'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Clients actifs (ayant fait au moins un voyage)
        $sql = "SELECT COUNT(DISTINCT client_id) as actifs 
                FROM reservations";
        $stmt = $this->db->query($sql);
        $stats['clients_actifs'] = $stmt->fetch(PDO::FETCH_ASSOC)['actifs'] ?? 0;

        // Nouveaux clients ce mois
        $sql = "SELECT COUNT(*) as nouveaux 
                FROM clients 
                WHERE MONTH(date_creation) = MONTH(CURRENT_DATE())
                AND YEAR(date_creation) = YEAR(CURRENT_DATE())";
        $stmt = $this->db->query($sql);
        $stats['nouveaux_mois'] = $stmt->fetch(PDO::FETCH_ASSOC)['nouveaux'];

        // Clients VIP (simulation - plus de 10 voyages)
        $sql = "SELECT COUNT(DISTINCT client_id) as vip 
                FROM reservations 
                GROUP BY client_id 
                HAVING COUNT(*) >= 10";
        $stmt = $this->db->query($sql);
        $stats['clients_vip'] = $stmt->rowCount();

        // Clients éligibles promo (simulation)
        $stats['promo_eligibles'] = round($stats['total_clients'] * 0.15);

        return $stats;
    }

    /**
     * Récupérer tous les clients avec leurs statistiques
     */
    public function getTousLesClients($filtres = []) {
        $sql = "SELECT 
                    c.id,
                    c.nom,
                    c.prenom,
                    c.telephone,
                    c.email,
                    c.date_creation,
                    COUNT(DISTINCT r.id) as nombre_voyages,
                    COALESCE(SUM(r.montant_total), 0) as depenses_totales,
                    MAX(r.date_creation) as dernier_voyage
                FROM clients c
                LEFT JOIN reservations r ON c.id = r.client_id
                GROUP BY c.id, c.nom, c.prenom, c.telephone, c.email, c.date_creation
                ORDER BY c.date_creation DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Enrichir les données de chaque client
        foreach ($clients as &$client) {
            // Calculer les points fidélité (100 points par voyage)
            $client['points_fidelite'] = $client['nombre_voyages'] * 100;

            // Déterminer le niveau
            if ($client['points_fidelite'] >= 3000) {
                $client['niveau'] = 'Or';
            } elseif ($client['points_fidelite'] >= 1500) {
                $client['niveau'] = 'Argent';
            } else {
                $client['niveau'] = 'Bronze';
            }

            // Déterminer le type de compte
            if ($client['nombre_voyages'] >= 10) {
                $client['type_compte'] = 'VIP';
            } elseif ($client['nombre_voyages'] >= 5) {
                $client['type_compte'] = 'Standard';
            } else {
                $client['type_compte'] = 'Nouveau';
            }

            // Éligibilité promo
            $client['promo_eligible'] = $client['points_fidelite'] >= 1000;
            
            // Réduction applicable
            if ($client['type_compte'] === 'VIP') {
                $client['reduction'] = 20;
            } elseif ($client['type_compte'] === 'Standard') {
                $client['reduction'] = 10;
            } else {
                $client['reduction'] = 0;
            }

            // Statut (tous actifs par défaut)
            $client['statut'] = 'actif';

            // Générer les initiales
            $client['initiales'] = strtoupper(
                substr($client['prenom'] ?? '', 0, 1) . 
                substr($client['nom'] ?? '', 0, 1)
            );

            // Nom complet
            $client['nom_complet'] = trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''));
        }

        return $clients;
    }

    /**
     * Récupérer un client par son ID
     */
    public function getClientById($id) {
        $sql = "SELECT 
                    c.*,
                    COUNT(DISTINCT r.id) as nombre_voyages,
                    COALESCE(SUM(r.montant_total), 0) as depenses_totales,
                    MAX(r.date_creation) as dernier_voyage
                FROM clients c
                LEFT JOIN reservations r ON c.id = r.client_id
                WHERE c.id = :id
                GROUP BY c.id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        $client = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($client) {
            // Enrichir les données
            $client['points_fidelite'] = $client['nombre_voyages'] * 100;
            
            if ($client['points_fidelite'] >= 3000) {
                $client['niveau'] = 'Or';
            } elseif ($client['points_fidelite'] >= 1500) {
                $client['niveau'] = 'Argent';
            } else {
                $client['niveau'] = 'Bronze';
            }

            if ($client['nombre_voyages'] >= 10) {
                $client['type_compte'] = 'VIP';
            } elseif ($client['nombre_voyages'] >= 5) {
                $client['type_compte'] = 'Standard';
            } else {
                $client['type_compte'] = 'Nouveau';
            }

            $client['promo_eligible'] = $client['points_fidelite'] >= 1000;
            $client['statut'] = 'actif';
            $client['initiales'] = strtoupper(
                substr($client['prenom'] ?? '', 0, 1) . 
                substr($client['nom'] ?? '', 0, 1)
            );
            $client['nom_complet'] = trim(($client['prenom'] ?? '') . ' ' . ($client['nom'] ?? ''));
        }

        return $client;
    }

    /**
     * Ajouter un nouveau client
     */
    public function ajouterClient($data) {
        try {
            $sql = "INSERT INTO clients (nom, prenom, telephone, email, date_creation) 
                    VALUES (:nom, :prenom, :telephone, :email, NOW())";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'telephone' => $data['telephone'],
                'email' => $data['email'] ?? null
            ]);

            return [
                'success' => $success,
                'message' => $success ? 'Client ajouté avec succès' : 'Erreur lors de l\'ajout',
                'id' => $this->db->lastInsertId()
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Mettre à jour un client
     */
    public function mettreAJourClient($id, $data) {
        try {
            $sql = "UPDATE clients SET 
                        nom = :nom,
                        prenom = :prenom,
                        telephone = :telephone,
                        email = :email
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute([
                'nom' => $data['nom'],
                'prenom' => $data['prenom'],
                'telephone' => $data['telephone'],
                'email' => $data['email'] ?? null,
                'id' => $id
            ]);

            return [
                'success' => $success,
                'message' => $success ? 'Client modifié avec succès' : 'Erreur lors de la modification'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ];
        }
    }

    /**
     * Supprimer un client
     */
    public function supprimerClient($id) {
        try {
            $sql = "DELETE FROM clients WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $success = $stmt->execute(['id' => $id]);

            return [
                'success' => $success,
                'message' => $success ? 'Client supprimé avec succès' : 'Erreur lors de la suppression'
            ];
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Erreur : ' . $e->getMessage()
            ];
        }
    }
}
