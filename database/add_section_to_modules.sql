-- Ajouter la colonne section à la table modules
ALTER TABLE `modules` ADD COLUMN `section` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL AFTER `departement`;

-- Mettre à jour les sections pour le département Planification (PL)
-- Section: CONCEPTION
UPDATE `modules` SET `section` = 'CONCEPTION' WHERE `code` IN ('pl_gestion_bus', 'pl_trajets');

-- Section: PLANIFICATION
UPDATE `modules` SET `section` = 'PLANIFICATION' WHERE `code` IN ('pl_equipe', 'pl_gestion_services');

-- Section: STATISTIQUES
UPDATE `modules` SET `section` = 'STATISTIQUES' WHERE `code` IN ('pl_alertes', 'pl_business_intelligence');

-- Le dashboard reste sans section (en haut)
UPDATE `modules` SET `section` = NULL WHERE `code` = 'pl_dashboard';
