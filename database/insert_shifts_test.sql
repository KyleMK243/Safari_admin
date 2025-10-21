-- Insertion de données de test pour les shifts (planification)

-- Supprimer les anciennes données de test
DELETE FROM shifts WHERE id > 1;

-- Insérer des shifts de test pour aujourd'hui et les prochains jours
INSERT INTO `shifts` (`bus_numero`, `date_prevue`, `heure_debut`, `heure_fin`, `chauffeur_id`, `controleur_id`, `receveur_id`, `trajet_id`, `statut`, `notes`) VALUES
-- Shifts d'aujourd'hui
('012', CURDATE(), '06:00:00', '14:00:00', 1, 2, 3, 1, 'actif', 'Shift matinal - En cours'),
('156', CURDATE(), '06:30:00', '14:30:00', 4, 5, 6, 2, 'actif', 'Shift matinal - Route Kikwit'),
('421', CURDATE(), '14:00:00', '22:00:00', 7, 8, 9, 1, 'planifie', 'Shift après-midi'),
('789', CURDATE(), '14:30:00', '22:30:00', 10, 11, 12, 3, 'planifie', 'Shift après-midi - Route Lubumbashi'),

-- Shifts de demain
('012', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '06:00:00', '14:00:00', 4, 5, 6, 1, 'planifie', 'Shift matinal planifié'),
('156', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '06:30:00', '14:30:00', 7, 8, 9, 2, 'planifie', 'Shift matinal - Route Kikwit'),
('421', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:00:00', '22:00:00', 1, 2, 3, 1, 'planifie', 'Shift après-midi'),
('789', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '14:30:00', '22:30:00', 10, 11, 12, 3, 'planifie', 'Shift après-midi - Route Lubumbashi'),
('234', DATE_ADD(CURDATE(), INTERVAL 1 DAY), '07:00:00', '15:00:00', 13, 14, 15, 4, 'planifie', 'Shift matinal - Route Kananga'),

-- Shifts d'après-demain
('012', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '06:00:00', '14:00:00', 7, 8, 9, 1, 'planifie', 'Shift matinal'),
('156', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '06:30:00', '14:30:00', 1, 2, 3, 2, 'planifie', 'Shift matinal - Route Kikwit'),
('421', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', '22:00:00', 4, 5, 6, 1, 'planifie', 'Shift après-midi'),
('789', DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:30:00', '22:30:00', 10, 11, 12, 3, 'planifie', 'Shift après-midi - Route Lubumbashi'),

-- Shifts d'hier (terminés)
('012', DATE_SUB(CURDATE(), INTERVAL 1 DAY), '06:00:00', '14:00:00', 1, 2, 3, 1, 'termine', 'Shift terminé avec succès'),
('156', DATE_SUB(CURDATE(), INTERVAL 1 DAY), '06:30:00', '14:30:00', 4, 5, 6, 2, 'termine', 'Shift terminé - Route Kikwit'),
('421', DATE_SUB(CURDATE(), INTERVAL 1 DAY), '14:00:00', '22:00:00', 7, 8, 9, 1, 'termine', 'Shift terminé'),
('789', DATE_SUB(CURDATE(), INTERVAL 1 DAY), '14:30:00', '22:30:00', 10, 11, 12, 3, 'annule', 'Annulé - Panne mécanique du bus'),

-- Shifts il y a 2 jours (terminés)
('012', DATE_SUB(CURDATE(), INTERVAL 2 DAY), '06:00:00', '14:00:00', 4, 5, 6, 1, 'termine', 'Shift terminé'),
('156', DATE_SUB(CURDATE(), INTERVAL 2 DAY), '06:30:00', '14:30:00', 7, 8, 9, 2, 'termine', 'Shift terminé - Route Kikwit'),
('421', DATE_SUB(CURDATE(), INTERVAL 2 DAY), '14:00:00', '22:00:00', 1, 2, 3, 1, 'termine', 'Shift terminé'),
('234', DATE_SUB(CURDATE(), INTERVAL 2 DAY), '07:00:00', '15:00:00', 13, 14, 15, 4, 'termine', 'Shift terminé - Route Kananga'),

-- Shifts dans 3 jours
('012', DATE_ADD(CURDATE(), INTERVAL 3 DAY), '06:00:00', '14:00:00', 1, 2, 3, 1, 'planifie', 'Shift matinal'),
('156', DATE_ADD(CURDATE(), INTERVAL 3 DAY), '06:30:00', '14:30:00', 4, 5, 6, 2, 'planifie', 'Shift matinal - Route Kikwit'),
('421', DATE_ADD(CURDATE(), INTERVAL 3 DAY), '14:00:00', '22:00:00', 7, 8, 9, 1, 'planifie', 'Shift après-midi'),
('789', DATE_ADD(CURDATE(), INTERVAL 3 DAY), '14:30:00', '22:30:00', 10, 11, 12, 3, 'planifie', 'Shift après-midi - Route Lubumbashi'),
('234', DATE_ADD(CURDATE(), INTERVAL 3 DAY), '07:00:00', '15:00:00', 13, 14, 15, 4, 'planifie', 'Shift matinal - Route Kananga'),

-- Shifts dans 4 jours
('012', DATE_ADD(CURDATE(), INTERVAL 4 DAY), '06:00:00', '14:00:00', 4, 5, 6, 1, 'planifie', 'Shift matinal'),
('156', DATE_ADD(CURDATE(), INTERVAL 4 DAY), '06:30:00', '14:30:00', 7, 8, 9, 2, 'planifie', 'Shift matinal - Route Kikwit'),
('421', DATE_ADD(CURDATE(), INTERVAL 4 DAY), '14:00:00', '22:00:00', 1, 2, 3, 1, 'planifie', 'Shift après-midi'),

-- Shifts dans 5 jours
('012', DATE_ADD(CURDATE(), INTERVAL 5 DAY), '06:00:00', '14:00:00', 7, 8, 9, 1, 'planifie', 'Shift matinal'),
('156', DATE_ADD(CURDATE(), INTERVAL 5 DAY), '06:30:00', '14:30:00', 1, 2, 3, 2, 'planifie', 'Shift matinal - Route Kikwit'),
('421', DATE_ADD(CURDATE(), INTERVAL 5 DAY), '14:00:00', '22:00:00', 4, 5, 6, 1, 'planifie', 'Shift après-midi'),
('789', DATE_ADD(CURDATE(), INTERVAL 5 DAY), '14:30:00', '22:30:00', 10, 11, 12, 3, 'planifie', 'Shift après-midi - Route Lubumbashi');

-- Vérifier les données insérées
SELECT 
    s.id,
    s.bus_numero,
    s.date_prevue,
    CONCAT(s.heure_debut, ' - ', s.heure_fin) as horaire,
    s.statut,
    CONCAT(c.nom, ' ', c.prenom) as chauffeur,
    CONCAT(co.nom, ' ', co.prenom) as controleur,
    CONCAT(r.nom, ' ', r.prenom) as receveur
FROM shifts s
LEFT JOIN equipe_bord c ON s.chauffeur_id = c.id
LEFT JOIN equipe_bord co ON s.controleur_id = co.id
LEFT JOIN equipe_bord r ON s.receveur_id = r.id
ORDER BY s.date_prevue DESC, s.heure_debut ASC
LIMIT 20;
