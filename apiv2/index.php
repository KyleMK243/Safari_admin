<?php
/**
 * API v2 - Main Router
 * Simple PHP REST API without framework
 * Uses existing SafariSmartMobily database configuration
 */

// Enable CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Set content type
header('Content-Type: application/json; charset=utf-8');

// Error handling
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);

// Get request method and URI
$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Remove base path (adjust if needed)
// Support both local and production environments
$basePath = '/apiv2';
if (strpos($uri, '/SafariSmartMobily/apiv2') !== false) {
    $basePath = '/SafariSmartMobily/apiv2';
}
$uri = str_replace($basePath, '', $uri);
$uri = trim($uri, '/');

// Parse URI segments
$segments = explode('/', $uri);
$resource = $segments[0] ?? '';
$id = $segments[1] ?? null;

// Route handling
try {
    switch($resource) {
        case '':
            // API Info
            echo json_encode([
                'success' => true,
                'message' => 'Safari Smart Mobility API v2',
                'version' => '2.0.0',
                'endpoints' => [
                    '/bus' => 'Bus management',
                    '/utilisateurs' => 'User management',
                    '/trajets' => 'Route management',
                    '/billets' => 'Ticket management',
                    '/equipe_bord' => 'Crew management',
                    '/colis' => 'Package management',
                    '/shifts' => 'Shift management',
                    '/alertes' => 'Alert management'
                ],
                'documentation' => 'https://safari.hakika.events/apiv2/documentation.html',
                'test_page' => 'https://safari.hakika.events/apiv2/test.html'
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            break;

        case 'bus':
            require_once __DIR__ . '/routes/bus.php';
            $controller = new BusRoutes();
            
            switch($method) {
                case 'GET':
                    if ($id) {
                        $controller->getOne($id);
                    } else {
                        $controller->getAll();
                    }
                    break;
                case 'POST':
                    $controller->create();
                    break;
                case 'PUT':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for update']);
                        exit;
                    }
                    $controller->update($id);
                    break;
                case 'DELETE':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for delete']);
                        exit;
                    }
                    $controller->delete($id);
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case 'utilisateurs':
            require_once __DIR__ . '/routes/utilisateurs.php';
            $controller = new UtilisateursRoutes();
            
            switch($method) {
                case 'GET':
                    if ($id) {
                        $controller->getOne($id);
                    } else {
                        $controller->getAll();
                    }
                    break;
                case 'POST':
                    $controller->create();
                    break;
                case 'PUT':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for update']);
                        exit;
                    }
                    $controller->update($id);
                    break;
                case 'DELETE':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for delete']);
                        exit;
                    }
                    $controller->delete($id);
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case 'trajets':
            require_once __DIR__ . '/routes/trajets.php';
            $controller = new TrajetsRoutes();
            
            switch($method) {
                case 'GET':
                    if ($id) {
                        $controller->getOne($id);
                    } else {
                        $controller->getAll();
                    }
                    break;
                case 'POST':
                    $controller->create();
                    break;
                case 'PUT':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for update']);
                        exit;
                    }
                    $controller->update($id);
                    break;
                case 'DELETE':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for delete']);
                        exit;
                    }
                    $controller->delete($id);
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case 'billets':
            require_once __DIR__ . '/routes/billets.php';
            $controller = new BilletsRoutes();
            
            switch($method) {
                case 'GET':
                    if ($id) {
                        $controller->getOne($id);
                    } else {
                        $controller->getAll();
                    }
                    break;
                case 'POST':
                    $controller->create();
                    break;
                case 'PUT':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for update']);
                        exit;
                    }
                    $controller->update($id);
                    break;
                case 'DELETE':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for delete']);
                        exit;
                    }
                    $controller->delete($id);
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case 'equipe_bord':
            require_once __DIR__ . '/routes/equipe_bord.php';
            $controller = new EquipeBordRoutes();
            
            switch($method) {
                case 'GET':
                    if ($id) {
                        $controller->getOne($id);
                    } else {
                        $controller->getAll();
                    }
                    break;
                case 'POST':
                    $controller->create();
                    break;
                case 'PUT':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for update']);
                        exit;
                    }
                    $controller->update($id);
                    break;
                case 'DELETE':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for delete']);
                        exit;
                    }
                    $controller->delete($id);
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case 'colis':
            require_once __DIR__ . '/routes/colis.php';
            $controller = new ColisRoutes();
            
            switch($method) {
                case 'GET':
                    if ($id) {
                        $controller->getOne($id);
                    } else {
                        $controller->getAll();
                    }
                    break;
                case 'POST':
                    $controller->create();
                    break;
                case 'PUT':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for update']);
                        exit;
                    }
                    $controller->update($id);
                    break;
                case 'DELETE':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for delete']);
                        exit;
                    }
                    $controller->delete($id);
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case 'shifts':
            require_once __DIR__ . '/routes/shifts.php';
            $controller = new ShiftsRoutes();
            
            switch($method) {
                case 'GET':
                    if ($id) {
                        $controller->getOne($id);
                    } else {
                        $controller->getAll();
                    }
                    break;
                case 'POST':
                    $controller->create();
                    break;
                case 'PUT':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for update']);
                        exit;
                    }
                    $controller->update($id);
                    break;
                case 'DELETE':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for delete']);
                        exit;
                    }
                    $controller->delete($id);
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        case 'alertes':
            require_once __DIR__ . '/routes/alertes.php';
            $controller = new AlertesRoutes();
            
            switch($method) {
                case 'GET':
                    if ($id) {
                        $controller->getOne($id);
                    } else {
                        $controller->getAll();
                    }
                    break;
                case 'POST':
                    $controller->create();
                    break;
                case 'PUT':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for update']);
                        exit;
                    }
                    $controller->update($id);
                    break;
                case 'DELETE':
                    if (!$id) {
                        http_response_code(400);
                        echo json_encode(['success' => false, 'message' => 'ID required for delete']);
                        exit;
                    }
                    $controller->delete($id);
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            }
            break;

        default:
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => 'Endpoint not found',
                'requested_resource' => $resource,
                'requested_uri' => $_SERVER['REQUEST_URI'],
                'available_endpoints' => [
                    '/bus',
                    '/utilisateurs',
                    '/trajets',
                    '/billets',
                    '/equipe_bord',
                    '/colis',
                    '/shifts',
                    '/alertes'
                ],
                'help' => [
                    'base_url' => 'https://safari.hakika.events/apiv2/',
                    'example' => 'https://safari.hakika.events/apiv2/bus',
                    'documentation' => 'https://safari.hakika.events/apiv2/documentation.html',
                    'test_page' => 'https://safari.hakika.events/apiv2/test.html'
                ]
            ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Internal server error',
        'error' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}
