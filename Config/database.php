<?php
/**
 * Classe de gestion de la base de données
 * Utilise le pattern Singleton et PDO avec les variables d'environnement
 */

class Database {
    private static $instance = null;
    private $connection;

    private function __construct() {
        try {
            // Construire le DSN avec les variables d'environnement
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=utf8mb4",
                getenv('DB_HOST') ?: 'localhost',
                getenv('DB_NAME') ?: 'safari_smart_mobility'
            );
            
            // Options PDO sécurisées
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];
            
            // Connexion PDO
            $this->connection = new PDO(
                $dsn,
                getenv('DB_USER') ?: 'root',
                getenv('DB_PASS') ?: '',
                $options
            );
            
        } catch(PDOException $e) {
            // En développement : afficher l'erreur détaillée
            if (defined('APP_ENV') && APP_ENV === 'development') {
                die('❌ Erreur de connexion à la base de données : ' . $e->getMessage());
            }
            
            // En production : logger l'erreur et afficher un message générique
            error_log('Database connection error: ' . $e->getMessage());
            die('Une erreur est survenue. Veuillez réessayer plus tard.');
        }
    }

    /**
     * Obtenir l'instance unique de la base de données
     * 
     * @return Database Instance de la classe
     */
    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Obtenir la connexion PDO
     * 
     * @return PDO Connexion PDO
     */
    public function getConnection() {
        return $this->connection;
    }
    
    /**
     * Exécuter une requête préparée (helper)
     * 
     * @param string $sql Requête SQL
     * @param array $params Paramètres de la requête
     * @return PDOStatement|false Statement ou false en cas d'erreur
     */
    public function query($sql, $params = []) {
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch(PDOException $e) {
            // En développement : afficher l'erreur
            if (defined('APP_ENV') && APP_ENV === 'development') {
                throw $e;
            }
            
            // En production : logger l'erreur
            error_log('Query error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Obtenir le dernier ID inséré
     * 
     * @return string Dernier ID
     */
    public function lastInsertId() {
        return $this->connection->lastInsertId();
    }
}