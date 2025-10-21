-- ========================================
-- Script de migration : Renommer la colonne latitude_deprte en latitude_depart
-- Date: 2025-10-16
-- Description: Correction de la faute de frappe dans le nom de la colonne
-- ========================================

USE safari_smart_mobility;

-- Vérifier si la colonne existe avant de la renommer
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'safari_smart_mobility'
  AND TABLE_NAME = 'trajets'
  AND COLUMN_NAME IN ('latitude_deprte', 'latitude_depart');

-- Renommer la colonne latitude_deprte en latitude_depart
ALTER TABLE `trajets` 
CHANGE COLUMN `latitude_deprte` `latitude_depart` DECIMAL(10,8) NULL DEFAULT NULL;

-- Vérifier que la modification a été appliquée
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = 'safari_smart_mobility'
  AND TABLE_NAME = 'trajets'
  AND COLUMN_NAME = 'latitude_depart';

-- Afficher quelques lignes pour vérifier les données
SELECT 
    id,
    code,
    nom,
    latitude_depart,
    longitude_depart,
    latitude_arrivee,
    longitude_arrivee
FROM trajets
LIMIT 5;

-- Message de confirmation
SELECT 'Migration terminée avec succès ! La colonne a été renommée de latitude_deprte à latitude_depart' AS message;
