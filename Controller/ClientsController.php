<?php
/**
 * Controller Clients
 * Gestion des requêtes liées aux clients
 */

require_once ROOT_PATH . '/Model/Clients.php';

class ClientsController {
    private $clientsModel;

    public function __construct() {
        $this->clientsModel = new Clients();
    }

    /**
     * Afficher la page principale des clients
     */
    public function index() {
        // Récupérer les statistiques
        $stats = $this->clientsModel->getStatistiques();

        // Récupérer tous les clients
        $clients = $this->clientsModel->getTousLesClients();

        // Charger la vue
        require_once ROOT_PATH . '/Views/clients-bt.php';
    }

    /**
     * Récupérer les détails d'un client (AJAX)
     */
    public function getDetails() {
        header('Content-Type: application/json');

        if (!isset($_GET['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'ID client manquant'
            ]);
            return;
        }

        $client = $this->clientsModel->getClientById($_GET['id']);

        if ($client) {
            echo json_encode([
                'success' => true,
                'client' => $client
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Client non trouvé'
            ]);
        }
    }

    /**
     * Ajouter un nouveau client (AJAX)
     */
    public function ajouter() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['nom']) || !isset($data['prenom']) || !isset($data['telephone'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Données incomplètes'
            ]);
            return;
        }

        $result = $this->clientsModel->ajouterClient($data);
        echo json_encode($result);
    }

    /**
     * Mettre à jour un client (AJAX)
     */
    public function modifier() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'ID client manquant'
            ]);
            return;
        }

        $id = $data['id'];
        unset($data['id']);

        $result = $this->clientsModel->mettreAJourClient($id, $data);
        echo json_encode($result);
    }

    /**
     * Supprimer un client (AJAX)
     */
    public function supprimer() {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || !isset($data['id'])) {
            echo json_encode([
                'success' => false,
                'message' => 'ID client manquant'
            ]);
            return;
        }

        $result = $this->clientsModel->supprimerClient($data['id']);
        echo json_encode($result);
    }
}
