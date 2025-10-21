-- Insertion des tarifs pour les trajets existants
-- Prix calculé : 2500 CDF pour 13 km = ~192.31 CDF/km

INSERT INTO `tarifs` (`nom`, `trajet_id`, `type_tarif`, `prix`, `devise`, `statut`, `date_debut`) VALUES
-- Trajet 1: Centre ville - Kasapa (12 km)
('Tarif Normal - Centre ville Kasapa', 1, 'normal', 192.31, 'CDF', 'actif', '2025-01-01'),
('Tarif Étudiant - Centre ville Kasapa', 1, 'etudiant', 163.46, 'CDF', 'actif', '2025-01-01'),
('Tarif Senior - Centre ville Kasapa', 1, 'senior', 173.08, 'CDF', 'actif', '2025-01-01'),
('Tarif Enfant - Centre ville Kasapa', 1, 'enfant', 153.85, 'CDF', 'actif', '2025-01-01'),

-- Trajet 12: Goma → Bukavu
('Tarif Normal - Goma Bukavu', 12, 'normal', 192.31, 'CDF', 'actif', '2025-01-01'),
('Tarif Étudiant - Goma Bukavu', 12, 'etudiant', 163.46, 'CDF', 'actif', '2025-01-01'),
('Tarif Senior - Goma Bukavu', 12, 'senior', 173.08, 'CDF', 'actif', '2025-01-01'),
('Tarif Enfant - Goma Bukavu', 12, 'enfant', 153.85, 'CDF', 'actif', '2025-01-01'),

-- Trajet 13: Lubumbashi → Likasi
('Tarif Normal - Lubumbashi Likasi', 13, 'normal', 192.31, 'CDF', 'actif', '2025-01-01'),
('Tarif Étudiant - Lubumbashi Likasi', 13, 'etudiant', 163.46, 'CDF', 'actif', '2025-01-01'),
('Tarif Senior - Lubumbashi Likasi', 13, 'senior', 173.08, 'CDF', 'actif', '2025-01-01'),
('Tarif Enfant - Lubumbashi Likasi', 13, 'enfant', 153.85, 'CDF', 'actif', '2025-01-01'),

-- Trajet 14: Kinshasa → Kikwit
('Tarif Normal - Kinshasa Kikwit', 14, 'normal', 192.31, 'CDF', 'actif', '2025-01-01'),
('Tarif Étudiant - Kinshasa Kikwit', 14, 'etudiant', 163.46, 'CDF', 'actif', '2025-01-01'),
('Tarif Senior - Kinshasa Kikwit', 14, 'senior', 173.08, 'CDF', 'actif', '2025-01-01'),
('Tarif Enfant - Kinshasa Kikwit', 14, 'enfant', 153.85, 'CDF', 'actif', '2025-01-01');

-- Calculs:
-- Normal: 192.31 CDF/km (100%)
-- Étudiant: 163.46 CDF/km (85% - réduction 15%)
-- Senior: 173.08 CDF/km (90% - réduction 10%)
-- Enfant: 153.85 CDF/km (80% - réduction 20%)

-- Exemple pour 13 km:
-- Normal: 192.31 × 13 = 2,500 CDF
-- Étudiant: 163.46 × 13 = 2,125 CDF
-- Senior: 173.08 × 13 = 2,250 CDF
-- Enfant: 153.85 × 13 = 2,000 CDF
