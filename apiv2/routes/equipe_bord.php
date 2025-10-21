<?php
/**
 * Equipe Bord CRUD Routes
 * Endpoints: GET, POST, PUT, DELETE /equipe_bord
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Response.php';

class EquipeBordRoutes {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    // GET /equipe_bord - Get all crew members
    public function getAll() {
        try {
            $sql = "SELECT * FROM equipe_bord ORDER BY id DESC";
            $stmt = $this->conn->query($sql);
            $crew = $stmt->fetchAll();
            
            Response::success($crew, 'Crew members retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve crew members: ' . $e->getMessage());
        }
    }

    // GET /equipe_bord/{id} - Get single crew member
    public function getOne($id) {
        try {
            $sql = "SELECT * FROM equipe_bord WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $member = $stmt->fetch();
            
            if (!$member) {
                Response::notFound('Crew member not found');
            }
            
            Response::success($member, 'Crew member retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve crew member: ' . $e->getMessage());
        }
    }

    // POST /equipe_bord - Create new crew member
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = [];
            if (empty($data['nom'])) $errors['nom'] = 'Nom is required';
            if (empty($data['poste'])) $errors['poste'] = 'Poste is required';
            
            if (!empty($errors)) {
                Response::validationError($errors);
            }
            
            $sql = "INSERT INTO equipe_bord (nom, poste, telephone, email, adresse, 
                    bus_affecte, statut, date_embauche, notes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['nom'],
                $data['poste'],
                $data['telephone'] ?? null,
                $data['email'] ?? null,
                $data['adresse'] ?? null,
                $data['bus_affecte'] ?? null,
                $data['statut'] ?? 'actif',
                $data['date_embauche'] ?? null,
                $data['notes'] ?? null
            ]);
            
            $id = $this->conn->lastInsertId();
            
            Response::success(['id' => $id], 'Crew member created successfully', 201);
        } catch(PDOException $e) {
            Response::serverError('Failed to create crew member: ' . $e->getMessage());
        }
    }

    // PUT /equipe_bord/{id} - Update crew member
    public function update($id) {
        try {
            // Check if member exists
            $checkSql = "SELECT id FROM equipe_bord WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Crew member not found');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "UPDATE equipe_bord SET 
                    nom = ?, poste = ?, telephone = ?, email = ?, adresse = ?, 
                    bus_affecte = ?, statut = ?, date_embauche = ?, notes = ?
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['nom'] ?? null,
                $data['poste'] ?? null,
                $data['telephone'] ?? null,
                $data['email'] ?? null,
                $data['adresse'] ?? null,
                $data['bus_affecte'] ?? null,
                $data['statut'] ?? 'actif',
                $data['date_embauche'] ?? null,
                $data['notes'] ?? null,
                $id
            ]);
            
            Response::success(['id' => $id], 'Crew member updated successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to update crew member: ' . $e->getMessage());
        }
    }

    // DELETE /equipe_bord/{id} - Delete crew member
    public function delete($id) {
        try {
            // Check if member exists
            $checkSql = "SELECT id FROM equipe_bord WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Crew member not found');
            }
            
            $sql = "DELETE FROM equipe_bord WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            Response::success(null, 'Crew member deleted successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to delete crew member: ' . $e->getMessage());
        }
    }
}
