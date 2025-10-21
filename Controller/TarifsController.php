<?php

/**
 * Contrôleur Tarifs
 * Gère toutes les opérations CRUD pour les tarifs
 */

require_once ROOT_PATH . '/Model/Tarifs.php';
require_once ROOT_PATH . '/Model/Trajets.php';

class TarifsController {
    private $tarifModel;
    private $trajetModel;

    public function __construct() {
        $this->tarifModel = new Tarifs();
        $this->trajetModel = new Trajets();
    }

    /**
     * Afficher la page principale des tarifs
     */
    public function index() {
        try {
            // Récupérer les tarifs groupés par trajet
            $trajetsAvecTarifs = $this->tarifModel->getTarifsParTrajet();
            
            // Récupérer les statistiques
            $stats = $this->tarifModel->getStatistiques();
            
            // Récupérer tous les trajets pour le formulaire
            $trajets = $this->trajetModel->getTrajets(100, 0);
            
            // Charger la vue
            require VIEW_PATH . '/tarifs.php';
        } catch (Exception $e) {
            logMessage("Erreur lors du chargement de la page tarifs: " . $e->getMessage(), "ERROR");
            $_SESSION['error'] = "Erreur lors du chargement des données";
            redirect('/dashboard_' . $_SESSION['departement']);
        }
    }

    /**
     * Récupérer les détails d'un tarif (AJAX)
     */
    public function getDetails() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $tarifId = (int) ($_GET['tarif_id'] ?? 0);
            
            if (!$tarifId) {
                throw new Exception("ID du tarif manquant");
            }

            $tarif = $this->tarifModel->getTarifById($tarifId);
            
            if (!$tarif) {
                throw new Exception("Tarif non trouvé");
            }

            echo json_encode([
                'success' => true,
                'tarif' => $tarif
            ]);

        } catch (Exception $e) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Enregistrer un tarif (création ou modification) (AJAX)
     */
    public function save() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || empty($data['trajet_id']) || empty($data['type_tarif']) || !isset($data['prix'])) {
                throw new Exception("Données incomplètes");
            }

            // Récupérer le trajet pour générer le nom
            $trajet = $this->trajetModel->getTrajetById($data['trajet_id']);
            if (!$trajet) {
                throw new Exception("Trajet introuvable");
            }

            // Générer le nom du tarif
            $typeLabels = [
                'normal' => 'Tarif Normal',
                'etudiant' => 'Tarif Étudiant',
                'senior' => 'Tarif Senior',
                'enfant' => 'Tarif Enfant',
                'entreprise' => 'Tarif Entreprise',
                'touriste' => 'Tarif Touriste'
            ];
            
            $data['nom'] = ($typeLabels[$data['type_tarif']] ?? 'Tarif') . ' - ' . $trajet['nom'];

            if (!empty($data['id'])) {
                // Modification
                $tarifId = (int) $data['id'];
                $this->tarifModel->mettreAJourTarif($tarifId, $data);
                $message = 'Tarif mis à jour avec succès';
            } else {
                // Création
                $this->tarifModel->ajouterTarif($data);
                $message = 'Tarif créé avec succès';
            }

            echo json_encode([
                'success' => true,
                'message' => $message
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Créer automatiquement les tarifs pour un trajet (AJAX)
     */
    public function creerAuto() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || empty($data['trajet_id']) || !isset($data['prix_normal'])) {
                throw new Exception("Données incomplètes");
            }

            $this->tarifModel->creerTarifsAutomatiques($data['trajet_id'], $data['prix_normal']);

            echo json_encode([
                'success' => true,
                'message' => 'Tarifs créés automatiquement avec succès'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Supprimer un tarif (AJAX)
     */
    public function delete() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!$data || empty($data['id'])) {
                throw new Exception("ID du tarif manquant");
            }

            $this->tarifModel->supprimerTarif($data['id']);

            echo json_encode([
                'success' => true,
                'message' => 'Tarif supprimé avec succès'
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}
