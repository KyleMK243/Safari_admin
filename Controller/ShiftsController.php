<?php

class ShiftsController {
    private $shiftsModel;

    public function __construct() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        require_once ROOT_PATH . '/Model/Shifts.php';
        $this->shiftsModel = new Shifts();
    }

    /**
     * Afficher la page des shifts
     */
    public function index() {
        try {
            // Récupérer les filtres depuis l'URL (statut + date uniquement pour le roulement journalier)
            $filtreStatut = isset($_GET['statut']) && $_GET['statut'] !== '' ? $_GET['statut'] : null;
            $filtreDate = isset($_GET['date']) && $_GET['date'] !== '' ? $_GET['date'] : null;

            // Debug
            error_log("=== FILTRES ROULEMENT JOURNALIER ===");
            error_log("Statut: " . ($filtreStatut ?? 'null'));
            error_log("Date: " . ($filtreDate ?? 'null'));

            // Récupérer les shifts avec filtres (sans filtre bus)
            $shifts = $this->shiftsModel->getShiftsAvecFiltres($filtreStatut, $filtreDate, null, 100, 0);

            error_log("Nombre de shifts trouvés: " . count($shifts));

            // Charger la vue roulement journalier PL
            $pageTitle = 'Roulement journalier';
            require_once ROOT_PATH . '/Views/shifts.php';

        } catch (Exception $e) {
            error_log("Erreur dans ShiftsController::index() : " . $e->getMessage());
            $_SESSION['error'] = "Erreur lors du chargement des shifts : " . $e->getMessage();
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
    }

    /**
     * Récupérer les détails d'un shift (AJAX)
     */
    public function getDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $shiftId = (int) ($_GET['shift_id'] ?? 0);

            if (!$shiftId) {
                throw new Exception("ID du shift manquant");
            }

            $shift = $this->shiftsModel->getShiftParId($shiftId);

            if (!$shift) {
                throw new Exception("Shift non trouvé");
            }

            echo json_encode([
                'success' => true,
                'shift' => $shift
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
     * Changer le statut d'un shift (AJAX)
     */
    public function changeStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $shiftId = (int) ($data['shift_id'] ?? 0);
            $statut = $data['statut'] ?? '';

            if (!$shiftId || !$statut) {
                throw new Exception("Données manquantes");
            }

            $statutsValides = ['planifie', 'actif', 'termine', 'annule'];
            if (!in_array($statut, $statutsValides)) {
                throw new Exception("Statut invalide");
            }

            $result = $this->shiftsModel->changerStatut($shiftId, $statut);

            if ($result) {
                logMessage("Statut shift modifié: ID $shiftId -> $statut par l'utilisateur {$_SESSION['user_id']}", "INFO");

                echo json_encode([
                    'success' => true,
                    'message' => 'Statut modifié avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors du changement de statut");
            }

        } catch (Exception $e) {
            logMessage("Erreur changement statut shift: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Annuler un shift (AJAX)
     */
    public function annuler() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            $shiftId = (int) ($data['shift_id'] ?? 0);
            $motif = $data['motif'] ?? 'Non spécifié';

            if (!$shiftId) {
                throw new Exception("ID du shift manquant");
            }

            $result = $this->shiftsModel->annulerShift($shiftId, $motif);

            if ($result) {
                logMessage("Shift annulé: ID $shiftId, Motif: $motif par l'utilisateur {$_SESSION['user_id']}", "WARNING");

                echo json_encode([
                    'success' => true,
                    'message' => 'Shift annulé avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de l'annulation du shift");
            }

        } catch (Exception $e) {
            logMessage("Erreur annulation shift: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Générer des suggestions de shifts (AJAX)
     */
    public function getSuggestions() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $date = $_GET['date'] ?? null;
            $limit = (int) ($_GET['limit'] ?? 10);

            error_log("=== GÉNÉRATION SUGGESTIONS ===");
            error_log("Date: " . ($date ?? 'null'));
            error_log("Limit: " . $limit);

            $suggestions = $this->shiftsModel->genererSuggestions($date, $limit);

            error_log("Nombre de suggestions: " . count($suggestions));

            echo json_encode([
                'success' => true,
                'suggestions' => $suggestions,
                'date' => $date ?? date('Y-m-d', strtotime('+1 day'))
            ]);

        } catch (Exception $e) {
            error_log("ERREUR génération suggestions: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            logMessage("Erreur génération suggestions: " . $e->getMessage(), "ERROR");
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage(),
                'debug' => $e->getTraceAsString()
            ]);
        }
        exit;
    }
}

?>
