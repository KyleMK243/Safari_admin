-- Script de correction des tarifs pour correspondre aux vrais trajets
-- Date: 2025-10-16
-- Description: Correction des noms et prix des tarifs pour correspondre aux trajets existants

USE safari_smart_mobility;

-- Mise à jour des tarifs pour le trajet 1 (Gare Centrale - Lemba)
UPDATE `tarifs` SET 
    `nom` = 'Tarif Normal - Gare Centrale - Lemba',
    `prix` = 3000.00
WHERE `id` = 1;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Étudiant - Gare Centrale - Lemba',
    `prix` = 2550.00
WHERE `id` = 2;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Senior - Gare Centrale - Lemba',
    `prix` = 2700.00
WHERE `id` = 3;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Enfant - Gare Centrale - Lemba',
    `prix` = 2400.00
WHERE `id` = 4;

-- Mise à jour des tarifs pour le trajet 12 (Gare Centrale - Ndjili)
UPDATE `tarifs` SET 
    `nom` = 'Tarif Normal - Gare Centrale - Ndjili',
    `prix` = 4500.00
WHERE `id` = 5;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Étudiant - Gare Centrale - Ndjili',
    `prix` = 3825.00
WHERE `id` = 6;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Senior - Gare Centrale - Ndjili',
    `prix` = 4050.00
WHERE `id` = 7;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Enfant - Gare Centrale - Ndjili',
    `prix` = 3600.00
WHERE `id` = 8;

-- Mise à jour des tarifs pour le trajet 13 (Gare Centrale - Kimbanseke)
UPDATE `tarifs` SET 
    `nom` = 'Tarif Normal - Gare Centrale - Kimbanseke',
    `prix` = 5000.00
WHERE `id` = 9;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Étudiant - Gare Centrale - Kimbanseke',
    `prix` = 4250.00
WHERE `id` = 10;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Senior - Gare Centrale - Kimbanseke',
    `prix` = 4500.00
WHERE `id` = 11;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Enfant - Gare Centrale - Kimbanseke',
    `prix` = 4000.00
WHERE `id` = 12;

-- Mise à jour des tarifs pour le trajet 14 (Gare Centrale - Selembao)
UPDATE `tarifs` SET 
    `nom` = 'Tarif Normal - Gare Centrale - Selembao',
    `prix` = 2750.00
WHERE `id` = 13;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Étudiant - Gare Centrale - Selembao',
    `prix` = 2337.50
WHERE `id` = 14;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Senior - Gare Centrale - Selembao',
    `prix` = 2475.00
WHERE `id` = 15;

UPDATE `tarifs` SET 
    `nom` = 'Tarif Enfant - Gare Centrale - Selembao',
    `prix` = 2200.00
WHERE `id` = 16;

-- Vérification des données corrigées
SELECT 
    t.id,
    t.nom AS tarif_nom,
    t.trajet_id,
    tr.nom AS trajet_nom,
    t.type_tarif,
    t.prix,
    t.devise,
    t.statut
FROM tarifs t
LEFT JOIN trajets tr ON t.trajet_id = tr.id
ORDER BY t.trajet_id, t.type_tarif;
