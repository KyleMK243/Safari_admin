<?php
/**
 * Colis CRUD Routes
 * Endpoints: GET, POST, PUT, DELETE /colis
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Response.php';

class ColisRoutes {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    // GET /colis - Get all packages
    public function getAll() {
        try {
            $sql = "SELECT * FROM colis ORDER BY id DESC LIMIT 100";
            $stmt = $this->conn->query($sql);
            $packages = $stmt->fetchAll();
            
            Response::success($packages, 'Packages retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve packages: ' . $e->getMessage());
        }
    }

    // GET /colis/{id} - Get single package
    public function getOne($id) {
        try {
            $sql = "SELECT * FROM colis WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $package = $stmt->fetch();
            
            if (!$package) {
                Response::notFound('Package not found');
            }
            
            Response::success($package, 'Package retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve package: ' . $e->getMessage());
        }
    }

    // POST /colis - Create new package
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = [];
            if (empty($data['numero_colis'])) $errors['numero_colis'] = 'Numero colis is required';
            if (empty($data['code_suivi'])) $errors['code_suivi'] = 'Code suivi is required';
            if (empty($data['expediteur_nom'])) $errors['expediteur_nom'] = 'Expediteur nom is required';
            if (empty($data['expediteur_telephone'])) $errors['expediteur_telephone'] = 'Expediteur telephone is required';
            if (empty($data['destinataire_nom'])) $errors['destinataire_nom'] = 'Destinataire nom is required';
            if (empty($data['destinataire_telephone'])) $errors['destinataire_telephone'] = 'Destinataire telephone is required';
            if (empty($data['arret_depart'])) $errors['arret_depart'] = 'Arret depart is required';
            if (empty($data['arret_arrivee'])) $errors['arret_arrivee'] = 'Arret arrivee is required';
            if (empty($data['date_expedition'])) $errors['date_expedition'] = 'Date expedition is required';
            if (empty($data['description_colis'])) $errors['description_colis'] = 'Description is required';
            if (empty($data['prix_transport'])) $errors['prix_transport'] = 'Prix transport is required';
            if (empty($data['montant_total'])) $errors['montant_total'] = 'Montant total is required';
            
            if (!empty($errors)) {
                Response::validationError($errors);
            }
            
            $sql = "INSERT INTO colis (numero_colis, code_suivi, qr_code, expediteur_nom, 
                    expediteur_telephone, expediteur_email, expediteur_adresse, destinataire_nom, 
                    destinataire_telephone, destinataire_email, destinataire_adresse, arret_depart, 
                    arret_arrivee, bus_id, shift_id, date_expedition, date_livraison_prevue, 
                    description_colis, poids, dimensions, valeur_declaree, fragile, type_colis, 
                    prix_transport, assurance, montant_total, devise, mode_paiement, 
                    reference_paiement, statut_colis, statut_paiement, observations, enregistre_par) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['numero_colis'],
                $data['code_suivi'],
                $data['qr_code'] ?? null,
                $data['expediteur_nom'],
                $data['expediteur_telephone'],
                $data['expediteur_email'] ?? null,
                $data['expediteur_adresse'] ?? null,
                $data['destinataire_nom'],
                $data['destinataire_telephone'],
                $data['destinataire_email'] ?? null,
                $data['destinataire_adresse'] ?? null,
                $data['arret_depart'],
                $data['arret_arrivee'],
                $data['bus_id'] ?? null,
                $data['shift_id'] ?? null,
                $data['date_expedition'],
                $data['date_livraison_prevue'] ?? null,
                $data['description_colis'],
                $data['poids'] ?? null,
                $data['dimensions'] ?? null,
                $data['valeur_declaree'] ?? null,
                $data['fragile'] ?? 0,
                $data['type_colis'] ?? 'standard',
                $data['prix_transport'],
                $data['assurance'] ?? 0.00,
                $data['montant_total'],
                $data['devise'] ?? 'CDF',
                $data['mode_paiement'] ?? 'especes',
                $data['reference_paiement'] ?? null,
                $data['statut_colis'] ?? 'enregistre',
                $data['statut_paiement'] ?? 'non_paye',
                $data['observations'] ?? null,
                $data['enregistre_par'] ?? null
            ]);
            
            $id = $this->conn->lastInsertId();
            
            Response::success(['id' => $id], 'Package created successfully', 201);
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                Response::error('Package with this numero or code suivi already exists', 409);
            }
            Response::serverError('Failed to create package: ' . $e->getMessage());
        }
    }

    // PUT /colis/{id} - Update package
    public function update($id) {
        try {
            // Check if package exists
            $checkSql = "SELECT id FROM colis WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Package not found');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            $sql = "UPDATE colis SET 
                    statut_colis = ?, statut_paiement = ?, date_livraison_effective = ?, 
                    observations = ?, livre_par = ?
                    WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['statut_colis'] ?? 'enregistre',
                $data['statut_paiement'] ?? 'non_paye',
                $data['date_livraison_effective'] ?? null,
                $data['observations'] ?? null,
                $data['livre_par'] ?? null,
                $id
            ]);
            
            Response::success(['id' => $id], 'Package updated successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to update package: ' . $e->getMessage());
        }
    }

    // DELETE /colis/{id} - Delete package
    public function delete($id) {
        try {
            // Check if package exists
            $checkSql = "SELECT id FROM colis WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('Package not found');
            }
            
            $sql = "DELETE FROM colis WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            Response::success(null, 'Package deleted successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to delete package: ' . $e->getMessage());
        }
    }
}
