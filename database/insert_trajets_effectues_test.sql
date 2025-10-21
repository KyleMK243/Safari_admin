-- Insertion de données de test pour les trajets effectués (pour BI)

-- Supprimer les anciennes données de test
DELETE FROM trajets_effectues WHERE id > 0;

-- Insérer des trajets effectués pour les 30 derniers jours
-- Cela permettra d'avoir des données pour les graphiques BI

-- Trajets d'aujourd'hui
INSERT INTO `trajets_effectues` (`trajet_id`, `bus_id`, `shift_id`, `date_depart`, `heure_depart_reelle`, `heure_arrivee_reelle`, `nombre_passagers`, `statut`) VALUES
(1, 1, NULL, CURDATE(), '06:00:00', '10:30:00', 45, 'termine'),
(2, 2, NULL, CURDATE(), '06:30:00', '11:00:00', 38, 'termine'),
(1, 3, NULL, CURDATE(), '14:00:00', '18:30:00', 42, 'en_cours'),
(3, 4, NULL, CURDATE(), '14:30:00', NULL, 35, 'en_cours');

-- Trajets d'hier
INSERT INTO `trajets_effectues` (`trajet_id`, `bus_id`, `shift_id`, `date_depart`, `heure_depart_reelle`, `heure_arrivee_reelle`, `nombre_passagers`, `statut`) VALUES
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '06:00:00', '10:30:00', 48, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '06:30:00', '11:00:00', 40, 'termine'),
(1, 3, NULL, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '14:00:00', '18:30:00', 44, 'termine'),
(3, 4, NULL, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '14:30:00', '19:00:00', 37, 'termine'),
(4, 5, NULL, DATE_SUB(CURDATE(), INTERVAL 1 DAY), '07:00:00', '12:00:00', 30, 'termine');

-- Trajets il y a 2 jours
INSERT INTO `trajets_effectues` (`trajet_id`, `bus_id`, `shift_id`, `date_depart`, `heure_depart_reelle`, `heure_arrivee_reelle`, `nombre_passagers`, `statut`) VALUES
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '06:00:00', '10:30:00', 46, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '06:30:00', '11:00:00', 39, 'termine'),
(1, 3, NULL, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '14:00:00', '18:30:00', 43, 'termine'),
(3, 4, NULL, DATE_SUB(CURDATE(), INTERVAL 2 DAY), '14:30:00', '19:00:00', 36, 'termine');

-- Trajets il y a 3 jours
INSERT INTO `trajets_effectues` (`trajet_id`, `bus_id`, `shift_id`, `date_depart`, `heure_depart_reelle`, `heure_arrivee_reelle`, `nombre_passagers`, `statut`) VALUES
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '06:00:00', '10:30:00', 47, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '06:30:00', '11:00:00', 41, 'termine'),
(1, 3, NULL, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '14:00:00', '18:30:00', 45, 'termine'),
(3, 4, NULL, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '14:30:00', '19:00:00', 38, 'termine'),
(4, 5, NULL, DATE_SUB(CURDATE(), INTERVAL 3 DAY), '07:00:00', '12:00:00', 32, 'termine');

-- Trajets pour les 7 derniers jours (en boucle)
INSERT INTO `trajets_effectues` (`trajet_id`, `bus_id`, `shift_id`, `date_depart`, `heure_depart_reelle`, `heure_arrivee_reelle`, `nombre_passagers`, `statut`) VALUES
-- Jour -4
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 4 DAY), '06:00:00', '10:30:00', 44, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 4 DAY), '06:30:00', '11:00:00', 37, 'termine'),
(1, 3, NULL, DATE_SUB(CURDATE(), INTERVAL 4 DAY), '14:00:00', '18:30:00', 41, 'termine'),
-- Jour -5
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 5 DAY), '06:00:00', '10:30:00', 45, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 5 DAY), '06:30:00', '11:00:00', 38, 'termine'),
(3, 4, NULL, DATE_SUB(CURDATE(), INTERVAL 5 DAY), '14:30:00', '19:00:00', 35, 'termine'),
-- Jour -6
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 6 DAY), '06:00:00', '10:30:00', 46, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 6 DAY), '06:30:00', '11:00:00', 39, 'termine'),
(1, 3, NULL, DATE_SUB(CURDATE(), INTERVAL 6 DAY), '14:00:00', '18:30:00', 42, 'termine'),
(4, 5, NULL, DATE_SUB(CURDATE(), INTERVAL 6 DAY), '07:00:00', '12:00:00', 31, 'termine'),
-- Jour -7
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 7 DAY), '06:00:00', '10:30:00', 47, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 7 DAY), '06:30:00', '11:00:00', 40, 'termine'),
(3, 4, NULL, DATE_SUB(CURDATE(), INTERVAL 7 DAY), '14:30:00', '19:00:00', 36, 'termine');

