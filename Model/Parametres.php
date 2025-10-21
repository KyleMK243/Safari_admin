<?php

class Parametres {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer tous les utilisateurs
     */
    public function getUtilisateurs() {
        try {
            $sql = "SELECT 
                        id, nom, email, role, statut, 
                        departement, avatar,
                        derniere_connexion, date_creation
                    FROM utilisateurs
                    WHERE departement = 'PL'
                    ORDER BY date_creation DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $utilisateurs = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Adapter les données pour la vue
            foreach ($utilisateurs as &$user) {
                // Utiliser avatar si disponible, sinon générer les initiales
                if (empty($user['avatar'])) {
                    $user['initiales'] = $this->genererInitiales($user['nom']);
                } else {
                    $user['initiales'] = $user['avatar'];
                }
                
                // Mapper departement vers module pour compatibilité avec la vue
                $user['module'] = $user['departement'] ?? '-';
            }
            
            return $utilisateurs;
        } catch (Exception $e) {
            error_log("Erreur getUtilisateurs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer un utilisateur par ID
     */
    public function getUtilisateurById($id) {
        try {
            $sql = "SELECT * FROM utilisateurs WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getUtilisateurById: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Créer un nouvel utilisateur (toujours dans le département PL)
     */
    public function creerUtilisateur($nom, $email, $password, $role, $statut = 'actif') {
        try {
            // Générer les initiales pour l'avatar
            $avatar = $this->genererInitiales($nom);
            
            // Forcer le département PL pour cette page
            $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role, departement, statut, avatar, date_creation) 
                    VALUES (?, ?, ?, ?, 'PL', ?, ?, NOW())";
            
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$nom, $email, $hashedPassword, $role, $statut, $avatar]);
            
            return [
                'success' => true,
                'message' => 'Utilisateur créé avec succès'
            ];
        } catch (Exception $e) {
            error_log("Erreur creerUtilisateur: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de la création: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Modifier un utilisateur (département PL uniquement)
     */
    public function modifierUtilisateur($id, $nom, $email, $role, $statut, $password = null) {
        try {
            // Générer les initiales pour l'avatar
            $avatar = $this->genererInitiales($nom);
            
            if ($password) {
                $sql = "UPDATE utilisateurs 
                        SET nom = ?, email = ?, mot_de_passe = ?, role = ?, statut = ?, avatar = ?
                        WHERE id = ? AND departement = 'PL'";
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$nom, $email, $hashedPassword, $role, $statut, $avatar, $id]);
            } else {
                $sql = "UPDATE utilisateurs 
                        SET nom = ?, email = ?, role = ?, statut = ?, avatar = ?
                        WHERE id = ? AND departement = 'PL'";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$nom, $email, $role, $statut, $avatar, $id]);
            }
            
            return [
                'success' => true,
                'message' => 'Utilisateur modifié avec succès'
            ];
        } catch (Exception $e) {
            error_log("Erreur modifierUtilisateur: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de la modification: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Changer le statut d'un utilisateur
     */
    public function changerStatutUtilisateur($id, $statut) {
        try {
            $sql = "UPDATE utilisateurs SET statut = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$statut, $id]);
            
            return [
                'success' => true,
                'message' => 'Statut modifié avec succès'
            ];
        } catch (Exception $e) {
            error_log("Erreur changerStatutUtilisateur: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors du changement de statut: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function supprimerUtilisateur($id) {
        try {
            $sql = "DELETE FROM utilisateurs WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            
            return [
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès'
            ];
        } catch (Exception $e) {
            error_log("Erreur supprimerUtilisateur: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erreur lors de la suppression: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Générer les initiales d'un nom
     */
    private function genererInitiales($nom) {
        $parts = explode(' ', trim($nom));
        if (count($parts) >= 2) {
            return strtoupper(substr($parts[0], 0, 1) . substr($parts[1], 0, 1));
        }
        return strtoupper(substr($nom, 0, 2));
    }

    /**
     * Récupérer les statistiques des utilisateurs
     */
    public function getStatistiquesUtilisateurs() {
        try {
            $sql = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN statut = 'actif' THEN 1 ELSE 0 END) as actifs,
                        SUM(CASE WHEN statut = 'inactif' THEN 1 ELSE 0 END) as inactifs
                    FROM utilisateurs";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erreur getStatistiquesUtilisateurs: " . $e->getMessage());
            return ['total' => 0, 'actifs' => 0, 'inactifs' => 0];
        }
    }

    /**
     * Récupérer les paramètres système
     */
    public function getParametresSysteme() {
        try {
            $sql = "SELECT * FROM parametres_systeme ORDER BY cle ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Convertir en tableau associatif clé => valeur
            $parametres = [];
            foreach ($results as $row) {
                $parametres[$row['cle']] = $row['valeur'];
            }
            
            return $parametres;
        } catch (Exception $e) {
            error_log("Erreur getParametresSysteme: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mettre à jour un paramètre système
     */
    public function updateParametreSysteme($cle, $valeur) {
        try {
            $sql = "UPDATE parametres_systeme SET valeur = ? WHERE cle = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$valeur, $cle]);
        } catch (Exception $e) {
            error_log("Erreur updateParametreSysteme: " . $e->getMessage());
            return false;
        }
    }
}
