<?php
class Permissions {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Récupérer tous les modules avec leurs permissions pour un rôle
     * @param string $role Rôle de l'utilisateur
     * @param string|null $departement Filtre optionnel par département (PL, BT, RH)
     */
    public function getModulesWithPermissions($role, $departement = null) {
        $sql = "
            SELECT 
                m.id,
                m.code,
                m.nom,
                m.description,
                m.departement,
                m.section,
                m.icone,
                p.id as permission_id,
                p.peut_voir,
                p.peut_creer,
                p.peut_modifier,
                p.peut_supprimer
            FROM modules m
            LEFT JOIN permissions p ON m.id = p.module_id AND p.role = :role
            WHERE m.actif = 1
        ";
        
        // Ajouter le filtre par département si spécifié
        $params = ['role' => $role];
        if ($departement !== null) {
            $sql .= " AND m.departement = :departement";
            $params['departement'] = $departement;
        }
        
        $sql .= "
            ORDER BY m.departement, 
                     CASE 
                         WHEN m.section IS NULL THEN 0 
                         ELSE 1 
                     END,
                     m.section,
                     m.ordre, 
                     m.nom
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Grouper par département et section
        $grouped = [];
        foreach ($results as $row) {
            $dept = $row['departement'];
            $section = $row['section'] ?? 'GENERAL';
            
            if (!isset($grouped[$dept])) {
                $grouped[$dept] = [];
            }
            
            if (!isset($grouped[$dept][$section])) {
                $grouped[$dept][$section] = [];
            }
            
            $grouped[$dept][$section][] = [
                'id' => $row['id'],
                'code' => $row['code'],
                'nom' => $row['nom'],
                'description' => $row['description'],
                'icone' => $row['icone'],
                'section' => $row['section'],
                'permission_id' => $row['permission_id'],
                'peut_voir' => $row['peut_voir'] ?? 0,
                'peut_creer' => $row['peut_creer'] ?? 0,
                'peut_modifier' => $row['peut_modifier'] ?? 0,
                'peut_supprimer' => $row['peut_supprimer'] ?? 0
            ];
        }
        
        return $grouped;
    }

    /**
     * Mettre à jour une permission spécifique
     */
    public function updatePermission($role, $moduleId, $action, $value) {
        try {
            // Vérifier si la permission existe
            $sql = "SELECT id FROM permissions WHERE role = :role AND module_id = :module_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute(['role' => $role, 'module_id' => $moduleId]);
            $permissionId = $stmt->fetchColumn();
            
            if ($permissionId) {
                // Mettre à jour la permission existante
                $sql = "UPDATE permissions SET $action = :value WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    'value' => $value ? 1 : 0,
                    'id' => $permissionId
                ]);
            } else {
                // Créer une nouvelle permission
                $sql = "
                    INSERT INTO permissions (role, module_id, peut_voir, peut_creer, peut_modifier, peut_supprimer)
                    VALUES (:role, :module_id, :peut_voir, :peut_creer, :peut_modifier, :peut_supprimer)
                ";
                $stmt = $this->db->prepare($sql);
                return $stmt->execute([
                    'role' => $role,
                    'module_id' => $moduleId,
                    'peut_voir' => $action === 'peut_voir' ? 1 : 0,
                    'peut_creer' => $action === 'peut_creer' ? 1 : 0,
                    'peut_modifier' => $action === 'peut_modifier' ? 1 : 0,
                    'peut_supprimer' => $action === 'peut_supprimer' ? 1 : 0
                ]);
            }
        } catch (Exception $e) {
            error_log("Erreur updatePermission: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier si un rôle a une permission spécifique sur un module
     */
    public function hasPermission($role, $moduleCode, $action) {
        $sql = "
            SELECT p.$action
            FROM permissions p
            INNER JOIN modules m ON p.module_id = m.id
            WHERE p.role = :role AND m.code = :code
        ";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'role' => $role,
            'code' => $moduleCode
        ]);
        $result = $stmt->fetchColumn();
        return $result == 1;
    }

    /**
     * Initialiser les permissions par défaut pour un rôle
     */
    public function initDefaultPermissions($role) {
        try {
            $this->db->beginTransaction();
            
            // Récupérer tous les modules actifs
            $sql = "SELECT id FROM modules WHERE actif = 1";
            $stmt = $this->db->query($sql);
            $modules = $stmt->fetchAll(PDO::FETCH_COLUMN);
            
            // Définir les permissions par défaut selon le rôle
            $defaults = $this->getDefaultPermissionsByRole($role);
            
            foreach ($modules as $moduleId) {
                // Vérifier si la permission existe déjà
                $checkSql = "SELECT id FROM permissions WHERE role = :role AND module_id = :module_id";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->execute(['role' => $role, 'module_id' => $moduleId]);
                
                if (!$checkStmt->fetchColumn()) {
                    // Créer la permission
                    $insertSql = "
                        INSERT INTO permissions (role, module_id, peut_voir, peut_creer, peut_modifier, peut_supprimer)
                        VALUES (:role, :module_id, :peut_voir, :peut_creer, :peut_modifier, :peut_supprimer)
                    ";
                    $insertStmt = $this->db->prepare($insertSql);
                    $insertStmt->execute([
                        'role' => $role,
                        'module_id' => $moduleId,
                        'peut_voir' => $defaults['peut_voir'],
                        'peut_creer' => $defaults['peut_creer'],
                        'peut_modifier' => $defaults['peut_modifier'],
                        'peut_supprimer' => $defaults['peut_supprimer']
                    ]);
                }
            }
            
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Erreur initDefaultPermissions: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir les permissions par défaut selon le rôle
     */
    private function getDefaultPermissionsByRole($role) {
        switch ($role) {
            case 'admin':
                return ['peut_voir' => 1, 'peut_creer' => 1, 'peut_modifier' => 1, 'peut_supprimer' => 1];
            case 'supervisor':
                return ['peut_voir' => 1, 'peut_creer' => 1, 'peut_modifier' => 1, 'peut_supprimer' => 0];
            case 'operator':
                return ['peut_voir' => 1, 'peut_creer' => 1, 'peut_modifier' => 0, 'peut_supprimer' => 0];
            case 'viewer':
                return ['peut_voir' => 1, 'peut_creer' => 0, 'peut_modifier' => 0, 'peut_supprimer' => 0];
            default:
                return ['peut_voir' => 0, 'peut_creer' => 0, 'peut_modifier' => 0, 'peut_supprimer' => 0];
        }
    }
}
