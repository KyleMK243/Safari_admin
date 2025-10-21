<?php
/**
 * Shifts CRUD Routes
 * Endpoints: GET, POST, PUT, DELETE /shifts
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Response.php';

class ShiftsRoutes {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    // GET /shifts - Get all shifts
    public function getAll() {
        try {
            $sql = "SELECT s.*, 
                    c.nom as chauffeur_nom, 
                    ct.nom as controleur_nom, 
                    r.nom as receveur_nom,
                    t.nom as trajet_nom
                    FROM shifts s
                    LEFT JOIN equipe_bord c ON s.chauffeur_id = c.id
                    LEFT JOIN equipe_bord ct ON s.controleur_id = ct.id
                    LEFT JOIN equipe_bord r ON s.receveur_id = r.id
                    LEFT JOIN trajets t ON s.trajet_id = t.id
                    ORDER BY s.id DESC";
            $stmt = $this->conn->query($sql);
            $shifts = $stmt->fetchAll();
            
            Response::success($shifts, 'Shifts retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve shifts: ' . $e->getMessage());
        }
    }

    // GET /shifts/{id} - Get single shift
    public function getOne($id) {
        try {
            $sql = "SELECT s.*, 
                    c.nom as chauffeur_nom, 
                    ct.nom as controleur_nom, 
                    r.nom as receveur_nom,
                    t.nom as trajet_nom
                    FROM shifts s
                    LEFT JOIN equipe_bord c ON s.chauffeur_id = c.id
                    LEFT JOIN equipe_bord ct ON s.controleur_id = ct.id
                    LEFT JOIN equipe_bord r ON s.receveur_id = r.id
                    LEFT JOIN trajets t ON s.trajet_id = t.id
                    WHERE s.id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $shift = $stmt->fetch();
            
            if (!$shift) {
                Response::notFound('Shift not found');
            }
            
            Response::success($shift, 'Shift retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve shift: ' . $e->getMessage());
        }
    }

    // POST /shifts - Create new shift
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = [];
            if (empty($data['bus_numero'])) $errors['bus_numero'] = 'Bus numero is required';
            if (empty($data['date_prevue'])) $errors['date_prevue'] = 'Date prevue is required';
            if (empty($data['heure_debut'])) $errors['heure_debut'] = 'Heure debut is required';
            if (empty($data['heure_fin'])) $errors['heure_fin'] = 'Heure fin is required';
            if (empty($data['chauffeur_id'])) $errors['chauffeur_id'] = 'Chauffeur ID is required';
            if (empty($data['controleur_id'])) $errors['controleur_id'] = 'Controleur ID is required';
            if (empty($data['receveur_id'])) $errors['receveur_id'] = 'Receveur ID is required';
            if (empty($data['trajet_id'])) $errors['trajet_id'] = 'Trajet ID is required';
            
            if (!empty($errors)) {
                Response::validationError($errors);
            }
            
            $sql = "INSERT INTO shifts (bus_numero, date_prevue, heure_debut, heure_fin, 
                    chauffeur_id, controleur_id, receveur_id, trajet_id, statut, notes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['bus_numero'],
                $data['date_prevue'],
                $data['heure_debut'],
                $data['heure_fin'],
                $data['chauffeur_id'],
                $data['controleur_id'],
                $data['receveur_id'],
                $data['trajet_id'],
                $data['statut'] ?? 'planifie',
                $data['notes'] ?? null
            ]);
            
            $id = $this->conn->lastInsertId();
            
            Response::success(['id' => $id], 'Shift created successfully', 201);
        } catch(PDOException $e) {
            Response::serverError('Failed to create shift: ' . $e->getMessage());
        }
    }

    // PUT /shifts/{id} - Update shift
    public function update($id) {
        try {
            // Check if shift exists
            $checkSql = "SELECT id FROM shifts WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Shift not found');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "UPDATE shifts SET 
                    bus_numero = ?, date_prevue = ?, heure_debut = ?, heure_fin = ?, 
                    chauffeur_id = ?, controleur_id = ?, receveur_id = ?, trajet_id = ?, 
                    statut = ?, notes = ?
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['bus_numero'] ?? null,
                $data['date_prevue'] ?? null,
                $data['heure_debut'] ?? null,
                $data['heure_fin'] ?? null,
                $data['chauffeur_id'] ?? null,
                $data['controleur_id'] ?? null,
                $data['receveur_id'] ?? null,
                $data['trajet_id'] ?? null,
                $data['statut'] ?? 'planifie',
                $data['notes'] ?? null,
                $id
            ]);
            
            Response::success(['id' => $id], 'Shift updated successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to update shift: ' . $e->getMessage());
        }
    }

    // DELETE /shifts/{id} - Delete shift
    public function delete($id) {
        try {
            // Check if shift exists
            $checkSql = "SELECT id FROM shifts WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Shift not found');
            }
            
            $sql = "DELETE FROM shifts WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            Response::success(null, 'Shift deleted successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to delete shift: ' . $e->getMessage());
        }
    }
}
