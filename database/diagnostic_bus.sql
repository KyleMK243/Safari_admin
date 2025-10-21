-- ========================================
-- DIAGNOSTIC : Vérifier pourquoi les bus ne s'affichent pas
-- ========================================

-- 1. Vérifier que les colonnes GPS existent
SHOW COLUMNS FROM `bus` LIKE 'latitude';
SHOW COLUMNS FROM `bus` LIKE 'longitude';

-- 2. Compter les bus par statut
SELECT 
    statut,
    COUNT(*) as nombre
FROM bus 
GROUP BY statut;

-- 3. Vérifier les bus avec GPS
SELECT 
    COUNT(*) as total_bus,
    SUM(CASE WHEN latitude IS NOT NULL THEN 1 ELSE 0 END) as avec_gps,
    SUM(CASE WHEN latitude IS NULL THEN 1 ELSE 0 END) as sans_gps
FROM bus 
WHERE statut IN ('actif', 'maintenance', 'panne');

-- 4. Voir les 5 premiers bus avec leurs coordonnées
SELECT 
    numero, 
    statut, 
    latitude, 
    longitude,
    CASE 
        WHEN latitude IS NULL THEN '❌ Pas de GPS'
        ELSE '✅ GPS OK'
    END as gps_status
FROM bus 
WHERE statut IN ('actif', 'maintenance', 'panne')
ORDER BY numero
LIMIT 10;

-- ========================================
-- SI AUCUN BUS N'A DE GPS, EXÉCUTE CECI :
-- ========================================

-- Ajouter les colonnes si elles n'existent pas
-- ALTER TABLE `bus` 
-- ADD COLUMN `latitude` DECIMAL(10, 8) NULL DEFAULT NULL AFTER `derniere_activite`,
-- ADD COLUMN `longitude` DECIMAL(11, 8) NULL DEFAULT NULL AFTER `latitude`;

-- Ajouter des positions GPS à Kinshasa
-- UPDATE `bus` SET 
--   `latitude` = -4.3276 + (RAND() * 0.1 - 0.05),
--   `longitude` = 15.3136 + (RAND() * 0.1 - 0.05)
-- WHERE `statut` IN ('actif', 'maintenance', 'panne');

-- Vérifier après l'UPDATE
-- SELECT numero, statut, latitude, longitude 
-- FROM bus 
-- WHERE statut IN ('actif', 'maintenance', 'panne')
-- LIMIT 5;
