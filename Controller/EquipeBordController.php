<?php
/**
 * Contrôleur Équipe de Bord
 * Gère toutes les opérations CRUD pour les membres de l'équipe
 */

require_once ROOT_PATH . '/Model/EquipeBord.php';

class EquipeBordController {
    private $equipeModel;

    public function __construct() {
        // Vérifier que l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = "Vous devez être connecté pour accéder à cette page";
            redirect('/login');
            exit;
        }

        $this->equipeModel = new EquipeBord();
    }

    /**
     * Afficher la page équipe de bord
     */
    public function index() {
        try {
            // Récupérer les filtres depuis l'URL (comme BusController)
            $filtrePoste = isset($_GET['poste']) && $_GET['poste'] !== '' ? $_GET['poste'] : null;
            $filtreStatut = isset($_GET['statut']) && $_GET['statut'] !== '' ? $_GET['statut'] : null;
            $recherche = isset($_GET['search']) && $_GET['search'] !== '' ? trim($_GET['search']) : null;
            
            // Debug
            error_log("=== FILTRES EQUIPE ===");
            error_log("Poste: " . ($filtrePoste ?? 'null'));
            error_log("Statut: " . ($filtreStatut ?? 'null'));
            error_log("Recherche: " . ($recherche ?? 'null'));
            
            // Récupérer les membres avec filtres (sans pagination pour l'instant)
            $membres = $this->equipeModel->getEquipeAvecFiltres($filtrePoste, $filtreStatut, $recherche);
            
            error_log("Nombre de membres trouvés: " . count($membres));
            
            // Récupérer le nombre total de membres
            $totalMembres = count($membres);
            
            // Récupérer les postes disponibles
            $postes = $this->equipeModel->getPostesDisponibles();
            
            // Récupérer tous les bus pour le modal
            require_once ROOT_PATH . '/Model/Bus.php';
            $busModel = new Bus();
            $busList = $busModel->getBusAvecPagination(1000, 0); // Tous les bus
            
            // Récupérer tous les membres actifs NON AFFECTÉS pour le modal (par poste)
            $tousLesMembres = $this->equipeModel->getEquipeAvecPagination(1000, 0, null, 'actif');
            
            // Filtrer uniquement ceux qui ne sont pas affectés à un bus
            $chauffeurs = array_filter($tousLesMembres, function($m) {
                return $m['poste'] === 'chauffeur' && empty($m['bus_affecte']);
            });
            
            $controleurs = array_filter($tousLesMembres, function($m) {
                return $m['poste'] === 'controleur' && empty($m['bus_affecte']);
            });
            
            $receveurs = array_filter($tousLesMembres, function($m) {
                return $m['poste'] === 'receveur' && empty($m['bus_affecte']);
            });
            
            // Charger la vue
            require VIEW_PATH . '/equipe-bord.php';
            
        } catch (Exception $e) {
            logMessage("Erreur lors du chargement de la page équipe de bord: " . $e->getMessage(), "ERROR");
            $_SESSION['error'] = "Erreur lors du chargement des données";
            redirect('/dashboard_' . $_SESSION['departement']);
        }
    }

    /**
     * Créer un nouveau membre (AJAX)
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        if (!validateCSRFToken(post('csrf_token'))) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            exit;
        }

        if (!in_array($_SESSION['role'], ['admin', 'supervisor'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permissions insuffisantes']);
            exit;
        }

        try {
            $donnees = [
                'nom' => trim(post('nom')),
                'poste' => post('poste'),
                'telephone' => trim(post('telephone')),
                'email' => trim(post('email', '')),
                'statut' => post('statut', 'actif'),
                'bus_affecte' => post('bus_affecte') ?: null,
                'date_embauche' => post('date_embauche') ?: date('Y-m-d')
            ];

            // Validation
            if (empty($donnees['nom'])) {
                throw new Exception("Le nom est obligatoire");
            }
            if (empty($donnees['poste'])) {
                throw new Exception("Le poste est obligatoire");
            }

            $result = $this->equipeModel->ajouterMembre($donnees);

            if ($result) {
                logMessage("Membre équipe créé: {$donnees['nom']} par l'utilisateur {$_SESSION['user_id']}", "INFO");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Membre ajouté avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de l'ajout du membre");
            }

        } catch (Exception $e) {
            logMessage("Erreur création membre équipe: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Mettre à jour un membre (AJAX)
     */
    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        if (!validateCSRFToken(post('csrf_token'))) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            exit;
        }

        if (!in_array($_SESSION['role'], ['admin', 'supervisor'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Permissions insuffisantes']);
            exit;
        }

        try {
            $membreId = (int) post('membre_id');
            
            if (!$membreId) {
                throw new Exception("ID du membre manquant");
            }

            $donnees = [
                'nom' => trim(post('nom')),
                'poste' => post('poste'),
                'telephone' => trim(post('telephone')),
                'email' => trim(post('email', '')),
                'statut' => post('statut', 'actif'),
                'bus_affecte' => post('bus_affecte') ?: null,
                'date_embauche' => post('date_embauche')
            ];

            $result = $this->equipeModel->mettreAJourMembre($membreId, $donnees);

            if ($result) {
                logMessage("Membre équipe modifié: ID $membreId par l'utilisateur {$_SESSION['user_id']}", "INFO");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Membre modifié avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de la modification du membre");
            }

        } catch (Exception $e) {
            logMessage("Erreur modification membre équipe: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Supprimer un membre (AJAX)
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        if (!validateCSRFToken(post('csrf_token'))) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            exit;
        }

        if ($_SESSION['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Seul un administrateur peut supprimer un membre']);
            exit;
        }

        try {
            $membreId = (int) post('membre_id');
            
            if (!$membreId) {
                throw new Exception("ID du membre manquant");
            }

            $result = $this->equipeModel->supprimerMembre($membreId);

            if ($result) {
                logMessage("Membre équipe supprimé: ID $membreId par l'utilisateur {$_SESSION['user_id']}", "WARNING");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Membre supprimé avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de la suppression du membre");
            }

        } catch (Exception $e) {
            logMessage("Erreur suppression membre équipe: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Changer le statut d'un membre (AJAX)
     */
    public function changeStatus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        if (!validateCSRFToken(post('csrf_token'))) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            exit;
        }

        try {
            $membreId = (int) post('membre_id');
            $statut = post('statut');
            
            if (!$membreId || !$statut) {
                throw new Exception("Données manquantes");
            }

            $statutsValides = ['actif', 'conge', 'inactif'];
            if (!in_array($statut, $statutsValides)) {
                throw new Exception("Statut invalide");
            }

            $result = $this->equipeModel->changerStatut($membreId, $statut);

            if ($result) {
                logMessage("Statut membre modifié: ID $membreId -> $statut par l'utilisateur {$_SESSION['user_id']}", "INFO");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Statut modifié avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors du changement de statut");
            }

        } catch (Exception $e) {
            logMessage("Erreur changement statut membre: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Affecter un membre à un bus (AJAX)
     */
    public function affecterBus() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        if (!validateCSRFToken(post('csrf_token'))) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Token CSRF invalide']);
            exit;
        }

        try {
            $membreId = (int) post('membre_id');
            $numeroBus = post('numero_bus');
            
            if (!$membreId || !$numeroBus) {
                throw new Exception("Données manquantes");
            }

            $result = $this->equipeModel->affecterBus($membreId, $numeroBus);

            if ($result) {
                logMessage("Membre $membreId affecté au bus $numeroBus par l'utilisateur {$_SESSION['user_id']}", "INFO");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Membre affecté au bus avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de l'affectation");
            }

        } catch (Exception $e) {
            logMessage("Erreur affectation bus: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Récupérer les détails d'un membre (AJAX)
     */
    public function getDetails() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $membreId = (int) ($_GET['membre_id'] ?? 0);
            
            if (!$membreId) {
                throw new Exception("ID du membre manquant");
            }

            $membre = $this->equipeModel->getMembreParId($membreId);
            
            if (!$membre) {
                throw new Exception("Membre non trouvé");
            }

            echo json_encode([
                'success' => true,
                'membre' => $membre
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
     * Rechercher des membres (AJAX)
     */
    public function search() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $motCle = trim($_GET['q'] ?? '');
            
            if (empty($motCle)) {
                throw new Exception("Mot-clé de recherche manquant");
            }

            $resultats = $this->equipeModel->chercherEquipe($motCle);

            echo json_encode([
                'success' => true,
                'resultats' => $resultats,
                'count' => count($resultats)
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
     * Filtrer les membres (AJAX)
     */
    public function filter() {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            $poste = $_GET['poste'] ?? null;
            $statut = $_GET['statut'] ?? null;
            $limit = (int) ($_GET['limit'] ?? 100);
            $offset = (int) ($_GET['offset'] ?? 0);

            $membres = $this->equipeModel->getEquipeAvecPagination($limit, $offset, $poste, $statut);
            $total = $this->equipeModel->compterTous($poste, $statut);

            echo json_encode([
                'success' => true,
                'membres' => $membres,
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset
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
     * Affecter une équipe complète à un bus avec création de shift (AJAX)
     */
    public function affecterEquipe() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            // Récupérer les données JSON
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!$data) {
                throw new Exception("Données invalides");
            }

            $busId = (int) ($data['bus_id'] ?? 0);
            $chauffeurId = (int) ($data['chauffeur_id'] ?? 0);
            $controleurId = (int) ($data['controleur_id'] ?? 0);
            $receveurId = (int) ($data['receveur_id'] ?? 0);
            $datePrevue = $data['date_prevue'] ?? null;
            $heureDebut = $data['heure_debut'] ?? null;
            $heureFin = $data['heure_fin'] ?? null;
            $trajetId = (int) ($data['trajet_id'] ?? 0);

            // Validation
            if (!$busId) {
                throw new Exception("Bus non sélectionné");
            }
            
            // Au moins un membre doit être sélectionné
            if (!$chauffeurId && !$controleurId && !$receveurId) {
                throw new Exception("Vous devez sélectionner au moins un membre d'équipe");
            }

            // Validation des horaires
            if (!$datePrevue || !$heureDebut || !$heureFin) {
                throw new Exception("Date et horaires sont obligatoires");
            }

            // Vérifier que heure_fin > heure_debut
            if ($heureFin <= $heureDebut) {
                throw new Exception("L'heure de fin doit être après l'heure de début");
            }

            // Récupérer le numéro du bus
            require_once ROOT_PATH . '/Model/Bus.php';
            $busModel = new Bus();
            $bus = $busModel->getBusParId($busId);
            
            if (!$bus) {
                throw new Exception("Bus introuvable");
            }

            $numeroBus = $bus['numero'];

            // Charger le modèle Shifts
            require_once ROOT_PATH . '/Model/Shifts.php';
            $shiftsModel = new Shifts();

            // Vérifier si un shift existe déjà pour ce bus/date/horaire
            $shiftExistant = $shiftsModel->trouverShiftExistant($numeroBus, $datePrevue, $heureDebut, $heureFin);

            // Vérifier les conflits horaires pour chaque membre
            $membresAffecter = [];
            
            if ($chauffeurId) {
                $chauffeur = $this->equipeModel->getMembreParId($chauffeurId);
                if (!$chauffeur) {
                    throw new Exception("Chauffeur introuvable");
                }
                
                // Si shift existant, vérifier que le poste n'est pas déjà occupé
                if ($shiftExistant && $shiftExistant['chauffeur_id'] != 0) {
                    throw new Exception("Un chauffeur est déjà affecté à ce shift. Veuillez d'abord le retirer.");
                }
                
                // Vérifier conflit horaire (exclure le shift existant si on le met à jour)
                $conflit = $shiftsModel->verifierConflitHoraire($chauffeurId, $datePrevue, $heureDebut, $heureFin, $shiftExistant ? $shiftExistant['id'] : null);
                if ($conflit) {
                    $conflitInfo = $conflit[0];
                    throw new Exception("Le chauffeur {$chauffeur['nom']} a déjà un shift le $datePrevue de {$conflitInfo['heure_debut']} à {$conflitInfo['heure_fin']} (Bus #{$conflitInfo['bus_numero']})");
                }
                
                $membresAffecter[] = ['id' => $chauffeurId, 'poste' => 'chauffeur', 'nom' => $chauffeur['nom']];
            }
            
            if ($controleurId) {
                $controleur = $this->equipeModel->getMembreParId($controleurId);
                if (!$controleur) {
                    throw new Exception("Contrôleur introuvable");
                }
                
                // Si shift existant, vérifier que le poste n'est pas déjà occupé
                if ($shiftExistant && $shiftExistant['controleur_id'] != 0) {
                    throw new Exception("Un contrôleur est déjà affecté à ce shift. Veuillez d'abord le retirer.");
                }
                
                // Vérifier conflit horaire (exclure le shift existant si on le met à jour)
                $conflit = $shiftsModel->verifierConflitHoraire($controleurId, $datePrevue, $heureDebut, $heureFin, $shiftExistant ? $shiftExistant['id'] : null);
                if ($conflit) {
                    $conflitInfo = $conflit[0];
                    throw new Exception("Le contrôleur {$controleur['nom']} a déjà un shift le $datePrevue de {$conflitInfo['heure_debut']} à {$conflitInfo['heure_fin']} (Bus #{$conflitInfo['bus_numero']})");
                }
                
                $membresAffecter[] = ['id' => $controleurId, 'poste' => 'contrôleur', 'nom' => $controleur['nom']];
            }
            
            if ($receveurId) {
                $receveur = $this->equipeModel->getMembreParId($receveurId);
                if (!$receveur) {
                    throw new Exception("Receveur introuvable");
                }
                
                // Si shift existant, vérifier que le poste n'est pas déjà occupé
                if ($shiftExistant && $shiftExistant['receveur_id'] != 0) {
                    throw new Exception("Un receveur est déjà affecté à ce shift. Veuillez d'abord le retirer.");
                }
                
                // Vérifier conflit horaire (exclure le shift existant si on le met à jour)
                $conflit = $shiftsModel->verifierConflitHoraire($receveurId, $datePrevue, $heureDebut, $heureFin, $shiftExistant ? $shiftExistant['id'] : null);
                if ($conflit) {
                    $conflitInfo = $conflit[0];
                    throw new Exception("Le receveur {$receveur['nom']} a déjà un shift le $datePrevue de {$conflitInfo['heure_debut']} à {$conflitInfo['heure_fin']} (Bus #{$conflitInfo['bus_numero']})");
                }
                
                $membresAffecter[] = ['id' => $receveurId, 'poste' => 'receveur', 'nom' => $receveur['nom']];
            }

            // Décider : Créer un nouveau shift OU mettre à jour un shift existant
            if ($shiftExistant) {
                // MISE À JOUR d'un shift existant
                $shiftId = $shiftExistant['id'];
                
                $result = $shiftsModel->mettreAJourShift(
                    $shiftId,
                    $chauffeurId ?: null,
                    $controleurId ?: null,
                    $receveurId ?: null
                );

                if (!$result) {
                    throw new Exception("Erreur lors de la mise à jour du shift");
                }

                // Mettre à jour equipe_bord.bus_affecte pour affichage rapide
                foreach ($membresAffecter as $membre) {
                    $this->equipeModel->affecterBus($membre['id'], $numeroBus);
                }

                // Log
                $postesAffectes = array_column($membresAffecter, 'poste');
                $nomsAffectes = array_column($membresAffecter, 'nom');
                $messageLog = "Shift #$shiftId mis à jour : Bus #$numeroBus, Date: $datePrevue, Horaire: $heureDebut-$heureFin, Membres ajoutés: " . implode(', ', $nomsAffectes) . " par l'utilisateur {$_SESSION['user_id']}";
                logMessage($messageLog, "INFO");
                
                $nombreMembres = count($membresAffecter);
                $message = "Shift mis à jour avec succès ! $nombreMembres membre(s) ajouté(s) au shift existant du bus #$numeroBus pour le $datePrevue de $heureDebut à $heureFin";
                
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                    'shift_id' => $shiftId,
                    'action' => 'updated'
                ]);
            } else {
                // CRÉATION d'un nouveau shift
                $shiftData = [
                    'bus_numero' => $numeroBus,
                    'date_prevue' => $datePrevue,
                    'heure_debut' => $heureDebut,
                    'heure_fin' => $heureFin,
                    'chauffeur_id' => $chauffeurId ?: 0,
                    'controleur_id' => $controleurId ?: 0,
                    'receveur_id' => $receveurId ?: 0,
                    'trajet_id' => $trajetId ?: 0,
                    'statut' => 'planifie',
                    'notes' => null
                ];

                $shiftId = $shiftsModel->creerShift($shiftData);

                if (!$shiftId) {
                    throw new Exception("Erreur lors de la création du shift");
                }

                // Mettre à jour equipe_bord.bus_affecte pour affichage rapide
                foreach ($membresAffecter as $membre) {
                    $this->equipeModel->affecterBus($membre['id'], $numeroBus);
                }

                // Log
                $postesAffectes = array_column($membresAffecter, 'poste');
                $nomsAffectes = array_column($membresAffecter, 'nom');
                $messageLog = "Shift #$shiftId créé : Bus #$numeroBus, Date: $datePrevue, Horaire: $heureDebut-$heureFin, Équipe: " . implode(', ', $nomsAffectes) . " par l'utilisateur {$_SESSION['user_id']}";
                logMessage($messageLog, "INFO");
                
                $nombreMembres = count($membresAffecter);
                $message = "Shift créé avec succès ! $nombreMembres membre(s) affecté(s) au bus #$numeroBus pour le $datePrevue de $heureDebut à $heureFin";
                
                echo json_encode([
                    'success' => true,
                    'message' => $message,
                    'shift_id' => $shiftId,
                    'action' => 'created'
                ]);
            }

        } catch (Exception $e) {
            logMessage("Erreur affectation équipe: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }

    /**
     * Désaffecter un membre d'un bus (AJAX)
     */
    public function desaffecterMembre() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
            exit;
        }

        try {
            // Récupérer les données JSON
            $json = file_get_contents('php://input');
            $data = json_decode($json, true);

            if (!$data) {
                throw new Exception("Données invalides");
            }

            $membreId = (int) ($data['membre_id'] ?? 0);

            if (!$membreId) {
                throw new Exception("ID du membre manquant");
            }

            // Récupérer les infos du membre avant désaffectation
            $membre = $this->equipeModel->getMembreParId($membreId);
            
            if (!$membre) {
                throw new Exception("Membre introuvable");
            }

            $busAffecte = $membre['bus_affecte'];

            // Désaffecter le membre
            $result = $this->equipeModel->retirerDuBus($membreId);

            if ($result) {
                logMessage("Membre {$membre['nom']} (ID: $membreId) désaffecté du bus #$busAffecte par l'utilisateur {$_SESSION['user_id']}", "INFO");
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Membre désaffecté avec succès'
                ]);
            } else {
                throw new Exception("Erreur lors de la désaffectation");
            }

        } catch (Exception $e) {
            logMessage("Erreur désaffectation membre: " . $e->getMessage(), "ERROR");
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
        exit;
    }
}

?>