-- Trajets pour les 30 derniers jours (échantillon)
INSERT INTO `trajets_effectues` (`trajet_id`, `bus_id`, `shift_id`, `date_depart`, `heure_depart_reelle`, `heure_arrivee_reelle`, `nombre_passagers`, `statut`) VALUES
-- Semaine -2
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 10 DAY), '06:00:00', '10:30:00', 48, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 10 DAY), '06:30:00', '11:00:00', 41, 'termine'),
(1, 3, NULL, DATE_SUB(CURDATE(), INTERVAL 11 DAY), '14:00:00', '18:30:00', 43, 'termine'),
(3, 4, NULL, DATE_SUB(CURDATE(), INTERVAL 12 DAY), '14:30:00', '19:00:00', 37, 'termine'),
(4, 5, NULL, DATE_SUB(CURDATE(), INTERVAL 13 DAY), '07:00:00', '12:00:00', 33, 'termine'),
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 14 DAY), '06:00:00', '10:30:00', 46, 'termine'),
-- Semaine -3
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 15 DAY), '06:30:00', '11:00:00', 39, 'termine'),
(1, 3, NULL, DATE_SUB(CURDATE(), INTERVAL 16 DAY), '14:00:00', '18:30:00', 44, 'termine'),
(3, 4, NULL, DATE_SUB(CURDATE(), INTERVAL 17 DAY), '14:30:00', '19:00:00', 38, 'termine'),
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 18 DAY), '06:00:00', '10:30:00', 45, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 19 DAY), '06:30:00', '11:00:00', 40, 'termine'),
(4, 5, NULL, DATE_SUB(CURDATE(), INTERVAL 20 DAY), '07:00:00', '12:00:00', 34, 'termine'),
-- Semaine -4
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 21 DAY), '06:00:00', '10:30:00', 47, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 22 DAY), '06:30:00', '11:00:00', 42, 'termine'),
(1, 3, NULL, DATE_SUB(CURDATE(), INTERVAL 23 DAY), '14:00:00', '18:30:00', 41, 'termine'),
(3, 4, NULL, DATE_SUB(CURDATE(), INTERVAL 24 DAY), '14:30:00', '19:00:00', 36, 'termine'),
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 25 DAY), '06:00:00', '10:30:00', 48, 'termine'),
(2, 2, NULL, DATE_SUB(CURDATE(), INTERVAL 26 DAY), '06:30:00', '11:00:00', 43, 'termine'),
(4, 5, NULL, DATE_SUB(CURDATE(), INTERVAL 27 DAY), '07:00:00', '12:00:00', 35, 'termine'),
(1, 3, NULL, DATE_SUB(CURDATE(), INTERVAL 28 DAY), '14:00:00', '18:30:00', 42, 'termine'),
(3, 4, NULL, DATE_SUB(CURDATE(), INTERVAL 29 DAY), '14:30:00', '19:00:00', 39, 'termine'),
(1, 1, NULL, DATE_SUB(CURDATE(), INTERVAL 30 DAY), '06:00:00', '10:30:00', 46, 'termine');

-- Vérifier les données insérées
SELECT 
    DATE(date_depart) as date,
    COUNT(*) as total_trajets,
    SUM(nombre_passagers) as total_passagers
FROM trajets_effectues
WHERE date_depart >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY DATE(date_depart)
ORDER BY date DESC;
