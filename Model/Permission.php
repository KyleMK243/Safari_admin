<?php
/**
 * Model Permission
 * Gère les permissions d'accès aux modules
 */

class Permission {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Obtenir les modules accessibles pour un rôle et département
     * 
     * @param string $role Rôle de l'utilisateur
     * @param string $departement Département (PL, BT, RH)
     * @return array Liste des modules accessibles
     */
    public function getModulesAccessibles($role, $departement) {
        try {
            $sql = "SELECT m.*, p.peut_voir, p.peut_creer, p.peut_modifier, p.peut_supprimer
                    FROM modules m
                    INNER JOIN permissions p ON m.id = p.module_id
                    WHERE p.role = :role 
                    AND m.departement = :departement
                    AND m.actif = TRUE
                    AND p.peut_voir = TRUE
                    ORDER BY m.ordre ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'role' => $role,
                'departement' => $departement
            ]);
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur récupération modules: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Vérifier si un utilisateur peut accéder à une route
     * 
     * @param string $role Rôle de l'utilisateur
     * @param string $route Route demandée
     * @return bool True si accès autorisé
     */
    public function peutAcceder($role, $route) {
        try {
            $sql = "SELECT COUNT(*) 
                    FROM modules m
                    INNER JOIN permissions p ON m.id = p.module_id
                    WHERE p.role = :role 
                    AND m.route = :route
                    AND m.actif = TRUE
                    AND p.peut_voir = TRUE";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'role' => $role,
                'route' => $route
            ]);
            
            return $stmt->fetchColumn() > 0;
            
        } catch (PDOException $e) {
            error_log("Erreur vérification accès: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les permissions détaillées pour un module
     * 
     * @param string $role Rôle de l'utilisateur
     * @param string $route Route du module
     * @return array|false Permissions ou false
     */
    public function getPermissionsModule($role, $route) {
        try {
            $sql = "SELECT p.peut_voir, p.peut_creer, p.peut_modifier, p.peut_supprimer, m.nom
                    FROM modules m
                    INNER JOIN permissions p ON m.id = p.module_id
                    WHERE p.role = :role 
                    AND m.route = :route
                    AND m.actif = TRUE";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                'role' => $role,
                'route' => $route
            ]);
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur récupération permissions: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir tous les modules d'un département
     * 
     * @param string $departement Département (PL, BT, RH)
     * @return array Liste des modules
     */
    public function getTousModules($departement = null) {
        try {
            $sql = "SELECT * FROM modules WHERE actif = TRUE";
            
            if ($departement) {
                $sql .= " AND departement = :departement";
            }
            
            $sql .= " ORDER BY departement, ordre ASC";
            
            $stmt = $this->db->prepare($sql);
            
            if ($departement) {
                $stmt->execute(['departement' => $departement]);
            } else {
                $stmt->execute();
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (PDOException $e) {
            error_log("Erreur récupération modules: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtenir toutes les permissions pour un rôle
     * 
     * @param string $role Rôle
     * @return array Permissions groupées par département
     */
    public function getPermissionsParRole($role) {
        try {
            $sql = "SELECT m.departement, m.code, m.nom, m.route, m.icone,
                           p.peut_voir, p.peut_creer, p.peut_modifier, p.peut_supprimer
                    FROM modules m
                    LEFT JOIN permissions p ON m.id = p.module_id AND p.role = :role
                    WHERE m.actif = TRUE
                    ORDER BY m.departement, m.ordre ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['role' => $role]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Grouper par département
            $grouped = [];
            foreach ($results as $row) {
                $dept = $row['departement'];
                if (!isset($grouped[$dept])) {
                    $grouped[$dept] = [];
                }
                $grouped[$dept][] = $row;
            }
            
            return $grouped;
            
        } catch (PDOException $e) {
            error_log("Erreur récupération permissions par rôle: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mettre à jour une permission
     * 
     * @param string $role Rôle
     * @param int $moduleId ID du module
     * @param array $permissions Permissions (peut_voir, peut_creer, etc.)
     * @return bool
     */
    public function mettreAJourPermission($role, $moduleId, $permissions) {
        try {
            $sql = "INSERT INTO permissions (role, module_id, peut_voir, peut_creer, peut_modifier, peut_supprimer)
                    VALUES (:role, :module_id, :peut_voir, :peut_creer, :peut_modifier, :peut_supprimer)
                    ON DUPLICATE KEY UPDATE
                    peut_voir = :peut_voir,
                    peut_creer = :peut_creer,
                    peut_modifier = :peut_modifier,
                    peut_supprimer = :peut_supprimer";
            
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                'role' => $role,
                'module_id' => $moduleId,
                'peut_voir' => $permissions['peut_voir'] ?? false,
                'peut_creer' => $permissions['peut_creer'] ?? false,
                'peut_modifier' => $permissions['peut_modifier'] ?? false,
                'peut_supprimer' => $permissions['peut_supprimer'] ?? false
            ]);
            
        } catch (PDOException $e) {
            error_log("Erreur mise à jour permission: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupérer tous les modules d'un département
     */
    public function getModulesByDepartement($departement) {
        try {
            $sql = "SELECT * FROM modules 
                    WHERE departement = ? AND actif = 1 
                    ORDER BY ordre ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$departement]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur getModulesByDepartement: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupérer toutes les permissions d'un département (groupées par rôle)
     */
    public function getAllPermissionsByDepartement($departement) {
        try {
            $sql = "SELECT p.*, m.nom as module_nom, m.code as module_code
                    FROM permissions p
                    INNER JOIN modules m ON p.module_id = m.id
                    WHERE m.departement = ?
                    ORDER BY p.role, m.ordre ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$departement]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Grouper par rôle et module
            $permissions = [];
            foreach ($results as $row) {
                $role = $row['role'];
                $moduleId = $row['module_id'];
                
                if (!isset($permissions[$role])) {
                    $permissions[$role] = [];
                }
                
                $permissions[$role][$moduleId] = [
                    'peut_voir' => $row['peut_voir'],
                    'peut_creer' => $row['peut_creer'],
                    'peut_modifier' => $row['peut_modifier'],
                    'peut_supprimer' => $row['peut_supprimer']
                ];
            }
            
            return $permissions;
        } catch (PDOException $e) {
            error_log("Erreur getAllPermissionsByDepartement: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mettre à jour une permission
     */
    public function updatePermission($role, $moduleId, $type, $value) {
        try {
            $validTypes = ['peut_voir', 'peut_creer', 'peut_modifier', 'peut_supprimer'];
            if (!in_array($type, $validTypes)) {
                return false;
            }

            $sql = "UPDATE permissions 
                    SET $type = ? 
                    WHERE role = ? AND module_id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$value ? 1 : 0, $role, $moduleId]);
        } catch (PDOException $e) {
            error_log("Erreur updatePermission: " . $e->getMessage());
            return false;
        }
    }
}
