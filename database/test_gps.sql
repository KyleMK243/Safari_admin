-- ========================================
-- Test des colonnes GPS et des données
-- ========================================

-- 1. Vérifier que les colonnes existent
SHOW COLUMNS FROM `bus` LIKE 'latitude';
SHOW COLUMNS FROM `bus` LIKE 'longitude';

-- 2. Compter les bus avec GPS
SELECT 
    COUNT(*) as total_bus,
    SUM(CASE WHEN latitude IS NOT NULL THEN 1 ELSE 0 END) as avec_gps,
    SUM(CASE WHEN latitude IS NULL THEN 1 ELSE 0 END) as sans_gps
FROM bus;

-- 3. Voir les bus actifs avec leurs positions
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
ORDER BY numero;

-- 4. Si aucun bus n'a de GPS, exécuter ceci :
-- UPDATE `bus` SET 
--   `latitude` = -11.6667 + (RAND() * 0.1 - 0.05),
--   `longitude` = 27.4833 + (RAND() * 0.1 - 0.05)
-- WHERE `statut` IN ('actif', 'maintenance', 'panne');
