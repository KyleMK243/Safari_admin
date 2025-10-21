<?php
/**
 * Classe de gestion des migrations de base de données
 * Permet d'ajouter/modifier des colonnes de manière sécurisée
 */

class DatabaseMigration {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Ajoute deux colonnes à une table de manière sécurisée
     * Vérifie si les colonnes existent déjà avant de les ajouter
     * 
     * @param string $tableName Nom de la table
     * @param array $column1 Configuration de la première colonne
     * @param array $column2 Configuration de la deuxième colonne
     * @return array Résultat de l'opération avec succès et messages
     * 
     * Exemple d'utilisation:
     * $migration = new DatabaseMigration();
     * $result = $migration->addTwoColumns(
     *     'bus',
     *     [
     *         'name' => 'couleur',
     *         'type' => 'VARCHAR(50)',
     *         'nullable' => true,
     *         'default' => null,
     *         'after' => 'modele',
     *         'comment' => 'Couleur du bus'
     *     ],
     *     [
     *         'name' => 'consommation',
     *         'type' => 'DECIMAL(5,2)',
     *         'nullable' => true,
     *         'default' => null,
     *         'after' => 'kilometrage',
     *         'comment' => 'Consommation moyenne en L/100km'
     *     ]
     * );
     */
    public function addTwoColumns($tableName, $column1, $column2) {
        $results = [
            'success' => true,
            'messages' => [],
            'errors' => []
        ];
        
        try {
            // Vérifier que la table existe
            if (!$this->tableExists($tableName)) {
                $results['success'] = false;
                $results['errors'][] = "La table '$tableName' n'existe pas.";
                return $results;
            }
            
            // Ajouter la première colonne
            $result1 = $this->addColumnIfNotExists($tableName, $column1);
            $results['messages'][] = $result1['message'];
            if (!$result1['success']) {
                $results['success'] = false;
                $results['errors'][] = $result1['error'];
            }
            
            // Ajouter la deuxième colonne
            $result2 = $this->addColumnIfNotExists($tableName, $column2);
            $results['messages'][] = $result2['message'];
            if (!$result2['success']) {
                $results['success'] = false;
                $results['errors'][] = $result2['error'];
            }
            
            return $results;
            
        } catch (PDOException $e) {
            $results['success'] = false;
            $results['errors'][] = "Erreur PDO : " . $e->getMessage();
            return $results;
        }
    }
    
    /**
     * Ajoute une colonne si elle n'existe pas déjà
     * 
     * @param string $tableName Nom de la table
     * @param array $column Configuration de la colonne
     * @return array Résultat de l'opération
     */
    private function addColumnIfNotExists($tableName, $column) {
        $columnName = $column['name'];
        
        try {
            // Vérifier si la colonne existe déjà
            if ($this->columnExists($tableName, $columnName)) {
                return [
                    'success' => true,
                    'message' => "✓ La colonne '$columnName' existe déjà dans la table '$tableName'.",
                    'error' => null
                ];
            }
            
            // Construire la requête ALTER TABLE
            $sql = "ALTER TABLE `$tableName` ADD COLUMN `$columnName` " . $column['type'];
            
            // Ajouter NULL ou NOT NULL
            if (isset($column['nullable']) && $column['nullable'] === false) {
                $sql .= " NOT NULL";
            } else {
                $sql .= " NULL";
            }
            
            // Ajouter DEFAULT si spécifié
            if (isset($column['default'])) {
                if ($column['default'] === null) {
                    $sql .= " DEFAULT NULL";
                } else {
                    $sql .= " DEFAULT '" . $this->db->quote($column['default']) . "'";
                }
            }
            
            // Ajouter COMMENT si spécifié
            if (isset($column['comment'])) {
                $sql .= " COMMENT '" . addslashes($column['comment']) . "'";
            }
            
            // Ajouter AFTER si spécifié
            if (isset($column['after'])) {
                $sql .= " AFTER `" . $column['after'] . "`";
            }
            
            // Exécuter la requête
            $this->db->exec($sql);
            
            return [
                'success' => true,
                'message' => "✓ Colonne '$columnName' ajoutée avec succès à la table '$tableName'.",
                'error' => null
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => "✗ Échec de l'ajout de la colonne '$columnName'.",
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Vérifie si une table existe dans la base de données
     * 
     * @param string $tableName Nom de la table
     * @return bool True si la table existe
     */
    private function tableExists($tableName) {
        $sql = "SELECT COUNT(*) 
                FROM INFORMATION_SCHEMA.TABLES 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = :tableName";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tableName' => $tableName]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Vérifie si une colonne existe dans une table
     * 
     * @param string $tableName Nom de la table
     * @param string $columnName Nom de la colonne
     * @return bool True si la colonne existe
     */
    private function columnExists($tableName, $columnName) {
        $sql = "SELECT COUNT(*) 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = :tableName 
                AND COLUMN_NAME = :columnName";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tableName' => $tableName,
            'columnName' => $columnName
        ]);
        
        return $stmt->fetchColumn() > 0;
    }
    
    /**
     * Ajoute un index unique sur une colonne
     * 
     * @param string $tableName Nom de la table
     * @param string $columnName Nom de la colonne
     * @param string $indexName Nom de l'index (optionnel)
     * @return array Résultat de l'opération
     */
    public function addUniqueIndex($tableName, $columnName, $indexName = null) {
        if ($indexName === null) {
            $indexName = "idx_{$tableName}_{$columnName}";
        }
        
        try {
            // Vérifier si l'index existe déjà
            if ($this->indexExists($tableName, $indexName)) {
                return [
                    'success' => true,
                    'message' => "✓ L'index '$indexName' existe déjà."
                ];
            }
            
            $sql = "ALTER TABLE `$tableName` ADD UNIQUE INDEX `$indexName` (`$columnName`)";
            $this->db->exec($sql);
            
            return [
                'success' => true,
                'message' => "✓ Index unique '$indexName' créé avec succès."
            ];
            
        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => "✗ Échec de la création de l'index.",
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Vérifie si un index existe
     * 
     * @param string $tableName Nom de la table
     * @param string $indexName Nom de l'index
     * @return bool True si l'index existe
     */
    private function indexExists($tableName, $indexName) {
        $sql = "SELECT COUNT(*) 
                FROM INFORMATION_SCHEMA.STATISTICS 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = :tableName 
                AND INDEX_NAME = :indexName";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'tableName' => $tableName,
            'indexName' => $indexName
        ]);
        
        return $stmt->fetchColumn() > 0;
    }
}
