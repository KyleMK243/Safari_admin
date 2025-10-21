-- ========================================
-- Données de test pour la table trajets
-- Avec coordonnées GPS réelles de KINSHASA
-- ========================================

-- Vider la table trajets
TRUNCATE TABLE `trajets`;

-- Insérer des trajets avec coordonnées GPS de Kinshasa
INSERT INTO `trajets` (`id`, `code`, `nom`, `distance_totale`, `duree_estimee`, `statut`, `latitude_depart`, `longitude_depart`, `latitude_arrivee`, `longitude_arrivee`) VALUES
-- Trajet 1 : Gare Centrale → Lemba
(1, 'L1', 'Gare Centrale - Lemba', 12.00, '45', 'actif', 
 -4.3276, 15.3136,  -- Départ: Gare Centrale Kinshasa
 -4.3800, 15.3500), -- Arrivée: Lemba

-- Trajet 2 : Gare Centrale → Matete
(2, 'L2', 'Gare Centrale - Matete', 15.00, '50', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3650, 15.2800), -- Arrivée: Matete

-- Trajet 3 : Gare Centrale → Kintambo
(3, 'L3', 'Gare Centrale - Kintambo', 5.00, '25', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3100, 15.2900), -- Arrivée: Kintambo

-- Trajet 4 : Victoire → Lemba
(4, 'L4', 'Victoire - Lemba', 8.00, '35', 'actif',
 -4.3200, 15.3200,  -- Départ: Victoire
 -4.3800, 15.3500), -- Arrivée: Lemba

-- Trajet 5 : Gare Centrale → Kalamu
(5, 'L5', 'Gare Centrale - Kalamu', 7.00, '30', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3500, 15.3300), -- Arrivée: Kalamu

-- Trajet 6 : Gare Centrale → Ngaliema
(6, 'L6', 'Gare Centrale - Ngaliema', 10.00, '40', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.2900, 15.2600), -- Arrivée: Ngaliema

-- Trajet 7 : Gare Centrale → Bandalungwa
(7, 'L7', 'Gare Centrale - Bandalungwa', 9.00, '38', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3600, 15.2900), -- Arrivée: Bandalungwa

-- Trajet 8 : Gare Centrale → Kasa-Vubu
(8, 'L8', 'Gare Centrale - Kasa-Vubu', 6.00, '28', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3450, 15.3050), -- Arrivée: Kasa-Vubu

-- Trajet 9 : Gare Centrale → Lingwala
(9, 'L9', 'Gare Centrale - Lingwala', 4.00, '20', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3150, 15.3050), -- Arrivée: Lingwala

-- Trajet 10 : Gare Centrale → Barumbu
(10, 'L10', 'Gare Centrale - Barumbu', 3.00, '18', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3200, 15.3250), -- Arrivée: Barumbu

-- Trajet 11 : Gare Centrale → Masina
(11, 'L11', 'Gare Centrale - Masina', 16.00, '55', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.4000, 15.3800), -- Arrivée: Masina

-- Trajet 12 : Gare Centrale → Ndjili
(12, 'L12', 'Gare Centrale - Ndjili', 18.00, '60', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3850, 15.4200), -- Arrivée: Ndjili

-- Trajet 13 : Gare Centrale → Kimbanseke
(13, 'L13', 'Gare Centrale - Kimbanseke', 20.00, '65', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.4200, 15.3600), -- Arrivée: Kimbanseke

-- Trajet 14 : Gare Centrale → Selembao
(14, 'L14', 'Gare Centrale - Selembao', 11.00, '42', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3700, 15.2700), -- Arrivée: Selembao

-- Trajet 15 : Victoire → Matete
(15, 'L15', 'Victoire - Matete', 12.00, '45', 'actif',
 -4.3200, 15.3200,  -- Départ: Victoire
 -4.3650, 15.2800), -- Arrivée: Matete

-- Trajet 16 : Lemba → Masina
(16, 'L16', 'Lemba - Masina', 10.00, '40', 'actif',
 -4.3800, 15.3500,  -- Départ: Lemba
 -4.4000, 15.3800), -- Arrivée: Masina

-- Trajet 17 : Gare Centrale → Limete
(17, 'L17', 'Gare Centrale - Limete', 13.00, '48', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3650, 15.3400), -- Arrivée: Limete

-- Trajet 18 : Gare Centrale → Makala
(18, 'L18', 'Gare Centrale - Makala', 8.00, '35', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3550, 15.2850), -- Arrivée: Makala

-- Trajet 19 : Kalamu → Lemba
(19, 'L19', 'Kalamu - Lemba', 6.00, '28', 'actif',
 -4.3500, 15.3300,  -- Départ: Kalamu
 -4.3800, 15.3500), -- Arrivée: Lemba

-- Trajet 20 : Gare Centrale → Ngaba
(20, 'L20', 'Gare Centrale - Ngaba', 7.00, '32', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.3480, 15.2950), -- Arrivée: Ngaba

-- Trajet 21 : Matete → Ndjili
(21, 'L21', 'Matete - Ndjili', 9.00, '38', 'actif',
 -4.3650, 15.2800,  -- Départ: Matete
 -4.3850, 15.4200), -- Arrivée: Ndjili

-- Trajet 22 : Gare Centrale → Kingabwa
(22, 'L22', 'Gare Centrale - Kingabwa', 14.00, '50', 'actif',
 -4.3276, 15.3136,  -- Départ: Gare Centrale
 -4.2800, 15.3600), -- Arrivée: Kingabwa

-- Trajet 23 : Victoire → Kimbanseke
(23, 'L23', 'Victoire - Kimbanseke', 17.00, '58', 'actif',
 -4.3200, 15.3200,  -- Départ: Victoire
 -4.4200, 15.3600); -- Arrivée: Kimbanseke

-- Vérifier les données insérées
SELECT 
    id,
    code,
    nom,
    distance_totale as distance_km,
    duree_estimee as duree_min,
    ROUND(latitude_depart, 4) as lat_depart,
    ROUND(longitude_depart, 4) as lng_depart,
    ROUND(latitude_arrivee, 4) as lat_arrivee,
    ROUND(longitude_arrivee, 4) as lng_arrivee
FROM trajets
ORDER BY id;

-- Résultat attendu :
-- +----+------+----------------------------------+-------------+-----------+------------+-------------+-------------+--------------+
-- | id | code | nom                              | distance_km | duree_min | lat_depart | lng_depart  | lat_arrivee | lng_arrivee  |
-- +----+------+----------------------------------+-------------+-----------+------------+-------------+-------------+--------------+
-- |  1 | L1   | Centre ville - Kasapa            |       12.00 | 45        |   -11.6667 |     27.4833 |    -11.6950 |      27.5200 |
-- |  2 | L2   | Centre ville - Plateau Karavia   |       17.00 | 55        |   -11.6667 |     27.4833 |    -11.6200 |      27.5400 |
-- |  3 | L3   | Centre ville - Bel-air Camp      |        4.00 | 20        |   -11.6667 |     27.4833 |    -11.6800 |      27.4600 |
-- |  4 | L4   | Gécamines - Plateau Karavia      |        6.00 | 30        |   -11.6550 |     27.4700 |    -11.6200 |      27.5400 |
-- |  5 | L5   | Centre Ville - Kalubwe...        |        7.00 | 35        |   -11.6667 |     27.4833 |    -11.6400 |      27.5100 |
-- +----+------+----------------------------------+-------------+-----------+------------+-------------+-------------+--------------+
