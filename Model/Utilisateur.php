<?php
/**
 * Model Utilisateur
 * Gère toutes les opérations liées aux utilisateurs
 */

class Utilisateur {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Authentifier un utilisateur
     * 
     * @param string $email Email de l'utilisateur
     * @param string $motDePasse Mot de passe en clair
     * @return array|false Données utilisateur ou false si échec
     */
    public function connecter($email, $motDePasse) {
        try {
            $sql = "SELECT * FROM utilisateurs 
                    WHERE email = :email 
                    AND statut = 'actif' 
                    LIMIT 1";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['email' => $email]);
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'utilisateur existe et le mot de passe est correct
            if ($utilisateur && password_verify($motDePasse, $utilisateur['mot_de_passe'])) {
                // Mettre à jour la dernière connexion
                $this->mettreAJourDerniereConnexion($utilisateur['id']);
                
                // Ne pas retourner le mot de passe
                unset($utilisateur['mot_de_passe']);
                
                return $utilisateur;
            }

            return false;
            
        } catch (PDOException $e) {
            error_log("Erreur connexion utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre à jour la dernière connexion
     * 
     * @param int $id ID de l'utilisateur
     * @return bool
     */
    private function mettreAJourDerniereConnexion($id) {
        try {
            $sql = "UPDATE utilisateurs 
                    SET derniere_connexion = NOW() 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute(['id' => $id]);
            
        } catch (PDOException $e) {
            error_log("Erreur mise à jour connexion: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer un utilisateur par son ID
     * 
     * @param int $id ID de l'utilisateur
     * @return array|false
     */
    public function getParId($id) {
        try {
            $sql = "SELECT * FROM utilisateurs WHERE id = :id LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($utilisateur) {
                unset($utilisateur['mot_de_passe']);
            }
            
            return $utilisateur;
            
        } catch (PDOException $e) {
            error_log("Erreur récupération utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un email existe déjà
     * 
     * @param string $email Email à vérifier
     * @param int|null $excludeId ID à exclure (pour modification)
     * @return bool
     */
    public function emailExiste($email, $excludeId = null) {
        try {
            $sql = "SELECT COUNT(*) FROM utilisateurs WHERE email = :email";
            
            if ($excludeId) {
                $sql .= " AND id != :excludeId";
            }
            
            $stmt = $this->db->prepare($sql);
            $params = ['email' => $email];
            
            if ($excludeId) {
                $params['excludeId'] = $excludeId;
            }
            
            $stmt->execute($params);
            return $stmt->fetchColumn() > 0;
            
        } catch (PDOException $e) {
            error_log("Erreur vérification email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Créer un nouvel utilisateur
     * 
     * @param array $donnees Données de l'utilisateur
     * @return int|false ID du nouvel utilisateur ou false
     */
    public function creer($donnees) {
        try {
            $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, role, departement, statut, avatar)
                    VALUES (:nom, :email, :mot_de_passe, :role, :departement, :statut, :avatar)";
            
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([
                'nom'           => $donnees['nom'],
                'email'         => $donnees['email'],
                'mot_de_passe'  => password_hash($donnees['mot_de_passe'], PASSWORD_ARGON2ID),
                'role'          => $donnees['role'] ?? 'viewer',
                'departement'   => $donnees['departement'],
                'statut'        => $donnees['statut'] ?? 'actif',
                'avatar'        => $donnees['avatar'] ?? null
            ]);
            
            return $result ? $this->db->lastInsertId() : false;
            
        } catch (PDOException $e) {
            error_log("Erreur création utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Modifier un utilisateur
     * 
     * @param int $id ID de l'utilisateur
     * @param array $donnees Nouvelles données
     * @return bool
     */
    public function modifier($id, $donnees) {
        try {
            $sql = "UPDATE utilisateurs 
                    SET nom = :nom, 
                        email = :email, 
                        role = :role, 
                        departement = :departement,
                        avatar = :avatar
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'nom'         => $donnees['nom'],
                'email'       => $donnees['email'],
                'role'        => $donnees['role'],
                'departement' => $donnees['departement'],
                'avatar'      => $donnees['avatar'] ?? null,
                'id'          => $id
            ]);
            
        } catch (PDOException $e) {
            error_log("Erreur modification utilisateur: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Changer le mot de passe
     * 
     * @param int $id ID de l'utilisateur
     * @param string $nouveauMotDePasse Nouveau mot de passe en clair
     * @return bool
     */
    public function changerMotDePasse($id, $nouveauMotDePasse) {
        try {
            $sql = "UPDATE utilisateurs 
                    SET mot_de_passe = :mot_de_passe 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'mot_de_passe' => password_hash($nouveauMotDePasse, PASSWORD_ARGON2ID),
                'id'           => $id
            ]);
            
        } catch (PDOException $e) {
            error_log("Erreur changement mot de passe: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Changer le statut d'un utilisateur
     * 
     * @param int $id ID de l'utilisateur
     * @param string $statut Nouveau statut (actif, inactif, suspendu)
     * @return bool
     */
    public function changerStatut($id, $statut) {
        try {
            $sql = "UPDATE utilisateurs 
                    SET statut = :statut 
                    WHERE id = :id";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'statut' => $statut,
                'id'     => $id
            ]);
            
        } catch (PDOException $e) {
            error_log("Erreur changement statut: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer tous les utilisateurs avec pagination
     * 
     * @param int $limite Nombre d'éléments par page
     * @param int $offset Décalage
     * @param string|null $departement Filtrer par département
     * @return array
     */
    public function getTous($limite = 20, $offset = 0, $departement = null) {
        try {
            $sql = "SELECT id, nom, email, role, departement, statut, avatar, derniere_connexion, date_creation 
                    FROM utilisateurs";
            
            if ($departement) {
                $sql .= " WHERE departement = :departement";
            }
            
            $sql .= " ORDER BY date_creation DESC LIMIT :limite OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            
            if ($departement) {
                $stmt->bindValue(':departement', $departement, PDO::PARAM_STR);
            }
            
            $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur récupération utilisateurs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Compter tous les utilisateurs
     * 
     * @param string|null $departement Filtrer par département
     * @return int
     */
    public function compterTous($departement = null) {
        try {
            $sql = "SELECT COUNT(*) FROM utilisateurs";
            
            if ($departement) {
                $sql .= " WHERE departement = :departement";
            }
            
            $stmt = $this->db->prepare($sql);
            
            if ($departement) {
                $stmt->execute(['departement' => $departement]);
            } else {
                $stmt->execute();
            }
            
            return $stmt->fetchColumn();
            
        } catch (PDOException $e) {
            error_log("Erreur comptage utilisateurs: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Rechercher des utilisateurs
     * 
     * @param string $motCle Mot-clé de recherche
     * @param int $limite Nombre d'éléments
     * @param int $offset Décalage
     * @return array
     */
    public function rechercher($motCle, $limite = 20, $offset = 0) {
        try {
            $sql = "SELECT id, nom, email, role, departement, statut, avatar, derniere_connexion, date_creation 
                    FROM utilisateurs 
                    WHERE nom LIKE :motCle OR email LIKE :motCle 
                    ORDER BY date_creation DESC 
                    LIMIT :limite OFFSET :offset";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':motCle', "%$motCle%", PDO::PARAM_STR);
            $stmt->bindValue(':limite', (int)$limite, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur recherche utilisateurs: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Vérifier si un utilisateur est actif
     * 
     * @param int $id ID de l'utilisateur
     * @return bool
     */
    public function estActif($id) {
        try {
            $sql = "SELECT statut FROM utilisateurs WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['id' => $id]);
            return $stmt->fetchColumn() === 'actif';
            
        } catch (PDOException $e) {
            error_log("Erreur vérification statut: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les statistiques par département
     * 
     * @return array
     */
    public function getStatistiquesParDepartement() {
        try {
            $sql = "SELECT 
                        departement,
                        COUNT(*) as total,
                        SUM(CASE WHEN statut = 'actif' THEN 1 ELSE 0 END) as actifs,
                        SUM(CASE WHEN statut = 'inactif' THEN 1 ELSE 0 END) as inactifs,
                        SUM(CASE WHEN statut = 'suspendu' THEN 1 ELSE 0 END) as suspendus
                    FROM utilisateurs
                    GROUP BY departement";
            
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur statistiques: " . $e->getMessage());
            return [];
        }
    }
}
