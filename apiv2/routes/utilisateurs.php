<?php
/**
 * Utilisateurs CRUD Routes
 * Endpoints: GET, POST, PUT, DELETE /utilisateurs
 */

require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../config/Response.php';

class UtilisateursRoutes {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    // GET /utilisateurs - Get all users
    public function getAll() {
        try {
            $sql = "SELECT id, nom, email, departement, role, statut, avatar, 
                    derniere_connexion, date_creation FROM utilisateurs ORDER BY id DESC";
            $stmt = $this->conn->query($sql);
            $users = $stmt->fetchAll();
            
            Response::success($users, 'Users retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve users: ' . $e->getMessage());
        }
    }

    // GET /utilisateurs/{id} - Get single user
    public function getOne($id) {
        try {
            $sql = "SELECT id, nom, email, departement, role, statut, avatar, 
                    derniere_connexion, date_creation FROM utilisateurs WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            $user = $stmt->fetch();
            
            if (!$user) {
                Response::notFound('User not found');
            }
            
            Response::success($user, 'User retrieved successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to retrieve user: ' . $e->getMessage());
        }
    }

    // POST /utilisateurs - Create new user
    public function create() {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Validation
            $errors = [];
            if (empty($data['nom'])) $errors['nom'] = 'Nom is required';
            if (empty($data['email'])) $errors['email'] = 'Email is required';
            if (empty($data['mot_de_passe'])) $errors['mot_de_passe'] = 'Password is required';
            if (empty($data['departement'])) $errors['departement'] = 'Departement is required';
            
            if (!empty($errors)) {
                Response::validationError($errors);
            }
            
            // Hash password
            $hashedPassword = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO utilisateurs (nom, email, mot_de_passe, departement, role, statut, avatar) 
                    VALUES (?, ?, ?, ?, ?, ?, ?)";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([
                $data['nom'],
                $data['email'],
                $hashedPassword,
                $data['departement'],
                $data['role'] ?? 'viewer',
                $data['statut'] ?? 'actif',
                $data['avatar'] ?? null
            ]);
            
            $id = $this->conn->lastInsertId();
            
            Response::success(['id' => $id], 'User created successfully', 201);
        } catch(PDOException $e) {
            if ($e->getCode() == 23000) {
                Response::error('User with this email already exists', 409);
            }
            Response::serverError('Failed to create user: ' . $e->getMessage());
        }
    }

    // PUT /utilisateurs/{id} - Update user
    public function update($id) {
        try {
            // Check if user exists
            $checkSql = "SELECT id FROM utilisateurs WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('User not found');
            }
            
            $data = json_decode(file_get_contents('php://input'), true);
            
            // Build update query dynamically
            $fields = [];
            $values = [];
            
            if (isset($data['nom'])) {
                $fields[] = 'nom = ?';
                $values[] = $data['nom'];
            }
            if (isset($data['email'])) {
                $fields[] = 'email = ?';
                $values[] = $data['email'];
            }
            if (isset($data['mot_de_passe'])) {
                $fields[] = 'mot_de_passe = ?';
                $values[] = password_hash($data['mot_de_passe'], PASSWORD_DEFAULT);
            }
            if (isset($data['departement'])) {
                $fields[] = 'departement = ?';
                $values[] = $data['departement'];
            }
            if (isset($data['role'])) {
                $fields[] = 'role = ?';
                $values[] = $data['role'];
            }
            if (isset($data['statut'])) {
                $fields[] = 'statut = ?';
                $values[] = $data['statut'];
            }
            if (isset($data['avatar'])) {
                $fields[] = 'avatar = ?';
                $values[] = $data['avatar'];
            }
            
            if (empty($fields)) {
                Response::error('No fields to update', 400);
            }
            
            $values[] = $id;
            $sql = "UPDATE utilisateurs SET " . implode(', ', $fields) . " WHERE id = ?";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($values);
            
            Response::success(['id' => $id], 'User updated successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to update user: ' . $e->getMessage());
        }
    }

    // DELETE /utilisateurs/{id} - Delete user
    public function delete($id) {
        try {
            // Check if user exists
            $checkSql = "SELECT id FROM utilisateurs WHERE id = ?";
            $checkStmt = $this->conn->prepare($checkSql);
            $checkStmt->execute([$id]);
            if (!$checkStmt->fetch()) {
                Response::notFound('User not found');
            }
            
            $sql = "DELETE FROM utilisateurs WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([$id]);
            
            Response::success(null, 'User deleted successfully');
        } catch(PDOException $e) {
            Response::serverError('Failed to delete user: ' . $e->getMessage());
        }
    }
}
