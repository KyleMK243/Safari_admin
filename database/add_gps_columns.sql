-- ========================================
-- Ajouter les colonnes GPS à la table bus
-- ========================================

-- Ajouter les colonnes latitude et longitude
ALTER TABLE `bus` 
ADD COLUMN `latitude` DECIMAL(10, 8) NULL DEFAULT NULL AFTER `derniere_activite`,
ADD COLUMN `longitude` DECIMAL(11, 8) NULL DEFAULT NULL AFTER `latitude`;

-- ========================================
-- Ajouter des positions de test (Kinshasa)
-- ========================================

-- Centre de Kinshasa: -4.3276, 15.3136
-- On génère des positions aléatoires dans un rayon de ~10km

UPDATE `bus` SET 
  `latitude` = -4.3276 + (RAND() * 0.1 - 0.05),
  `longitude` = 15.3136 + (RAND() * 0.1 - 0.05)
WHERE `statut` IN ('actif', 'maintenance', 'panne');

-- Les bus inactifs n'ont pas de position
UPDATE `bus` SET 
  `latitude` = NULL,
  `longitude` = NULL
WHERE `statut` = 'inactif';

-- ========================================
-- Vérification
-- ========================================

-- Afficher les bus avec leurs positions
SELECT 
  numero, 
  immatriculation, 
  statut, 
  latitude, 
  longitude 
FROM bus 
ORDER BY numero;
