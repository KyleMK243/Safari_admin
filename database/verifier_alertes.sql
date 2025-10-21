-- Vérifier si la table alertes existe
SHOW TABLES LIKE 'alertes';

-- Vérifier la structure de la table alertes
DESCRIBE alertes;

-- Compter le nombre d'alertes
SELECT COUNT(*) as total_alertes FROM alertes;

-- Afficher les 5 premières alertes
SELECT * FROM alertes LIMIT 5;
