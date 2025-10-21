<?php
/**
 * Billets CRUD Routes
 * Endpoints: GET, POST, PUT, DELETE /billets
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Response.php';

class BilletsRoutes {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    // GET /billets - Get all billets
    public function getAll() {
        try {
            $sql = "SELECT * FROM billets ORDER BY id DESC LIMIT 100";
            $stmt = $this->conn->query($sql);
            $billets = $stmt->fetchAll();
            
            Response::success($billets, 'Billets retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve billets: ' . $e->getMessage());
        }
    }

    // GET /billets/{id} - Get single billet
    public function getOne($id) {
        try {
            $sql = "SELECT * FROM billets WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $billet = $stmt->fetch();
            
            if (!$billet) {
                Response::notFound('Billet not found');
            }
            
            Response::success($billet, 'Billet retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve billet: ' . $e->getMessage());
        }
    }

    // POST /billets - Create new billet
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = [];
            if (empty($data['numero_billet'])) $errors['numero_billet'] = 'Numero billet is required';
            if (empty($data['trajet_id'])) $errors['trajet_id'] = 'Trajet ID is required';
            if (empty($data['tarif_id'])) $errors['tarif_id'] = 'Tarif ID is required';
            if (empty($data['arret_depart'])) $errors['arret_depart'] = 'Arret depart is required';
            if (empty($data['arret_arrivee'])) $errors['arret_arrivee'] = 'Arret arrivee is required';
            if (empty($data['date_voyage'])) $errors['date_voyage'] = 'Date voyage is required';
            if (empty($data['prix_paye'])) $errors['prix_paye'] = 'Prix paye is required';
            
            if (!empty($errors)) {
                Response::validationError($errors);
            }
            
            $sql = "INSERT INTO billets (numero_billet, qr_code, trajet_id, tarif_id, shift_id, 
                    bus_id, client_id, arret_depart, arret_arrivee, date_voyage, heure_depart, 
                    siege_numero, prix_paye, devise, statut_billet, mode_paiement, 
                    reference_paiement, vendu_par, point_vente) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['numero_billet'],
                $data['qr_code'] ?? null,
                $data['trajet_id'],
                $data['tarif_id'],
                $data['shift_id'] ?? null,
                $data['bus_id'] ?? null,
                $data['client_id'] ?? null,
                $data['arret_depart'],
                $data['arret_arrivee'],
                $data['date_voyage'],
                $data['heure_depart'] ?? null,
                $data['siege_numero'] ?? null,
                $data['prix_paye'],
                $data['devise'] ?? 'CDF',
                $data['statut_billet'] ?? 'reserve',
                $data['mode_paiement'] ?? 'especes',
                $data['reference_paiement'] ?? null,
                $data['vendu_par'] ?? null,
                $data['point_vente'] ?? null
            ]);
            
            $id = $this->conn->lastInsertId();
            
            Response::success(['id' => $id], 'Billet created successfully', 201);
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                Response::error('Billet with this numero already exists', 409);
            }
            Response::serverError('Failed to create billet: ' . $e->getMessage());
        }
    }

    // PUT /billets/{id} - Update billet
    public function update($id) {
        try {
            // Check if billet exists
            $checkSql = "SELECT id FROM billets WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Billet not found');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "UPDATE billets SET 
                    statut_billet = ?, mode_paiement = ?, reference_paiement = ?, 
                    date_utilisation = ?, date_annulation = ?, motif_annulation = ?
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['statut_billet'] ?? 'reserve',
                $data['mode_paiement'] ?? 'especes',
                $data['reference_paiement'] ?? null,
                $data['date_utilisation'] ?? null,
                $data['date_annulation'] ?? null,
                $data['motif_annulation'] ?? null,
                $id
            ]);
            
            Response::success(['id' => $id], 'Billet updated successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to update billet: ' . $e->getMessage());
        }
    }

    // DELETE /billets/{id} - Delete billet
    public function delete($id) {
        try {
            // Check if billet exists
            $checkSql = "SELECT id FROM billets WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Billet not found');
            }
            
            $sql = "DELETE FROM billets WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            Response::success(null, 'Billet deleted successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to delete billet: ' . $e->getMessage());
        }
    }
}
