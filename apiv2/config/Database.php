<?php
/**
 * Database Connection Helper for API v2
 * Uses the existing configuration from SafariSmartMobily/Config
 */

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        // Load environment variables
        $envPath = __DIR__ . '/../../.env';
        $this->loadEnv($envPath);
        
        // Récupérer toutes les variables d'environnement
        $dbHost = getenv('DB_HOST') ?: 'localhost';
        $dbName = getenv('DB_NAME') ?: 'ngla4195_safari';
        $dbUser = getenv('DB_USER') ?: 'ngla4195_ngla4195';
        $dbPass = getenv('DB_PASS') ?: 'vlE+(*efYDZj';
        
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                $dbHost,
                $dbName
            );
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            $this->connection = new PDO($dsn, $dbUser, $dbPass, $options);
            
        } catch(PDOException $e) {
            // Afficher TOUS les détails possibles
            http_response_code(500);
            
            $errorDetails = [
                'success' => false,
                'message' => '❌ ERREUR DE CONNEXION À LA BASE DE DONNÉES',
                
                // Détails de l'exception
                'exception' => [
                    'message' => $e->getMessage(),
                    'code' => $e->getCode(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'previous' => $e->getPrevious() ? $e->getPrevious()->getMessage() : null
                ],
                
                // Informations de connexion utilisées
                'connection_attempt' => [
                    'dsn' => $dsn,
                    'host' => $dbHost,
                    'database' => $dbName,
                    'user' => $dbUser,
                    'password_length' => strlen($dbPass),
                    'password_empty' => empty($dbPass),
                    'charset' => 'utf8mb4'
                ],
                
                // Informations sur le fichier .env
                'env_file' => [
                    'path' => $envPath,
                    'exists' => file_exists($envPath),
                    'readable' => file_exists($envPath) ? is_readable($envPath) : false,
                    'size' => file_exists($envPath) ? filesize($envPath) : 0
                ],
                
                // Variables d'environnement
                'environment_variables' => [
                    'DB_HOST' => getenv('DB_HOST') ?: 'NOT SET (using default)',
                    'DB_NAME' => getenv('DB_NAME') ?: 'NOT SET (using default)',
                    'DB_USER' => getenv('DB_USER') ?: 'NOT SET (using default)',
                    'DB_PASS' => getenv('DB_PASS') !== false ? '***SET***' : 'NOT SET (using default)'
                ],
                
                // Informations système
                'system_info' => [
                    'php_version' => PHP_VERSION,
                    'pdo_drivers' => PDO::getAvailableDrivers(),
                    'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
                    'document_root' => $_SERVER['DOCUMENT_ROOT'] ?? 'Unknown',
                    'script_filename' => $_SERVER['SCRIPT_FILENAME'] ?? 'Unknown'
                ],
                
                // Extensions PHP
                'php_extensions' => [
                    'pdo' => extension_loaded('pdo'),
                    'pdo_mysql' => extension_loaded('pdo_mysql'),
                    'mysqli' => extension_loaded('mysqli'),
                    'mysqlnd' => extension_loaded('mysqlnd')
                ],
                
                // Suggestions de résolution
                'troubleshooting' => [
                    'check_mysql_running' => 'Vérifiez que MySQL/MariaDB est démarré',
                    'check_credentials' => 'Vérifiez les identifiants dans le fichier .env',
                    'check_database_exists' => 'Vérifiez que la base de données existe',
                    'check_user_permissions' => 'Vérifiez les permissions de l\'utilisateur MySQL',
                    'check_host' => 'Vérifiez que l\'hôte est correct (localhost ou 127.0.0.1)',
                    'check_port' => 'Vérifiez que MySQL écoute sur le port 3306'
                ],
                
                // Timestamp
                'timestamp' => date('Y-m-d H:i:s'),
                'timezone' => date_default_timezone_get()
            ];
            
            echo json_encode($errorDetails, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit;
        }
    }

    private function loadEnv($path) {
        if (!file_exists($path)) {
            return;
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                if (!array_key_exists($name, $_ENV)) {
                    putenv("$name=$value");
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
    }

    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            throw $e;
        }
    }

    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}
