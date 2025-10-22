<?php
/**
 * Point d'entrée principal de l'application SafariSmartMobily
 * Gère le routage et la sécurité
 */

// Chargement de la configuration et initialisation
require_once __DIR__ . '/Config/init.php';

// Récupérer l'URL demandée (juste le chemin, sans query string)
$request_uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Déduire le chemin de base (exemple : /HarakaWays)
$script_name = dirname($_SERVER['SCRIPT_NAME']);
$base_path = rtrim(str_replace('\\', '/', $script_name), '/');

// Extraire la route en enlevant le chemin de base
$route = '/';
if (strpos($request_uri, $base_path) === 0) {
    $route = substr($request_uri, strlen($base_path));
}
$route = trim($route, '/');

// Si route vide, on met 'index'
if (empty($route)) {
    $route = 'index';
}

// Validation de la route (sécurité contre path traversal)
if (preg_match('/\.\./', $route) || preg_match('/[^a-zA-Z0-9\-_\/]/', $route)) {
    http_response_code(400);
    die('Route invalide');
}

define('CURRENT_ROUTE', $route);

// Méthode HTTP
$method = $_SERVER['REQUEST_METHOD'];

// Switch des routes
switch ($route) {
    case 'index':
        // Page de login
        require_once ROOT_PATH . '/Controller/AuthController.php';
        $authController = new AuthController();
        $authController->afficherLogin();
        break;
        
    case 'login':
        // Traiter la connexion
        require_once ROOT_PATH . '/Controller/AuthController.php';
        $authController = new AuthController();
        $authController->login();
        break;
        
    case 'logout':
        // Déconnexion
        require_once ROOT_PATH . '/Controller/AuthController.php';
        $authController = new AuthController();
        $authController->logout();
        break;


    // Page d'accueil
    case 'dashboard_PL':
        require_once ROOT_PATH . '/Controller/DashboardController.php';
        $dashboardController = new DashboardController();
        $dashboardController->index();
        break;
    
    case 'dashboard/donnees':
        require_once ROOT_PATH . '/Controller/DashboardController.php';
        $dashboardController = new DashboardController();
        $dashboardController->getDonnees();
        break;
    
    case 'dashboard/bus':
        require_once ROOT_PATH . '/Controller/DashboardController.php';
        $dashboardController = new DashboardController();
        $dashboardController->getDetailsBus();
        break;

    // Partie Gestion des bus
    case 'gestion-bus':
        require_once ROOT_PATH . '/Controller/BusController.php';
        $busController = new BusController();
        $busController->index();
        break;
    
    // Actions AJAX pour les bus
    case 'bus/ajouter':
        require_once ROOT_PATH . '/Controller/BusController.php';
        $busController = new BusController();
        $busController->ajouter();
        break;
    
    case 'bus/modifier':
        require_once ROOT_PATH . '/Controller/BusController.php';
        $busController = new BusController();
        $busController->modifier();
        break;
    
    case 'bus/delete':
        require_once ROOT_PATH . '/Controller/BusController.php';
        $busController = new BusController();
        $busController->delete();
        break;
    
    case 'bus/change-status':
        require_once ROOT_PATH . '/Controller/BusController.php';
        $busController = new BusController();
        $busController->changeStatus();
        break;
    
    case 'bus/details':
        require_once ROOT_PATH . '/Controller/BusController.php';
        $busController = new BusController();
        $busController->getDetails();
        break;
    
    case 'bus/search':
        require_once ROOT_PATH . '/Controller/BusController.php';
        $busController = new BusController();
        $busController->search();
        break;
    
    case 'bus/filter':
        require_once ROOT_PATH . '/Controller/BusController.php';
        $busController = new BusController();
        $busController->filter();
        break;

    // Partie statistiques    
    case 'bi':
        require_once ROOT_PATH . '/Controller/BusinessIntelligenceController.php';
        $biController = new BusinessIntelligenceController();
        $biController->index();
        break;
    
    case 'bi/donnees':
        require_once ROOT_PATH . '/Controller/BusinessIntelligenceController.php';
        $biController = new BusinessIntelligenceController();
        $biController->getDonneesGraphiques();
        break;
    
    // Partie Equipe de bord
    case 'equipe-bord':
        require_once ROOT_PATH . '/Controller/EquipeBordController.php';
        $EquipeBordController = new EquipeBordController();
        $EquipeBordController->index();
        break;
    case 'equipe-bord/details':
        require_once ROOT_PATH . '/Controller/EquipeBordController.php';
        $EquipeBordController = new EquipeBordController();
        $EquipeBordController->getDetails();
        break;
    
    case 'equipe-bord/affecter':
        require_once ROOT_PATH . '/Controller/EquipeBordController.php';
        $EquipeBordController = new EquipeBordController();
        $EquipeBordController->affecterEquipe();
        break;
    
    case 'equipe-bord/desaffecter':
        require_once ROOT_PATH . '/Controller/EquipeBordController.php';
        $EquipeBordController = new EquipeBordController();
        $EquipeBordController->desaffecterMembre();
        break;

    // Partie Trajets
    case 'trajets':
        require_once ROOT_PATH . '/Controller/TrajetsController.php';
        $trajetsController = new TrajetsController();
        $trajetsController->index();
        break;
    
    case 'trajets/details':
        require_once ROOT_PATH . '/Controller/TrajetsController.php';
        $trajetsController = new TrajetsController();
        $trajetsController->getDetails();
        break;
    
    case 'trajets/create':
        require_once ROOT_PATH . '/Controller/TrajetsController.php';
        $trajetsController = new TrajetsController();
        $trajetsController->create();
        break;
    
    case 'trajets/update':
        require_once ROOT_PATH . '/Controller/TrajetsController.php';
        $trajetsController = new TrajetsController();
        $trajetsController->update();
        break;
    
    case 'trajets/delete':
        require_once ROOT_PATH . '/Controller/TrajetsController.php';
        $trajetsController = new TrajetsController();
        $trajetsController->delete();
        break;
    
    case 'trajets/liste':
        require_once ROOT_PATH . '/Controller/TrajetsController.php';
        $trajetsController = new TrajetsController();
        $trajetsController->liste();
        break;
    
    case 'trajets/arrets':
        require_once ROOT_PATH . '/Controller/TrajetsController.php';
        $trajetsController = new TrajetsController();
        $trajetsController->arrets();
        break;
    
    case 'trajets/save':
        require_once ROOT_PATH . '/Controller/TrajetsController.php';
        $trajetsController = new TrajetsController();
        $trajetsController->save();
        break;
    
    case 'trajets/complets':
        require_once ROOT_PATH . '/Controller/TrajetsController.php';
        $trajetsController = new TrajetsController();
        $trajetsController->getTrajetsComplets();
        break;

    // Partie Tarifs
    case 'tarifs':
        require_once ROOT_PATH . '/Controller/TarifsController.php';
        $tarifsController = new TarifsController();
        $tarifsController->index();
        break;
    
    case 'tarifs/details':
        require_once ROOT_PATH . '/Controller/TarifsController.php';
        $tarifsController = new TarifsController();
        $tarifsController->getDetails();
        break;
    
    case 'tarifs/save':
        require_once ROOT_PATH . '/Controller/TarifsController.php';
        $tarifsController = new TarifsController();
        $tarifsController->save();
        break;
    
    case 'tarifs/creerAuto':
        require_once ROOT_PATH . '/Controller/TarifsController.php';
        $tarifsController = new TarifsController();
        $tarifsController->creerAuto();
        break;
    
    case 'tarifs/delete':
        require_once ROOT_PATH . '/Controller/TarifsController.php';
        $tarifsController = new TarifsController();
        $tarifsController->delete();
        break;

    // Partie Shifts
    case 'shifts':
        require_once ROOT_PATH . '/Controller/ShiftsController.php';
        $shiftsController = new ShiftsController();
        $shiftsController->index();
        break;
    
    // Actions AJAX pour les shifts
    case 'shifts/details':
        require_once ROOT_PATH . '/Controller/ShiftsController.php';
        $shiftsController = new ShiftsController();
        $shiftsController->getDetails();
        break;
    
    case 'shifts/change-status':
        require_once ROOT_PATH . '/Controller/ShiftsController.php';
        $shiftsController = new ShiftsController();
        $shiftsController->changeStatus();
        break;
    
    case 'shifts/annuler':
        require_once ROOT_PATH . '/Controller/ShiftsController.php';
        $shiftsController = new ShiftsController();
        $shiftsController->annuler();
        break;
    
    case 'shifts/suggestions':
        require_once ROOT_PATH . '/Controller/ShiftsController.php';
        $shiftsController = new ShiftsController();
        $shiftsController->getSuggestions();
        break;

    // Partie alerter
    case 'alerter':
        require_once ROOT_PATH . '/Controller/AlertesController.php';
        $alertesController = new AlertesController();
        $alertesController->index();
        break;
    
    case 'alertes/getAlertes':
        require_once ROOT_PATH . '/Controller/AlertesController.php';
        $alertesController = new AlertesController();
        $alertesController->getAlertes();
        break;
    
    case 'alertes/traiter':
        require_once ROOT_PATH . '/Controller/AlertesController.php';
        $alertesController = new AlertesController();
        $alertesController->traiter();
        break;
    
    case 'alertes/marquerToutesLues':
        require_once ROOT_PATH . '/Controller/AlertesController.php';
        $alertesController = new AlertesController();
        $alertesController->marquerToutesLues();
        break;
    
    case 'alertes/supprimer':
        require_once ROOT_PATH . '/Controller/AlertesController.php';
        $alertesController = new AlertesController();
        $alertesController->supprimer();
        break;

    // Partie parametres
    case 'parametres':
        require_once ROOT_PATH . '/Controller/ParametresController.php';
        $parametresController = new ParametresController();
        $parametresController->index();
        break;
    
    // Actions AJAX pour les paramètres
    case 'parametres/creer-utilisateur':
        require_once ROOT_PATH . '/Controller/ParametresController.php';
        $parametresController = new ParametresController();
        $parametresController->creerUtilisateur();
        break;
    
    case 'parametres/modifier-utilisateur':
        require_once ROOT_PATH . '/Controller/ParametresController.php';
        $parametresController = new ParametresController();
        $parametresController->modifierUtilisateur();
        break;
    
    case 'parametres/changer-statut':
        require_once ROOT_PATH . '/Controller/ParametresController.php';
        $parametresController = new ParametresController();
        $parametresController->changerStatut();
        break;
    
    case 'parametres/supprimer-utilisateur':
        require_once ROOT_PATH . '/Controller/ParametresController.php';
        $parametresController = new ParametresController();
        $parametresController->supprimerUtilisateur();
        break;
    
    case 'parametres/get-utilisateur':
        require_once ROOT_PATH . '/Controller/ParametresController.php';
        $parametresController = new ParametresController();
        $parametresController->getUtilisateur();
        break;
    
    case 'utilisateurs':
        require VIEW_PATH . '/utilisateurs.php';
        break;

    // Partie Billetterie
    case 'billetterie':
        require_once ROOT_PATH . '/Controller/BilletsController.php';
        $billetsController = new BilletsController();
        $billetsController->index();
        break;
    
    case 'billets/annuler':
        require_once ROOT_PATH . '/Controller/BilletsController.php';
        $billetsController = new BilletsController();
        $billetsController->annuler();
        break;
    
    case 'billets/details':
        require_once ROOT_PATH . '/Controller/BilletsController.php';
        $billetsController = new BilletsController();
        $billetsController->getDetails();
        break;
    
    case 'billets/imprimer':
        require_once ROOT_PATH . '/Controller/BilletsController.php';
        $billetsController = new BilletsController();
        $billetsController->imprimer();
        break;
    
    case 'billets/bus-disponibles':
        require_once ROOT_PATH . '/Controller/BilletsController.php';
        $billetsController = new BilletsController();
        $billetsController->busDisponibles();
        break;
    
    case 'billets/creer':
        require_once ROOT_PATH . '/Controller/BilletsController.php';
        $billetsController = new BilletsController();
        $billetsController->creer();
        break;
    
    case 'vente-billets':
        require VIEW_PATH . '/vente-billets.php';
        break;
    case 'reservation':
        require VIEW_PATH . '/reservation.php';
        break;
    case 'historique':
        require VIEW_PATH . '/historique.php';
        break;
    case 'nouvelle-carte':
        require VIEW_PATH . '/nouvelle-carte.php';
        break;
    case 'cartes-prepayees':
        require VIEW_PATH . '/cartes-prepayees.php';
        break;
    case 'canaux-vente':
        require VIEW_PATH . '/canaux-vente.php';
        break;
    case 'gestion-guichets':
        require VIEW_PATH . '/gestion-guichets.php';
        break;
    case 'gestion-partenaires':
        require VIEW_PATH . '/gestion-partenaires.php';
        break;
    case 'clients-bt':
        require_once ROOT_PATH . '/Controller/ClientsController.php';
        $clientsController = new ClientsController();
        $clientsController->index();
        break;
    
    case 'clients/details':
        require_once ROOT_PATH . '/Controller/ClientsController.php';
        $clientsController = new ClientsController();
        $clientsController->getDetails();
        break;
    
    case 'clients/ajouter':
        require_once ROOT_PATH . '/Controller/ClientsController.php';
        $clientsController = new ClientsController();
        $clientsController->ajouter();
        break;
    
    case 'clients/modifier':
        require_once ROOT_PATH . '/Controller/ClientsController.php';
        $clientsController = new ClientsController();
        $clientsController->modifier();
        break;
    
    case 'clients/supprimer':
        require_once ROOT_PATH . '/Controller/ClientsController.php';
        $clientsController = new ClientsController();
        $clientsController->supprimer();
        break;
    case 'reclamations':
        require VIEW_PATH . '/reclamations.php';
        break;
    case 'statistiques-bt':
        require VIEW_PATH . '/statistiques-bt.php';
        break;
    case 'locations':
        require VIEW_PATH . '/locations.php';
        break;
    case 'historique-locations':
        require VIEW_PATH . '/historique-locations.php';
        break;

    // Partie Ressources Humaines
    case 'rh-dashboard':
        require VIEW_PATH . '/rh-dashboard.php';
        break;
    case 'personnel':
        require_once ROOT_PATH . '/Controller/PersonnelController.php';
        $personnelController = new PersonnelController();
        $personnelController->index();
        break;
    
    case 'nouveau-agent':
        require_once ROOT_PATH . '/Controller/PersonnelController.php';
        $personnelController = new PersonnelController();
        $personnelController->nouveau();
        break;
    
    case 'personnel/get':
        require_once ROOT_PATH . '/Controller/PersonnelController.php';
        $personnelController = new PersonnelController();
        $personnelController->getAgents();
        break;
    
    case 'personnel/details':
        require_once ROOT_PATH . '/Controller/PersonnelController.php';
        $personnelController = new PersonnelController();
        $personnelController->getAgent();
        break;
    
    case 'personnel/creer':
        require_once ROOT_PATH . '/Controller/PersonnelController.php';
        $personnelController = new PersonnelController();
        $personnelController->creer();
        break;
    
    case 'personnel/modifier':
        require_once ROOT_PATH . '/Controller/PersonnelController.php';
        $personnelController = new PersonnelController();
        $personnelController->modifier();
        break;
    
    case 'personnel/supprimer':
        require_once ROOT_PATH . '/Controller/PersonnelController.php';
        $personnelController = new PersonnelController();
        $personnelController->supprimer();
        break;
    case 'contrats':
        require VIEW_PATH . '/contrats.php';
        break;
    
    // Partie Historique des ventes
    case 'historique-ventes':
        require VIEW_PATH . '/historique-ventes.php';
        break;
    
    // Partie Historique (ventes + réservations)
    case 'historique':
        require VIEW_PATH . '/historique.php';
        break;

    // Page 404 par défaut
    default:
        http_response_code(404);
        if (APP_DEBUG) {
            echo '<h1>404 - Page non trouvée</h1>';
            echo '<p>Route demandée : <code>' . e($route) . '</code></p>';
        } else {
            echo '404 - Page non trouvée';
        }
        break;
}
