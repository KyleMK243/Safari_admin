-- Mettre à jour les routes des modules de paramètres pour chaque département

-- Planification
UPDATE `modules` SET `route` = 'parametres-pl' WHERE `code` = 'pl_parametres';

-- Billetterie (si le module existe)
UPDATE `modules` SET `route` = 'parametres-bt' WHERE `code` = 'bt_parametres';

-- RH (si le module existe)
UPDATE `modules` SET `route` = 'parametres-rh' WHERE `code` = 'rh_parametres';

-- Vérifier les routes mises à jour
SELECT id, code, nom, route, departement FROM `modules` WHERE code LIKE '%parametres%';
