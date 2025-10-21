<?php
/**
 * Trajets CRUD Routes
 * Endpoints: GET, POST, PUT, DELETE /trajets
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Response.php';

class TrajetsRoutes {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    // GET /trajets - Get all trajets
    public function getAll() {
        try {
            $sql = "SELECT * FROM trajets ORDER BY id DESC";
            $stmt = $this->conn->query($sql);
            $trajets = $stmt->fetchAll();
            
            Response::success($trajets, 'Trajets retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve trajets: ' . $e->getMessage());
        }
    }

    // GET /trajets/{id} - Get single trajet
    public function getOne($id) {
        try {
            $sql = "SELECT * FROM trajets WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $trajet = $stmt->fetch();
            
            if (!$trajet) {
                Response::notFound('Trajet not found');
            }
            
            Response::success($trajet, 'Trajet retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve trajet: ' . $e->getMessage());
        }
    }

    // POST /trajets - Create new trajet
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = [];
            if (empty($data['nom'])) $errors['nom'] = 'Nom is required';
            
            if (!empty($errors)) {
                Response::validationError($errors);
            }
            
            $sql = "INSERT INTO trajets (nom, distance_totale, statut) VALUES (?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['nom'],
                $data['distance_totale'] ?? null,
                $data['statut'] ?? 'actif'
            ]);
            
            $id = $this->conn->lastInsertId();
            
            Response::success(['id' => $id], 'Trajet created successfully', 201);
        } catch(PDOException $e) {
            Response::serverError('Failed to create trajet: ' . $e->getMessage());
        }
    }

    // PUT /trajets/{id} - Update trajet
    public function update($id) {
        try {
            // Check if trajet exists
            $checkSql = "SELECT id FROM trajets WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Trajet not found');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "UPDATE trajets SET nom = ?, distance_totale = ?, statut = ? WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['nom'] ?? null,
                $data['distance_totale'] ?? null,
                $data['statut'] ?? 'actif',
                $id
            ]);
            
            Response::success(['id' => $id], 'Trajet updated successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to update trajet: ' . $e->getMessage());
        }
    }

    // DELETE /trajets/{id} - Delete trajet
    public function delete($id) {
        try {
            // Check if trajet exists
            $checkSql = "SELECT id FROM trajets WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Trajet not found');
            }
            
            $sql = "DELETE FROM trajets WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            Response::success(null, 'Trajet deleted successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to delete trajet: ' . $e->getMessage());
        }
    }
}
