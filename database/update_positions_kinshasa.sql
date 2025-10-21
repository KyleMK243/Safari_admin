-- ========================================
-- Mettre à jour les positions GPS vers Kinshasa
-- ========================================

-- Les bus ont actuellement des coordonnées de Lubumbashi (-11.xxx, 27.xxx)
-- On les déplace vers Kinshasa (-4.xxx, 15.xxx)

UPDATE `bus` SET 
  `latitude` = -4.3276 + (RAND() * 0.1 - 0.05),
  `longitude` = 15.3136 + (RAND() * 0.1 - 0.05)
WHERE `statut` IN ('actif', 'maintenance', 'panne');

-- Vérifier les nouvelles positions
SELECT 
    numero, 
    statut, 
    ROUND(latitude, 4) as lat, 
    ROUND(longitude, 4) as lng
FROM bus 
WHERE statut IN ('actif', 'maintenance', 'panne')
ORDER BY numero
LIMIT 10;

-- Les coordonnées doivent maintenant être autour de :
-- Latitude:  -4.32 (Kinshasa)
-- Longitude: 15.31 (Kinshasa)
