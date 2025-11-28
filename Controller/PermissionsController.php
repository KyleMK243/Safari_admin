<?php
require_once ROOT_PATH . '/Model/Permissions.php';

class PermissionsController {
    private $permissionsModel;

    public function __construct() {
        $this->permissionsModel = new Permissions();
    }

    /**
     * Récupérer tous les modules avec permissions pour un rôle (AJAX)
     */
    public function getPermissionsByRole() {
        header('Content-Type: application/json');
        
        if (!isset($_GET['role'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Rôle non spécifié']);
            exit;
        }

        $role = $_GET['role'];
        $departement = $_GET['departement'] ?? null; // Filtre optionnel par département
        
        try {
            $modules = $this->permissionsModel->getModulesWithPermissions($role, $departement);
            
            echo json_encode([
                'success' => true,
                'modules' => $modules,
                'role' => $role,
                'departement' => $departement
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur lors de la récupération des permissions: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Mettre à jour une permission spécifique (AJAX)
     */
    public function togglePermission() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['role']) || !isset($data['module_id']) || !isset($data['action']) || !isset($data['value'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Données invalides']);
            exit;
        }

        $role = $data['role'];
        $moduleId = $data['module_id'];
        $action = $data['action']; // 'peut_voir', 'peut_creer', 'peut_modifier', 'peut_supprimer'
        $value = $data['value']; // true ou false

        try {
            $success = $this->permissionsModel->updatePermission($role, $moduleId, $action, $value);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Permission mise à jour'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Initialiser les permissions par défaut pour un rôle (AJAX)
     */
    public function initPermissions() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['role'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Rôle non spécifié']);
            exit;
        }

        $role = $data['role'];

        try {
            $success = $this->permissionsModel->initDefaultPermissions($role);
            
            if ($success) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Permissions initialisées avec succès'
                ]);
            } else {
                http_response_code(500);
                echo json_encode([
                    'success' => false,
                    'message' => 'Erreur lors de l\'initialisation'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Erreur serveur: ' . $e->getMessage()
            ]);
        }
        exit;
    }
}
