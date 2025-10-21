<?php
/**
 * Bus CRUD Routes
 * Endpoints: GET, POST, PUT, DELETE /bus
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Response.php';

class BusRoutes {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    // GET /bus - Get all buses
    public function getAll() {
        try {
            $sql = "SELECT * FROM bus ORDER BY id DESC";
            $stmt = $this->conn->query($sql);
            $buses = $stmt->fetchAll();
            
            Response::success($buses, 'Buses retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve buses: ' . $e->getMessage());
        }
    }

    // GET /bus/{id} - Get single bus
    public function getOne($id) {
        try {
            $sql = "SELECT * FROM bus WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $bus = $stmt->fetch();
            
            if (!$bus) {
                Response::notFound('Bus not found');
            }
            
            Response::success($bus, 'Bus retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve bus: ' . $e->getMessage());
        }
    }

    // POST /bus - Create new bus
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = [];
            if (empty($data['numero'])) $errors['numero'] = 'Numero is required';
            if (empty($data['immatriculation'])) $errors['immatriculation'] = 'Immatriculation is required';
            
            if (!empty($errors)) {
                Response::validationError($errors);
            }
            
            $sql = "INSERT INTO bus (numero, immatriculation, marque, modele, annee, capacite, 
                    kilometrage, ligne_affectee, statut, modules, notes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['numero'],
                $data['immatriculation'],
                $data['marque'] ?? null,
                $data['modele'] ?? null,
                $data['annee'] ?? null,
                $data['capacite'] ?? null,
                $data['kilometrage'] ?? 0,
                $data['ligne_affectee'] ?? '',
                $data['statut'] ?? 'actif',
                $data['modules'] ?? null,
                $data['notes'] ?? null
            ]);
            
            $id = $this->conn->lastInsertId();
            
            Response::success(['id' => $id], 'Bus created successfully', 201);
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                Response::error('Bus with this numero or immatriculation already exists', 409);
            }
            Response::serverError('Failed to create bus: ' . $e->getMessage());
        }
    }

    // PUT /bus/{id} - Update bus
    public function update($id) {
        try {
            // Check if bus exists
            $checkSql = "SELECT id FROM bus WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Bus not found');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "UPDATE bus SET 
                    numero = ?, immatriculation = ?, marque = ?, modele = ?, 
                    annee = ?, capacite = ?, kilometrage = ?, ligne_affectee = ?, 
                    statut = ?, modules = ?, notes = ?
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['numero'] ?? null,
                $data['immatriculation'] ?? null,
                $data['marque'] ?? null,
                $data['modele'] ?? null,
                $data['annee'] ?? null,
                $data['capacite'] ?? null,
                $data['kilometrage'] ?? 0,
                $data['ligne_affectee'] ?? '',
                $data['statut'] ?? 'actif',
                $data['modules'] ?? null,
                $data['notes'] ?? null,
                $id
            ]);
            
            Response::success(['id' => $id], 'Bus updated successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to update bus: ' . $e->getMessage());
        }
    }

    // DELETE /bus/{id} - Delete bus
    public function delete($id) {
        try {
            // Check if bus exists
            $checkSql = "SELECT id FROM bus WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Bus not found');
            }
            
            $sql = "DELETE FROM bus WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            Response::success(null, 'Bus deleted successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to delete bus: ' . $e->getMessage());
        }
    }
}
