<?php
/**
 * Alertes CRUD Routes
 * Endpoints: GET, POST, PUT, DELETE /alertes
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Response.php';

class AlertesRoutes {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    // GET /alertes - Get all alerts
    public function getAll() {
        try {
            $sql = "SELECT a.*, 
                    b.numero as bus_numero,
                    e.nom as membre_nom
                    FROM alertes a
                    LEFT JOIN bus b ON a.bus_id = b.id
                    LEFT JOIN equipe_bord e ON a.membre_id = e.id
                    ORDER BY a.id DESC LIMIT 100";
            $stmt = $this->conn->query($sql);
            $alerts = $stmt->fetchAll();
            
            Response::success($alerts, 'Alerts retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve alerts: ' . $e->getMessage());
        }
    }

    // GET /alertes/{id} - Get single alert
    public function getOne($id) {
        try {
            $sql = "SELECT a.*, 
                    b.numero as bus_numero,
                    e.nom as membre_nom
                    FROM alertes a
                    LEFT JOIN bus b ON a.bus_id = b.id
                    LEFT JOIN equipe_bord e ON a.membre_id = e.id
                    WHERE a.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $alert = $stmt->fetch();
            
            if (!$alert) {
                Response::notFound('Alert not found');
            }
            
            Response::success($alert, 'Alert retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve alert: ' . $e->getMessage());
        }
    }

    // POST /alertes - Create new alert
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = [];
            if (empty($data['type_alerte'])) $errors['type_alerte'] = 'Type alerte is required';
            if (empty($data['titre'])) $errors['titre'] = 'Titre is required';
            if (empty($data['message'])) $errors['message'] = 'Message is required';
            
            if (!empty($errors)) {
                Response::validationError($errors);
            }
            
            $sql = "INSERT INTO alertes (type_alerte, titre, message, bus_id, membre_id, 
                    statut, priorite, localisation) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['type_alerte'],
                $data['titre'],
                $data['message'],
                $data['bus_id'] ?? null,
                $data['membre_id'] ?? null,
                $data['statut'] ?? 'nouveau',
                $data['priorite'] ?? 'moyenne',
                $data['localisation'] ?? null
            ]);
            
            $id = $this->conn->lastInsertId();
            
            Response::success(['id' => $id], 'Alert created successfully', 201);
        } catch(PDOException $e) {
            Response::serverError('Failed to create alert: ' . $e->getMessage());
        }
    }

    // PUT /alertes/{id} - Update alert
    public function update($id) {
        try {
            // Check if alert exists
            $checkSql = "SELECT id FROM alertes WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Alert not found');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "UPDATE alertes SET 
                    statut = ?, priorite = ?, date_resolution = ?, resolu_par = ?
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['statut'] ?? 'nouveau',
                $data['priorite'] ?? 'moyenne',
                $data['date_resolution'] ?? null,
                $data['resolu_par'] ?? null,
                $id
            ]);
            
            Response::success(['id' => $id], 'Alert updated successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to update alert: ' . $e->getMessage());
        }
    }

    // DELETE /alertes/{id} - Delete alert
    public function delete($id) {
        try {
            // Check if alert exists
            $checkSql = "SELECT id FROM alertes WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Alert not found');
            }
            
            $sql = "DELETE FROM alertes WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            Response::success(null, 'Alert deleted successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to delete alert: ' . $e->getMessage());
        }
    }
}
