-- --------------------------------------------------------
-- Hôte:                         127.0.0.1
-- Version du serveur:           8.0.30 - MySQL Community Server - GPL
-- SE du serveur:                Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Listage de la structure de table safari_smart_mobility. affectations_equipe
CREATE TABLE IF NOT EXISTS `affectations_equipe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `shift_id` int NOT NULL,
  `membre_id` int NOT NULL,
  `role` enum('chauffeur','controleur','receveur','autre') COLLATE utf8mb4_general_ci NOT NULL,
  `statut` enum('actif','remplace','absent') COLLATE utf8mb4_general_ci DEFAULT 'actif',
  `date_affectation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table safari_smart_mobility.affectations_equipe : ~3 rows (environ)
INSERT INTO `affectations_equipe` (`id`, `shift_id`, `membre_id`, `role`, `statut`, `date_affectation`) VALUES
	(1, 1, 10, 'chauffeur', 'actif', '2025-10-08 21:43:29'),
	(2, 1, 12, 'controleur', 'actif', '2025-10-08 21:43:29'),
	(3, 1, 15, 'receveur', 'actif', '2025-10-08 21:43:29');

-- Listage de la structure de table safari_smart_mobility. alertes
CREATE TABLE IF NOT EXISTS `alertes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_alerte` enum('critical','warning','info','success') NOT NULL,
  `titre` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `bus_id` int DEFAULT NULL,
  `membre_id` int DEFAULT NULL,
  `statut` enum('nouveau','en_cours','resolu') DEFAULT 'nouveau',
  `priorite` enum('haute','moyenne','basse') DEFAULT 'moyenne',
  `localisation` varchar(200) DEFAULT NULL,
  `date_alerte` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_resolution` datetime DEFAULT NULL,
  `resolu_par` int DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.alertes : ~12 rows (environ)
INSERT INTO `alertes` (`id`, `type_alerte`, `titre`, `message`, `bus_id`, `membre_id`, `statut`, `priorite`, `localisation`, `date_alerte`, `date_resolution`, `resolu_par`, `date_creation`) VALUES
	(1, 'critical', 'Bus #421 - Panne mécanique', 'Problème moteur détecté. Bus en panne sur la Ligne 2. Dépanneuse en route. Passagers transférés.', 1, NULL, 'en_cours', 'haute', 'Route de Matadi', '2025-10-11 20:41:47', NULL, NULL, '2025-10-11 21:26:47'),
	(2, 'critical', 'Bus #208 - Accident signalé', 'Accident mineur signalé. Bus immobilisé au niveau de Lemba. Aucun blessé. Intervention requise.', 3, NULL, 'en_cours', 'haute', 'Lemba, Kinshasa', '2025-10-11 21:11:47', NULL, NULL, '2025-10-11 21:26:47'),
	(3, 'critical', 'Bus #315 - Document expiré', 'Assurance expirée depuis 3 jours. Bus suspendu automatiquement. Renouvellement urgent requis.', 2, NULL, 'en_cours', 'haute', NULL, '2025-10-11 19:26:47', NULL, NULL, '2025-10-11 21:26:47'),
	(4, 'warning', 'Bus #156 - Contrôle technique à renouveler', 'Le contrôle technique expire dans 7 jours. Planifier le renouvellement avant le 14/10/2025.', 4, NULL, 'nouveau', 'moyenne', NULL, '2025-10-10 21:26:47', NULL, NULL, '2025-10-11 21:26:47'),
	(5, 'warning', 'Bus #512 - Niveau de carburant bas', 'Niveau de carburant à 15%. Ravitaillement recommandé avant le prochain trajet.', 6, NULL, 'nouveau', 'moyenne', NULL, '2025-10-11 18:26:47', NULL, NULL, '2025-10-11 21:26:47'),
	(6, 'warning', 'Équipe incomplète - Bus #642', 'Pas de receveur affecté pour le shift de demain 06:00-14:00. Affectation urgente requise.', 10, NULL, 'nouveau', 'haute', NULL, '2025-10-11 15:26:47', NULL, NULL, '2025-10-11 21:26:47'),
	(7, 'info', 'Bus #238 - Autorisé à reprendre le service', 'Réparations terminées. Contrôle qualité validé. Le bus est autorisé à reprendre le service.', 7, NULL, 'en_cours', 'basse', NULL, '2025-10-11 16:26:47', NULL, NULL, '2025-10-11 21:26:47'),
	(8, 'info', 'Nouveau chauffeur affecté - Bus #310', 'Grace Lumbu a été affectée comme chauffeur principal du Bus #310. Formation complétée avec succès.', 8, NULL, 'resolu', 'basse', NULL, '2025-10-10 21:26:47', NULL, NULL, '2025-10-11 21:26:47'),
	(9, 'info', 'Maintenance préventive programmée - Bus #156', 'Maintenance préventive programmée pour le 10/10/2025. Durée estimée: 4 heures. Bus de remplacement assigné.', 4, NULL, 'nouveau', 'moyenne', NULL, '2025-10-10 21:26:47', NULL, NULL, '2025-10-11 21:26:47'),
	(10, 'success', 'Bus #421 - Réparation terminée', 'La réparation du système de freinage a été complétée avec succès. Le bus est prêt à reprendre le service.', 1, NULL, 'resolu', 'basse', NULL, '2025-10-09 21:26:47', NULL, NULL, '2025-10-11 21:26:47'),
	(11, 'success', 'Formation sécurité complétée', 'Tous les chauffeurs ont complété la formation sécurité obligatoire. Certificats délivrés.', NULL, NULL, 'resolu', 'basse', NULL, '2025-10-08 21:26:47', NULL, NULL, '2025-10-11 21:26:47'),
	(12, 'warning', 'Bus #175 - Pneus à remplacer', 'L\'usure des pneus avant dépasse 80%. Remplacement recommandé dans les 48 heures.', 9, NULL, 'nouveau', 'moyenne', NULL, '2025-10-11 13:26:47', NULL, NULL, '2025-10-11 21:26:47');

-- Listage de la structure de table safari_smart_mobility. alertes_historique
CREATE TABLE IF NOT EXISTS `alertes_historique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `alerte_id` int NOT NULL,
  `action` enum('traiter','resoudre','ignorer') COLLATE utf8mb4_general_ci NOT NULL,
  `type_traitement` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `solution` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `raison` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `commentaire` text COLLATE utf8mb4_general_ci,
  `traite_par` int DEFAULT NULL,
  `date_action` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `alerte_id` (`alerte_id`),
  KEY `traite_par` (`traite_par`),
  CONSTRAINT `fk_alertes_historique_alerte` FOREIGN KEY (`alerte_id`) REFERENCES `alertes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table safari_smart_mobility.alertes_historique : ~0 rows (environ)
INSERT INTO `alertes_historique` (`id`, `alerte_id`, `action`, `type_traitement`, `solution`, `raison`, `commentaire`, `traite_par`, `date_action`) VALUES
	(1, 1, 'traiter', 'intervention_technique', NULL, NULL, 'un pneu crever remplacer', NULL, '2025-10-11 22:27:30');

-- Listage de la structure de table safari_smart_mobility. arrets
CREATE TABLE IF NOT EXISTS `arrets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trajet_id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(10,8) DEFAULT NULL,
  `distance_avec_debut` decimal(10,8) DEFAULT NULL,
  `temp_parcour` decimal(10,8) DEFAULT NULL,
  `temps_arret` int DEFAULT '3',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.arrets : ~11 rows (environ)
INSERT INTO `arrets` (`id`, `trajet_id`, `nom`, `latitude`, `longitude`, `distance_avec_debut`, `temp_parcour`, `temps_arret`, `date_creation`) VALUES
	(28, 28, 'NIEMBA', -4.35516400, 15.32082800, 0.21000000, NULL, 0, '2025-10-22 11:37:35'),
	(29, 28, 'HOPITAL', -4.35286400, 15.32153000, 0.48000000, NULL, 0, '2025-10-22 11:37:35'),
	(30, 28, 'WASTA', -4.35469000, 15.32100800, 0.27000000, NULL, 0, '2025-10-22 11:37:35'),
	(38, 29, 'LIBAYA', -4.38858700, 15.31165000, 0.19000000, NULL, 0, '2025-10-22 11:52:49'),
	(41, 25, 'ABATOIRE', -4.11220000, 15.22330000, 17.53000000, NULL, 0, '2025-10-22 11:58:12'),
	(42, 24, 'DE LA PLAINE', -4.11223455, 15.22113400, 37.14000000, NULL, 0, '2025-10-22 12:00:44'),
	(43, 1, 'BOBOZO', -4.50010000, 15.11220000, 29.44000000, NULL, 0, '2025-10-22 12:01:38'),
	(44, 1, 'KANANGA', -4.24550000, 15.36980000, 21.19000000, NULL, 0, '2025-10-22 12:01:38'),
	(45, 1, 'ECOLE TOME', -4.65520000, 15.66320000, 53.19000000, NULL, 0, '2025-10-22 12:01:38'),
	(46, 2, 'TOMBALBAY', -4.36500000, 15.20100000, 5.58000000, NULL, 0, '2025-10-22 12:03:42'),
	(47, 2, 'COL. EBEYA', -4.36450000, 15.22442000, 10.71000000, NULL, 0, '2025-10-22 12:03:42'),
	(48, 26, 'MOVENDA', -4.35329400, 15.30031100, 0.35000000, NULL, 0, '2025-10-22 12:30:51'),
	(49, 26, 'RUE BOLAFA', -4.34828800, 15.30172700, 0.93000000, NULL, 0, '2025-10-22 12:30:51');

-- Listage de la structure de table safari_smart_mobility. billets
CREATE TABLE IF NOT EXISTS `billets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_billet` varchar(50) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `trajet_id` int NOT NULL,
  `tarif_id` int NOT NULL,
  `shift_id` int DEFAULT NULL,
  `bus_id` int DEFAULT NULL,
  `client_id` int DEFAULT NULL,
  `arret_depart` varchar(100) NOT NULL,
  `arret_arrivee` varchar(100) NOT NULL,
  `date_voyage` date NOT NULL,
  `heure_depart` time DEFAULT NULL,
  `siege_numero` varchar(10) DEFAULT NULL,
  `prix_paye` decimal(10,2) NOT NULL,
  `devise` varchar(10) DEFAULT 'CDF',
  `statut_billet` enum('reserve','paye','utilise','annule','expire') DEFAULT 'reserve',
  `mode_paiement` enum('especes','mobile_money','carte_bancaire','autre') DEFAULT 'especes',
  `reference_paiement` varchar(100) DEFAULT NULL,
  `vendu_par` int DEFAULT NULL,
  `point_vente` varchar(100) DEFAULT NULL,
  `date_achat` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_utilisation` datetime DEFAULT NULL,
  `date_annulation` datetime DEFAULT NULL,
  `motif_annulation` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_billet` (`numero_billet`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.billets : ~17 rows (environ)
INSERT INTO `billets` (`id`, `numero_billet`, `qr_code`, `trajet_id`, `tarif_id`, `shift_id`, `bus_id`, `client_id`, `arret_depart`, `arret_arrivee`, `date_voyage`, `heure_depart`, `siege_numero`, `prix_paye`, `devise`, `statut_billet`, `mode_paiement`, `reference_paiement`, `vendu_par`, `point_vente`, `date_achat`, `date_utilisation`, `date_annulation`, `motif_annulation`, `date_creation`) VALUES
	(1, 'BT-2025-001234', NULL, 1, 1, NULL, NULL, 1, 'Kinshasa Centre', 'Matadi Port', '2025-10-12', '08:00:00', NULL, 5000.00, 'CDF', 'paye', 'mobile_money', NULL, NULL, NULL, '2025-10-12 14:30:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(2, 'BT-2025-001235', NULL, 1, 1, NULL, NULL, 3, 'Kinshasa Centre', 'Matadi Port', '2025-10-12', '10:00:00', NULL, 5000.00, 'CDF', 'paye', 'carte_bancaire', NULL, NULL, NULL, '2025-10-12 15:30:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(3, 'BT-2025-001236', NULL, 2, 2, NULL, NULL, 5, 'Kinshasa Centre', 'Kikwit', '2025-10-12', '14:00:00', NULL, 4000.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-12 16:00:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(4, 'RES-2025-00456', NULL, 1, 1, NULL, NULL, 2, 'Kinshasa Centre', 'Matadi Port', '2025-10-13', '06:00:00', NULL, 5000.00, 'CDF', 'reserve', 'mobile_money', NULL, NULL, NULL, '2025-10-12 13:30:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(5, 'RES-2025-00457', NULL, 3, 3, NULL, NULL, 4, 'Kinshasa Centre', 'Lubumbashi', '2025-10-13', '08:00:00', NULL, 7000.00, 'CDF', 'reserve', 'especes', NULL, NULL, NULL, '2025-10-12 14:30:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(6, 'BT-2025-001237', NULL, 1, 1, NULL, NULL, 1, 'Kinshasa Centre', 'Matadi Port', '2025-10-11', '08:00:00', NULL, 5000.00, 'CDF', 'utilise', 'mobile_money', NULL, NULL, NULL, '2025-10-11 16:30:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(7, 'BT-2025-001238', NULL, 2, 2, NULL, NULL, 3, 'Kinshasa Centre', 'Kikwit', '2025-10-11', '10:00:00', NULL, 4000.00, 'CDF', 'utilise', 'especes', NULL, NULL, NULL, '2025-10-11 16:30:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(8, 'BT-2025-001239', NULL, 1, 1, NULL, NULL, 2, 'Kinshasa Centre', 'Matadi Port', '2025-10-12', '12:00:00', NULL, 5000.00, 'CDF', 'paye', 'mobile_money', NULL, NULL, NULL, '2025-10-12 15:45:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(9, 'BT-2025-001240', NULL, 3, 3, NULL, NULL, 4, 'Kinshasa Centre', 'Lubumbashi', '2025-10-12', '15:00:00', NULL, 7000.00, 'CDF', 'paye', 'carte_bancaire', NULL, NULL, NULL, '2025-10-12 16:10:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(10, 'BT-2025-001241', NULL, 2, 2, NULL, NULL, 5, 'Kinshasa Centre', 'Kikwit', '2025-10-12', '16:00:00', NULL, 4000.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-12 16:20:49', NULL, NULL, NULL, '2025-10-12 16:30:49'),
	(13, 'BT-2025-001242', NULL, 5, 1, 1, 4, 5, 'Kinshasa Centre', 'Kikwit', '2025-10-14', '15:51:46', '4', 5000.00, 'CDF', 'reserve', 'especes', '11478552', 1, '01', '2025-10-14 15:52:22', NULL, NULL, NULL, '2025-10-14 15:53:03'),
	(14, 'BT-2025-651654', NULL, 14, 1, NULL, 14, NULL, 'auto lubumbashi 1', 'b42', '2025-10-15', NULL, NULL, 1923.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-15 01:39:05', NULL, NULL, NULL, '2025-10-15 01:39:05'),
	(15, 'BT-2025-148890', NULL, 14, 1, NULL, 14, NULL, 'auto lubumbashi 1', 'b42', '2025-10-15', NULL, NULL, 1923.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-15 01:40:23', NULL, NULL, NULL, '2025-10-15 01:40:23'),
	(16, 'BT-2025-104453', NULL, 14, 1, NULL, 14, NULL, 'auto lubumbashi 1', 'arret 1', '2025-10-15', NULL, NULL, 1154.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-15 01:43:59', NULL, NULL, NULL, '2025-10-15 01:43:59'),
	(17, 'BT-2025-139117', NULL, 14, 1, NULL, 14, NULL, 'auto lubumbashi 1', 'b42', '2025-10-15', NULL, NULL, 1923.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-15 01:48:58', NULL, NULL, NULL, '2025-10-15 01:48:58'),
	(18, 'BT-2025-153424', NULL, 14, 1, NULL, 14, NULL, 'auto lubumbashi 1', 'b42', '2025-10-15', NULL, NULL, 1923.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-15 01:53:12', NULL, NULL, NULL, '2025-10-15 01:53:12'),
	(19, 'BT-2025-143130', NULL, 14, 1, NULL, 14, NULL, 'auto lubumbashi 1', 'arret 1', '2025-10-15', NULL, NULL, 1154.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-15 01:55:54', NULL, NULL, NULL, '2025-10-15 01:55:54'),
	(20, 'BT-2025-545090', NULL, 14, 1, NULL, 14, NULL, 'auto lubumbashi 1', 'arret 1', '2025-10-15', NULL, NULL, 1154.00, 'CDF', 'reserve', 'especes', NULL, NULL, NULL, '2025-10-15 02:09:58', NULL, NULL, NULL, '2025-10-15 02:09:58'),
	(21, 'BT-2025-430273', NULL, 1, 1, NULL, 1, NULL, 'BOBOZO', 'ECOLE TOME', '2025-10-16', NULL, NULL, 4567.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-16 03:12:52', NULL, NULL, NULL, '2025-10-16 03:12:52'),
	(22, 'BT-2025-135620', NULL, 1, 1, NULL, 10, NULL, 'BOBOZO', 'ECOLE TOME', '2025-10-16', NULL, NULL, 7125.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-16 15:26:24', NULL, NULL, NULL, '2025-10-16 15:26:24'),
	(23, 'BT-2025-168094', NULL, 1, 1, NULL, 10, NULL, 'BOBOZO', 'ECOLE TOME', '2025-10-16', NULL, NULL, 7125.00, 'CDF', 'paye', 'especes', NULL, NULL, NULL, '2025-10-16 15:31:59', NULL, NULL, NULL, '2025-10-16 15:31:59');

-- Listage de la structure de table safari_smart_mobility. bus
CREATE TABLE IF NOT EXISTS `bus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero` varchar(20) NOT NULL,
  `immatriculation` varchar(50) NOT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `modele` varchar(50) DEFAULT NULL,
  `annee` int DEFAULT NULL,
  `capacite` int DEFAULT NULL,
  `kilometrage` int DEFAULT '0',
  `trajet_id` int DEFAULT NULL,
  `statut` enum('actif','maintenance','panne','inactif') DEFAULT 'actif',
  `modules` text,
  `notes` text,
  `derniere_activite` datetime DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`),
  UNIQUE KEY `immatriculation` (`immatriculation`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.bus : ~17 rows (environ)
INSERT INTO `bus` (`id`, `numero`, `immatriculation`, `marque`, `modele`, `annee`, `capacite`, `kilometrage`, `trajet_id`, `statut`, `modules`, `notes`, `derniere_activite`, `latitude`, `longitude`, `date_creation`) VALUES
	(1, '421', 'KIN-1234-AB', 'Mercedes', 'Sprinter', 2022, 50, 125450, 1, 'actif', 'datcha,wifi,pos', '', '2025-01-08 07:45:00', -4.33091979, 15.27416397, '2025-10-08 17:23:37'),
	(2, '315', 'KIN-5678-CD', 'Toyota', 'Coaster', 2021, 45, 98320, 2, 'actif', 'datcha,gps', '', '2025-01-08 08:10:00', -4.36482077, 15.29580424, '2025-10-08 17:23:37'),
	(3, '208', 'KIN-9012-EF', 'Isuzu', 'NPR', 2020, 40, 156780, 3, 'maintenance', 'datcha', 'Révision moteur en cours', '2025-01-07 18:30:00', -4.35491650, 15.28040478, '2025-10-08 17:23:37'),
	(4, '156', 'KIN-3456-GH', 'Mercedes', 'Sprinter', 2023, 50, 45200, 3, 'actif', 'datcha,wifi,pos,gps,camera', '', '2025-01-08 08:15:00', -4.36162660, 15.29305269, '2025-10-08 17:23:37'),
	(5, '089', 'KIN-7890-IJ', 'Toyota', 'Hiace', 2019, 35, 187650, 1, 'panne', 'datcha', 'Problème de transmission', '2025-01-06 14:20:00', -4.27825676, 15.27195812, '2025-10-08 17:23:37'),
	(6, '512', 'KIN-2468-KL', 'Hyundai', 'County', 2023, 45, 32100, 4, 'actif', 'datcha,wifi,gps', '', '2025-01-08 08:30:00', -4.33383911, 15.35733008, '2025-10-08 17:23:37'),
	(7, '238', 'KIN-1357-MN', 'Mercedes', 'Sprinter', 2021, 50, 112890, 4, 'actif', 'datcha,wifi,pos,camera', '', '2025-01-08 08:25:00', -4.34023224, 15.26924853, '2025-10-08 17:23:37'),
	(8, '310', 'KIN-9753-OP', 'Toyota', 'Coaster', 2020, 45, 143560, 4, 'actif', 'datcha,gps', '', '2025-01-08 08:20:00', -4.36146061, 15.32735135, '2025-10-08 17:23:37'),
	(9, '175', 'KIN-4682-QR', 'Isuzu', 'NPR', 2022, 40, 67890, 1, 'inactif', 'datcha', 'En attente d\'affectation', '2025-01-04 16:00:00', NULL, NULL, '2025-10-08 17:23:37'),
	(10, '642', 'KIN-8520-ST', 'Mercedes', 'Sprinter', 2024, 55, 12500, 1, 'actif', 'datcha,wifi,pos,gps,camera', 'Nouveau véhicule - Équipement complet', '2025-01-08 08:35:00', -4.30726142, 15.32403885, '2025-10-08 17:23:37'),
	(11, '012', 'KIN-7855-TT', 'Mercedes', 'Sprinter', 2015, 50, 80000, 5, 'actif', 'datcha,wifi,pos,gps,camera', 'Nouveau véhicule - Équipement complet', '2025-10-10 18:24:01', -4.28642147, 15.33817610, '2025-10-08 17:32:58'),
	(12, '001', 'AR-14452314-AZ', 'Hunda forgonette', 'GTY 147', 2018, 45, 12000, 3, 'actif', '', '-', '2025-10-10 23:04:20', -4.27825509, 15.33659625, '2025-10-10 22:04:20'),
	(14, '002', 'AR-14452314-AS', 'Hunda forgonette', 'GTY 147', 2018, 45, 105000, 14, 'actif', 'datcha,wifi,pos', '-', '2025-10-15 02:05:20', -4.31065347, 15.27934388, '2025-10-10 22:06:09'),
	(15, '003', 'KIN-54855-AX', 'Toyota', 'Coaster', 2021, 45, 12, 8, 'actif', 'datcha,wifi', '-', '2025-10-11 00:06:09', -4.29972017, 15.30576753, '2025-10-10 22:23:20'),
	(16, '004', 'KIN-577855-AX', 'Toyota', 'Coaster', 2021, 45, 12, 7, 'actif', 'datcha,camera', '-', '2025-10-11 00:06:41', -4.30040186, 15.32308814, '2025-10-10 22:25:02'),
	(17, '005', 'LSH-478961-AB', 'Mercedes', 'Sprinter', 2019, 50, 3000, 5, 'actif', 'datcha,wifi,pos,gps', 'Nouveau vehicule sans carte rose', '2025-10-11 00:05:51', -4.31175373, 15.31436694, '2025-10-10 22:40:12'),
	(18, '006', 'LSH-4774451-AB', 'Karsan', 'e-ATA', 2025, 135, 150, 6, 'panne', 'datcha,wifi,pos,gps,camera', 'Ce bus embarque jusqu\'à 449 kWh d\'énergie dans des batteries lithium-fer-phosphate (LFP). L\'autonomie annoncée est de 450 km (rechargement complet en 3h10).', '2025-10-13 03:40:13', -4.32130409, 15.29277870, '2025-10-10 23:12:15');

-- Listage de la structure de table safari_smart_mobility. caisses
CREATE TABLE IF NOT EXISTS `caisses` (
  `id` int NOT NULL AUTO_INCREMENT,
  `point_vente_id` int NOT NULL,
  `numero_caisse` varchar(20) NOT NULL,
  `operateur_id` int DEFAULT NULL,
  `date_ouverture` datetime NOT NULL,
  `date_fermeture` datetime DEFAULT NULL,
  `montant_initial` decimal(10,2) DEFAULT '0.00',
  `montant_final` decimal(10,2) DEFAULT NULL,
  `total_ventes` decimal(10,2) DEFAULT '0.00',
  `total_especes` decimal(10,2) DEFAULT '0.00',
  `total_mobile_money` decimal(10,2) DEFAULT '0.00',
  `total_carte` decimal(10,2) DEFAULT '0.00',
  `nombre_billets_vendus` int DEFAULT '0',
  `statut_caisse` enum('ouverte','fermee','suspendue') DEFAULT 'ouverte',
  `ecart` decimal(10,2) DEFAULT '0.00',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.caisses : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. cartes_prepayees
CREATE TABLE IF NOT EXISTS `cartes_prepayees` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_carte` varchar(50) NOT NULL,
  `code_pin` varchar(255) DEFAULT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `type_carte` enum('standard','etudiant','entreprise','senior','vip') DEFAULT 'standard',
  `nom_titulaire` varchar(100) NOT NULL,
  `telephone_titulaire` varchar(20) NOT NULL,
  `email_titulaire` varchar(100) DEFAULT NULL,
  `entreprise_nom` varchar(100) DEFAULT NULL,
  `entreprise_id` varchar(50) DEFAULT NULL,
  `ecole_nom` varchar(100) DEFAULT NULL,
  `numero_etudiant` varchar(50) DEFAULT NULL,
  `solde_actuel` decimal(10,2) DEFAULT '0.00',
  `devise` varchar(10) DEFAULT 'CDF',
  `statut_carte` enum('active','bloquee','expiree','perdue','desactivee') DEFAULT 'active',
  `date_activation` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `plafond_journalier` decimal(10,2) DEFAULT NULL,
  `reduction_pourcentage` decimal(5,2) DEFAULT '0.00',
  `photo_titulaire` varchar(255) DEFAULT NULL,
  `document_justificatif` varchar(255) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_carte` (`numero_carte`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.cartes_prepayees : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. clients
CREATE TABLE IF NOT EXISTS `clients` (
  `id` bigint NOT NULL DEFAULT '0',
  `nom` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `uid` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table safari_smart_mobility.clients : ~1 rows (environ)
INSERT INTO `clients` (`id`, `nom`, `prenom`, `telephone`, `email`, `uid`, `date_creation`) VALUES
	(0, 'Mukendi', 'Jean', '+243 812 345 678', 'jean.mukendi@email.com', NULL, '2025-10-12 15:30:49');

-- Listage de la structure de table safari_smart_mobility. colis
CREATE TABLE IF NOT EXISTS `colis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_colis` varchar(50) NOT NULL,
  `code_suivi` varchar(50) NOT NULL,
  `qr_code` varchar(255) DEFAULT NULL,
  `expediteur_nom` varchar(100) NOT NULL,
  `expediteur_telephone` varchar(20) NOT NULL,
  `expediteur_email` varchar(100) DEFAULT NULL,
  `expediteur_adresse` text,
  `destinataire_nom` varchar(100) NOT NULL,
  `destinataire_telephone` varchar(20) NOT NULL,
  `destinataire_email` varchar(100) DEFAULT NULL,
  `destinataire_adresse` text,
  `arret_depart` varchar(100) NOT NULL,
  `arret_arrivee` varchar(100) NOT NULL,
  `bus_id` int DEFAULT NULL,
  `shift_id` int DEFAULT NULL,
  `date_expedition` date NOT NULL,
  `date_livraison_prevue` date DEFAULT NULL,
  `date_livraison_effective` datetime DEFAULT NULL,
  `description_colis` text NOT NULL,
  `poids` decimal(10,2) DEFAULT NULL,
  `dimensions` varchar(50) DEFAULT NULL,
  `valeur_declaree` decimal(10,2) DEFAULT NULL,
  `fragile` tinyint(1) DEFAULT '0',
  `type_colis` enum('standard','fragile','express','volumineux','precieux') DEFAULT 'standard',
  `prix_transport` decimal(10,2) NOT NULL,
  `assurance` decimal(10,2) DEFAULT '0.00',
  `montant_total` decimal(10,2) NOT NULL,
  `devise` varchar(10) DEFAULT 'CDF',
  `mode_paiement` enum('especes','mobile_money','carte_bancaire','autre') DEFAULT 'especes',
  `reference_paiement` varchar(100) DEFAULT NULL,
  `statut_colis` enum('enregistre','en_transit','arrive','livre','retourne','perdu') DEFAULT 'enregistre',
  `statut_paiement` enum('non_paye','paye','rembourse') DEFAULT 'non_paye',
  `signature_destinataire` text,
  `photo_colis` varchar(255) DEFAULT NULL,
  `recu_path` varchar(255) DEFAULT NULL,
  `observations` text,
  `enregistre_par` int DEFAULT NULL,
  `livre_par` int DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_colis` (`numero_colis`),
  UNIQUE KEY `code_suivi` (`code_suivi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.colis : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. documents_bus
CREATE TABLE IF NOT EXISTS `documents_bus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bus_id` int NOT NULL,
  `designation` varchar(100) NOT NULL,
  `statut` enum('valide','expire','bientot') DEFAULT 'valide',
  `date_emission` date DEFAULT NULL,
  `date_expiration` date DEFAULT NULL,
  `fichier_path` varchar(255) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.documents_bus : ~24 rows (environ)
INSERT INTO `documents_bus` (`id`, `bus_id`, `designation`, `statut`, `date_emission`, `date_expiration`, `fichier_path`, `date_creation`, `date_modification`) VALUES
	(1, 1, 'Assurance', 'valide', '2024-01-15', '2025-12-15', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(2, 1, 'Contrôle technique', 'valide', '2024-02-20', '2025-08-20', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(3, 1, 'Carte grise', 'valide', '2022-03-10', '2027-03-10', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(4, 1, 'Vignette', 'bientot', '2024-01-01', '2025-01-25', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(5, 2, 'Assurance', 'valide', '2024-03-01', '2025-11-01', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(6, 2, 'Contrôle technique', 'valide', '2024-04-15', '2025-10-15', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(7, 2, 'Carte grise', 'valide', '2021-05-20', '2026-05-20', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(8, 2, 'Vignette', 'valide', '2024-01-01', '2025-12-31', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(9, 4, 'Assurance', 'valide', '2023-12-01', '2025-12-01', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(10, 4, 'Contrôle technique', 'valide', '2024-06-10', '2025-12-10', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(11, 4, 'Carte grise', 'valide', '2023-01-15', '2028-01-15', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(12, 4, 'Vignette', 'valide', '2024-01-01', '2025-12-31', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(13, 6, 'Assurance', 'valide', '2023-11-20', '2025-11-20', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(14, 6, 'Contrôle technique', 'valide', '2024-05-10', '2025-11-10', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(15, 6, 'Carte grise', 'valide', '2023-02-15', '2028-02-15', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(16, 6, 'Vignette', 'valide', '2024-01-01', '2025-12-31', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(17, 10, 'Assurance', 'valide', '2024-01-05', '2026-01-05', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(18, 10, 'Contrôle technique', 'valide', '2024-01-10', '2025-07-10', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(19, 10, 'Carte grise', 'valide', '2024-01-01', '2029-01-01', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(20, 10, 'Vignette', 'valide', '2024-01-01', '2025-12-31', NULL, '2025-10-08 17:23:37', '2025-10-08 17:23:37'),
	(21, 11, 'Assurance', 'valide', '2024-01-15', '2025-12-15', NULL, '2025-10-08 17:32:58', '2025-10-08 17:32:58'),
	(22, 11, 'Contrôle technique', 'valide', '2024-02-20', '2025-08-20', NULL, '2025-10-08 17:32:58', '2025-10-08 17:32:58'),
	(23, 11, 'Carte grise', 'valide', '2022-03-10', '2027-03-10', NULL, '2025-10-08 17:32:58', '2025-10-08 17:32:58'),
	(24, 11, 'Vignette', 'bientot', '2024-01-01', '2025-01-25', NULL, '2025-10-08 17:32:58', '2025-10-08 17:32:58');

-- Listage de la structure de table safari_smart_mobility. equipe_bord
CREATE TABLE IF NOT EXISTS `equipe_bord` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `matricule` varchar(50) DEFAULT NULL,
  `poste` enum('chauffeur','controleur','receveur','mecanicien','administratif') NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `adresse` text,
  `date_naissance` date DEFAULT NULL,
  `bus_affecte` varchar(20) DEFAULT NULL,
  `statut` enum('actif','conge','suspendu','inactif') DEFAULT 'actif',
  `date_embauche` date DEFAULT NULL,
  `type_contrat` enum('cdi','cdd','stage','interim') DEFAULT 'cdi',
  `salaire` decimal(10,2) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `notes` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `mot_de_passe` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `matricule` (`matricule`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.equipe_bord : ~29 rows (environ)
INSERT INTO `equipe_bord` (`id`, `nom`, `matricule`, `poste`, `telephone`, `email`, `adresse`, `date_naissance`, `bus_affecte`, `statut`, `date_embauche`, `type_contrat`, `salaire`, `photo`, `notes`, `date_creation`, `mot_de_passe`) VALUES
	(1, 'Jean-Pierre Mukendi', 'EMP-2025-001', 'chauffeur', '+243 812 345 678', 'jp.mukendi@safari.cd', NULL, NULL, '421', 'actif', '2020-03-15', 'cdi', NULL, NULL, '5 ans d\'expérience', '2025-10-08 17:23:37', NULL),
	(2, 'Marie Tshala', 'EMP-2025-002', 'chauffeur', '+243 823 456 789', 'm.tshala@safari.cd', NULL, NULL, '315', 'actif', '2019-06-20', 'cdi', NULL, NULL, 'Excellente conductrice', '2025-10-08 17:23:37', NULL),
	(3, 'Paul Kabongo', 'EMP-2025-003', 'chauffeur', '+243 834 567 890', 'p.kabongo@safari.cd', NULL, NULL, '156', 'actif', '2021-01-10', 'cdi', NULL, NULL, '', '2025-10-08 17:23:37', NULL),
	(4, 'Sarah Mbuyi', 'EMP-2025-004', 'chauffeur', '+243 845 678 901', 's.mbuyi@safari.cd', NULL, NULL, '512', 'actif', '2022-08-05', 'cdi', NULL, NULL, '', '2025-10-08 17:23:37', NULL),
	(5, 'David Nsimba', 'EMP-2025-005', 'chauffeur', '+243 856 789 012', 'd.nsimba@safari.cd', NULL, NULL, '238', 'actif', '2020-11-12', 'cdi', NULL, NULL, '', '2025-10-08 17:23:37', NULL),
	(6, 'Grace Lumbu', 'EMP-2025-006', 'chauffeur', '+243 867 890 123', 'g.lumbu@safari.cd', NULL, NULL, '310', 'actif', '2021-04-18', 'cdi', NULL, NULL, '', '2025-10-08 17:23:37', NULL),
	(7, 'Patrick Kalonji', 'EMP-2025-007', 'chauffeur', '+243 878 901 234', 'p.kalonji@safari.cd', NULL, NULL, '642', 'actif', '2023-12-01', 'cdi', NULL, NULL, 'Nouveau chauffeur', '2025-10-08 17:23:37', NULL),
	(8, 'Alice Kabila', 'EMP-2025-008', 'receveur', '+243 889 012 345', 'a.kabila@safari.cd', NULL, NULL, '421', 'actif', '2020-05-10', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(9, 'Bob Tshisekedi', 'EMP-2025-009', 'receveur', '+243 890 123 456', 'b.tshisekedi@safari.cd', NULL, NULL, '315', 'actif', '2019-09-15', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(10, 'Claire Mwamba', 'EMP-2025-010', 'receveur', '+243 801 234 567', 'c.mwamba@safari.cd', NULL, NULL, '156', 'actif', '2021-02-20', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(11, 'Daniel Kasongo', 'EMP-2025-011', 'receveur', '+243 812 345 678', 'd.kasongo@safari.cd', NULL, NULL, '512', 'actif', '2022-10-05', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(12, 'Emma Nkulu', 'EMP-2025-012', 'receveur', '+243 823 456 789', 'e.nkulu@safari.cd', NULL, NULL, '238', 'actif', '2020-12-15', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(13, 'Frank Ilunga', 'EMP-2025-013', 'receveur', '+243 834 567 890', 'f.ilunga@safari.cd', NULL, NULL, '310', 'actif', '2021-06-25', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(14, 'Grace Mutombo', 'EMP-2025-014', 'receveur', '+243 845 678 901', 'g.mutombo@safari.cd', NULL, NULL, '642', 'actif', '2024-01-05', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(15, 'Henri Kalala', 'EMP-2025-015', 'controleur', '+243 856 789 012', 'h.kalala@safari.cd', NULL, NULL, '421', 'actif', '2020-04-12', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(16, 'Irene Mulamba', 'EMP-2025-016', 'controleur', '+243 867 890 123', 'i.mulamba@safari.cd', NULL, NULL, '315', 'actif', '2019-08-20', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(17, 'Joseph Kanda', 'EMP-2025-017', 'controleur', '+243 878 901 234', 'j.kanda@safari.cd', NULL, NULL, '156', 'actif', '2021-03-15', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(18, 'Karen Mbala', 'EMP-2025-018', 'controleur', '+243 889 012 345', 'k.mbala@safari.cd', NULL, NULL, '512', 'actif', '2022-09-10', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(19, 'Louis Nzeza', 'EMP-2025-019', 'controleur', '+243 890 123 456', 'l.nzeza@safari.cd', NULL, NULL, '238', 'actif', '2020-10-18', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(20, 'Marie Kabongo', 'EMP-2025-020', 'controleur', '+243 801 234 567', 'm.kabongo@safari.cd', NULL, NULL, '310', 'actif', '2021-05-22', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(21, 'Nathan Tshimanga', 'EMP-2025-021', 'controleur', '+243 812 345 678', 'n.tshimanga@safari.cd', NULL, NULL, '642', 'actif', '2023-12-10', 'cdi', NULL, NULL, NULL, '2025-10-08 17:23:37', NULL),
	(22, 'Deflobert Kisongole', 'EMP-2025-022', 'chauffeur', '+243 812 345 678', 'deflo.kisongole@safari.cd', NULL, NULL, '012', 'actif', '2020-03-15', 'cdi', NULL, NULL, '5 ans d\'expérience', '2025-10-08 17:32:58', NULL),
	(23, 'Jemima Kabila', 'EMP-2025-023', 'receveur', '+243 889 012 345', 'a.kabila@safari.cd', NULL, NULL, '006', 'actif', '2020-05-10', 'cdi', NULL, NULL, NULL, '2025-10-08 17:32:58', NULL),
	(24, 'Janvier Zamunda', 'EMP-2025-024', 'controleur', '+243 856 789 012', 'h.kalala@safari.cd', NULL, NULL, '012', 'actif', '2020-04-12', 'cdi', NULL, NULL, NULL, '2025-10-08 17:32:58', NULL),
	(25, 'Kasongo mbondo', 'EMP-2025-025', 'chauffeur', '+243 815 112 000', 'k.mbondo@safari.cd', 'AV GALAXIE N05 GOLF MAISHA', '1992-03-04', '006', 'actif', '2025-10-11', 'cdi', 145000.00, NULL, 'il boss bien', '2025-10-11 00:02:08', NULL),
	(26, 'Capitaine America', 'EMP-2025-026', 'controleur', '+243 971 000 000', 'c.ameroca@safari.cd', NULL, NULL, NULL, 'actif', '2025-10-11', 'cdi', 55000.00, NULL, NULL, '2025-10-11 00:03:24', NULL),
	(27, 'Mputu herge', 'EMP-2025-027', 'receveur', '+243 974 111 111', 'mputuherge@safari.cd', NULL, NULL, '006', 'suspendu', '2025-10-11', 'cdi', 650000.00, NULL, NULL, '2025-10-11 00:06:10', NULL),
	(28, 'Kyle Masangu', 'EMP-2025-028', 'administratif', '+243971342218', 'kylechrismk243@gmail.com', 'Inconnue mais a Lubumbashi', '1996-06-22', NULL, 'actif', '2025-10-11', 'cdi', 1200000.00, NULL, NULL, '2025-10-11 04:11:09', NULL),
	(29, 'Newton Isaac', 'EMP-2025-029', 'mecanicien', '+24397445864', 'isaacnewt@gmail.com', 'Anglande inconnue', '1993-02-11', NULL, 'actif', '2025-10-11', 'cdi', 3000000.00, NULL, 'Lorem ipsum', '2025-10-11 04:55:04', NULL);

-- Listage de la structure de table safari_smart_mobility. incidents_location
CREATE TABLE IF NOT EXISTS `incidents_location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `type_incident` enum('accident','panne','retard','annulation','dommage','autre') NOT NULL,
  `description` text NOT NULL,
  `gravite` enum('mineure','moyenne','grave') DEFAULT 'mineure',
  `cout_reparation` decimal(10,2) DEFAULT NULL,
  `responsable` enum('client','entreprise','tiers','indetermine') DEFAULT NULL,
  `photos` text,
  `rapport_path` varchar(255) DEFAULT NULL,
  `date_incident` datetime NOT NULL,
  `date_resolution` datetime DEFAULT NULL,
  `statut_incident` enum('ouvert','en_cours','resolu','clos') DEFAULT 'ouvert',
  `notes` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.incidents_location : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. locations_bus
CREATE TABLE IF NOT EXISTS `locations_bus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_location` varchar(50) NOT NULL,
  `bus_id` int NOT NULL,
  `client_nom` varchar(100) NOT NULL,
  `client_telephone` varchar(20) NOT NULL,
  `client_email` varchar(100) DEFAULT NULL,
  `client_adresse` text,
  `client_type` enum('particulier','entreprise','association','autre') DEFAULT 'particulier',
  `entreprise_nom` varchar(100) DEFAULT NULL,
  `entreprise_nif` varchar(50) DEFAULT NULL,
  `type_location` enum('horaire','journaliere','hebdomadaire','mensuelle','evenement') NOT NULL,
  `date_debut` datetime NOT NULL,
  `date_fin` datetime NOT NULL,
  `duree_heures` int DEFAULT NULL,
  `duree_jours` int DEFAULT NULL,
  `destination` text,
  `itineraire` text,
  `nombre_passagers` int DEFAULT NULL,
  `avec_chauffeur` tinyint(1) DEFAULT '1',
  `chauffeur_id` int DEFAULT NULL,
  `avec_carburant` tinyint(1) DEFAULT '0',
  `kilometrage_debut` int DEFAULT NULL,
  `kilometrage_fin` int DEFAULT NULL,
  `tarif_horaire` decimal(10,2) DEFAULT NULL,
  `tarif_journalier` decimal(10,2) DEFAULT NULL,
  `montant_total` decimal(10,2) NOT NULL,
  `montant_caution` decimal(10,2) DEFAULT '0.00',
  `montant_paye` decimal(10,2) DEFAULT '0.00',
  `montant_restant` decimal(10,2) DEFAULT NULL,
  `devise` varchar(10) DEFAULT 'CDF',
  `statut_location` enum('demande','confirmee','en_cours','terminee','annulee') DEFAULT 'demande',
  `mode_paiement` enum('especes','mobile_money','carte_bancaire','virement','cheque','autre') DEFAULT NULL,
  `reference_paiement` varchar(100) DEFAULT NULL,
  `contrat_path` varchar(255) DEFAULT NULL,
  `piece_identite_path` varchar(255) DEFAULT NULL,
  `observations` text,
  `creee_par` int DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_location` (`numero_location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.locations_bus : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. logs_activite
CREATE TABLE IF NOT EXISTS `logs_activite` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_affectee` varchar(50) DEFAULT NULL,
  `enregistrement_id` int DEFAULT NULL,
  `details` text,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.logs_activite : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. modules
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `departement` enum('PL','BT','RH') COLLATE utf8mb4_general_ci NOT NULL,
  `section` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `icone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `route` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ordre` int DEFAULT '0',
  `actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table safari_smart_mobility.modules : ~25 rows (environ)
INSERT INTO `modules` (`id`, `code`, `nom`, `description`, `departement`, `section`, `icone`, `route`, `ordre`, `actif`, `date_creation`) VALUES
	(1, 'pl_dashboard', 'Dashboard', 'Tableau de bord Planification', 'PL', NULL, 'home', 'dashboard_PL', 1, 1, '2025-10-08 15:07:12'),
	(2, 'pl_gestion_bus', 'Gestion Bus', 'Gestion de la flotte de bus', 'PL', 'CONCEPTION', 'truck', 'gestion-bus', 2, 1, '2025-10-08 15:07:12'),
	(3, 'pl_equipe_bord', 'Équipe de bord', 'Gestion des équipes de bord', 'PL', 'PLANIFICATION', 'users', 'equipe-bord', 3, 1, '2025-10-08 15:07:12'),
	(4, 'pl_trajets', 'Trajets', 'Gestion des trajets', 'PL', 'CONCEPTION', 'map', 'trajets', 4, 1, '2025-10-08 15:07:12'),
	(5, 'pl_shifts', 'Gestion des services', 'Planification des shifts', 'PL', 'PLANIFICATION', 'calendar', 'shifts', 5, 1, '2025-10-08 15:07:12'),
	(6, 'pl_alertes', 'Alertes', 'Système d\'alertes', 'PL', 'STATISTIQUES', 'bell', 'alerter', 6, 1, '2025-10-08 15:07:12'),
	(7, 'pl_bi', 'Business Intelligence', 'Tableaux de bord et statistiques', 'PL', 'STATISTIQUES', 'bar-chart-2', 'bi', 7, 1, '2025-10-08 15:07:12'),
	(8, 'pl_parametres', 'Paramètres', 'Configuration du système', 'PL', NULL, 'settings', 'parametres', 8, 1, '2025-10-08 15:07:12'),
	(9, 'bt_dashboard', 'Tableau de bord', 'Dashboard Billetterie', 'BT', NULL, 'home', 'billetterie', 1, 1, '2025-10-08 15:07:12'),
	(10, 'bt_vente_billets', 'Vendre un billet', 'Vente de billets', 'BT', NULL, 'shopping-cart', 'vente-billets', 2, 1, '2025-10-08 15:07:12'),
	(11, 'bt_reservation', 'Créer une réservation', 'Système de réservation', 'BT', NULL, 'calendar', 'reservation', 3, 1, '2025-10-08 15:07:12'),
	(12, 'bt_historique', 'Historique', 'Historique des ventes', 'BT', NULL, 'list', 'historique', 4, 1, '2025-10-08 15:07:12'),
	(13, 'bt_nouvelle_carte', 'Créer une carte', 'Création de cartes prépayées', 'BT', NULL, 'plus-circle', 'nouvelle-carte', 5, 1, '2025-10-08 15:07:12'),
	(14, 'bt_cartes_prepayees', 'Liste des cartes', 'Gestion des cartes prépayées', 'BT', NULL, 'credit-card', 'cartes-prepayees', 6, 1, '2025-10-08 15:07:12'),
	(15, 'bt_tarifs', 'Gestion de tarif', 'Configuration des tarifs', 'BT', NULL, 'tag', 'tarifs', 7, 1, '2025-10-08 15:07:12'),
	(16, 'bt_canaux_vente', 'Canaux de vente', 'Gestion des canaux', 'BT', NULL, 'shopping-bag', 'canaux-vente', 8, 1, '2025-10-08 15:07:12'),
	(17, 'bt_clients', 'Clients', 'Gestion des clients', 'BT', NULL, 'users', 'clients-bt', 9, 1, '2025-10-08 15:07:12'),
	(18, 'bt_reclamations', 'Réclamations', 'Gestion des réclamations', 'BT', NULL, 'message-circle', 'reclamations', 10, 1, '2025-10-08 15:07:12'),
	(19, 'bt_statistiques', 'Statistiques', 'Statistiques de vente', 'BT', NULL, 'bar-chart-2', 'statistiques-bt', 11, 1, '2025-10-08 15:07:12'),
	(20, 'bt_locations', 'Gestion des locations', 'Location de véhicules', 'BT', NULL, 'truck', 'locations', 12, 1, '2025-10-08 15:07:12'),
	(21, 'bt_historique_locations', 'Historique locations', 'Historique des locations', 'BT', NULL, 'clock', 'historique-locations', 13, 1, '2025-10-08 15:07:12'),
	(22, 'rh_dashboard', 'Tableau de bord', 'Dashboard RH', 'RH', NULL, 'home', 'rh-dashboard', 1, 1, '2025-10-08 15:07:13'),
	(23, 'rh_personnel', 'Gestion du personnel', 'Liste et gestion des employés', 'RH', NULL, 'users', 'personnel', 2, 1, '2025-10-08 15:07:13'),
	(24, 'rh_nouveau_agent', 'Ajouter un agent', 'Recrutement d\'un nouvel agent', 'RH', NULL, 'user-plus', 'nouveau-agent', 3, 1, '2025-10-08 15:07:13'),
	(25, 'rh_contrats', 'Gestion des contrats', 'Gestion des contrats de travail', 'RH', NULL, 'file-text', 'contrats', 4, 1, '2025-10-08 15:07:13');

-- Listage de la structure de table safari_smart_mobility. notifications
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `utilisateur_id` int NOT NULL,
  `type_notification` varchar(50) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `message` text NOT NULL,
  `lien` varchar(255) DEFAULT NULL,
  `lu` tinyint(1) DEFAULT '0',
  `date_lecture` datetime DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.notifications : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. paiements_location
CREATE TABLE IF NOT EXISTS `paiements_location` (
  `id` int NOT NULL AUTO_INCREMENT,
  `location_id` int NOT NULL,
  `type_paiement` enum('acompte','caution','solde','supplement','remboursement') NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `devise` varchar(10) DEFAULT 'CDF',
  `mode_paiement` enum('especes','mobile_money','carte_bancaire','virement','cheque','autre') NOT NULL,
  `reference_paiement` varchar(100) DEFAULT NULL,
  `operateur_mobile` varchar(50) DEFAULT NULL,
  `numero_telephone_paiement` varchar(20) DEFAULT NULL,
  `recu_numero` varchar(50) DEFAULT NULL,
  `recu_path` varchar(255) DEFAULT NULL,
  `effectue_par` int DEFAULT NULL,
  `date_paiement` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.paiements_location : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. parametres_systeme
CREATE TABLE IF NOT EXISTS `parametres_systeme` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cle` varchar(100) NOT NULL,
  `valeur` text,
  `type_parametre` enum('string','number','boolean','json') DEFAULT 'string',
  `description_parametres` text,
  `date_modification` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cle` (`cle`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.parametres_systeme : ~6 rows (environ)
INSERT INTO `parametres_systeme` (`id`, `cle`, `valeur`, `type_parametre`, `description_parametres`, `date_modification`) VALUES
	(1, 'nom_entreprise', 'Safari Transport', 'string', 'Nom de l\'entreprise', '2025-10-07 20:15:12'),
	(2, 'email_contact', 'contact@safari.cd', 'string', 'Email de contact', '2025-10-07 20:15:12'),
	(3, 'telephone', '+243 XXX XXX XXX', 'string', 'Numéro de téléphone', '2025-10-07 20:15:12'),
	(4, 'fuseau_horaire', 'Africa/Kinshasa', 'string', 'Fuseau horaire', '2025-10-07 20:15:12'),
	(5, 'langue', 'fr', 'string', 'Langue par défaut', '2025-10-07 20:15:12'),
	(6, 'format_date', 'DD/MM/YYYY', 'string', 'Format de date', '2025-10-07 20:15:12');

-- Listage de la structure de table safari_smart_mobility. permissions
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` enum('admin','supervisor','operator','viewer') COLLATE utf8mb4_general_ci NOT NULL,
  `module_id` int NOT NULL,
  `peut_voir` tinyint(1) DEFAULT '0',
  `peut_creer` tinyint(1) DEFAULT '0',
  `peut_modifier` tinyint(1) DEFAULT '0',
  `peut_supprimer` tinyint(1) DEFAULT '0',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_role_module` (`role`,`module_id`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Listage des données de la table safari_smart_mobility.permissions : ~91 rows (environ)
INSERT INTO `permissions` (`id`, `role`, `module_id`, `peut_voir`, `peut_creer`, `peut_modifier`, `peut_supprimer`, `date_creation`) VALUES
	(1, 'admin', 16, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(2, 'admin', 14, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(3, 'admin', 17, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(4, 'admin', 9, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(5, 'admin', 12, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(6, 'admin', 21, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(7, 'admin', 20, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(8, 'admin', 13, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(9, 'admin', 18, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(10, 'admin', 11, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(11, 'admin', 19, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(12, 'admin', 15, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(13, 'admin', 10, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(14, 'admin', 6, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(15, 'admin', 7, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(16, 'admin', 1, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(17, 'admin', 3, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(18, 'admin', 2, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(19, 'admin', 8, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(20, 'admin', 5, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(21, 'admin', 4, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(22, 'admin', 25, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(23, 'admin', 22, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(24, 'admin', 24, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(25, 'admin', 23, 1, 1, 1, 1, '2025-10-08 15:07:13'),
	(32, 'supervisor', 1, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(33, 'supervisor', 2, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(34, 'supervisor', 3, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(35, 'supervisor', 4, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(36, 'supervisor', 5, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(37, 'supervisor', 6, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(38, 'supervisor', 7, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(39, 'supervisor', 8, 0, 1, 1, 0, '2025-10-08 15:07:13'),
	(47, 'supervisor', 9, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(48, 'supervisor', 10, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(49, 'supervisor', 11, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(50, 'supervisor', 12, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(51, 'supervisor', 13, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(52, 'supervisor', 14, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(53, 'supervisor', 15, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(54, 'supervisor', 16, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(55, 'supervisor', 17, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(56, 'supervisor', 18, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(57, 'supervisor', 19, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(58, 'supervisor', 20, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(59, 'supervisor', 21, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(62, 'supervisor', 22, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(63, 'supervisor', 23, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(64, 'supervisor', 24, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(65, 'supervisor', 25, 1, 1, 1, 0, '2025-10-08 15:07:13'),
	(69, 'operator', 1, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(70, 'operator', 2, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(71, 'operator', 3, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(72, 'operator', 4, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(73, 'operator', 5, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(74, 'operator', 6, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(76, 'operator', 9, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(77, 'operator', 10, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(78, 'operator', 11, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(79, 'operator', 12, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(80, 'operator', 13, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(81, 'operator', 14, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(82, 'operator', 17, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(83, 'operator', 18, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(84, 'operator', 20, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(85, 'operator', 21, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(91, 'operator', 22, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(92, 'operator', 23, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(93, 'operator', 24, 1, 1, 0, 0, '2025-10-08 15:07:13'),
	(94, 'viewer', 1, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(95, 'viewer', 2, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(96, 'viewer', 3, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(97, 'viewer', 4, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(98, 'viewer', 5, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(99, 'viewer', 6, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(100, 'viewer', 7, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(101, 'viewer', 9, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(102, 'viewer', 10, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(103, 'viewer', 11, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(104, 'viewer', 12, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(105, 'viewer', 13, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(106, 'viewer', 14, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(107, 'viewer', 17, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(108, 'viewer', 18, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(109, 'viewer', 19, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(110, 'viewer', 20, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(111, 'viewer', 21, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(116, 'viewer', 22, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(117, 'viewer', 23, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(118, 'viewer', 24, 1, 0, 0, 0, '2025-10-08 15:07:13'),
	(119, 'viewer', 25, 1, 0, 0, 0, '2025-10-08 15:07:13');

-- Listage de la structure de table safari_smart_mobility. points_chifte
CREATE TABLE IF NOT EXISTS `points_chifte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trajet_id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(10,8) DEFAULT NULL,
  `distance_avec_debut` decimal(10,2) DEFAULT NULL,
  `temp_parcour` decimal(10,8) DEFAULT NULL,
  `temps_arret` decimal(10,2) DEFAULT '3.00',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.points_chifte : ~6 rows (environ)
INSERT INTO `points_chifte` (`id`, `trajet_id`, `nom`, `latitude`, `longitude`, `distance_avec_debut`, `temp_parcour`, `temps_arret`, `date_creation`) VALUES
	(1, 14, 'shift 1', 10.00000000, NULL, NULL, NULL, NULL, '2025-10-11 05:24:40'),
	(10, 28, 'UPC', -4.33335500, 15.29738700, 3.66, 5.00000000, 3.00, '2025-10-22 11:37:35'),
	(15, 27, 'shift 1', -4.32210000, 15.40660000, 38.10, 57.00000000, 3.00, '2025-10-22 11:53:44'),
	(17, 25, 'Saint Raphael', -4.58990000, 15.00577000, 42.32, 63.00000000, 3.00, '2025-10-22 11:58:12'),
	(18, 24, '12EME RUE', -4.11223655, 15.11234474, 40.17, 60.00000000, 3.00, '2025-10-22 12:00:44'),
	(19, 1, 'MATERNITE', -4.14230000, 15.33660000, 20.76, 31.00000000, 3.00, '2025-10-22 12:01:38'),
	(20, 26, 'Semenon', -4.34556600, 15.30264000, 1.25, 2.00000000, 3.00, '2025-10-22 12:30:51');

-- Listage de la structure de table safari_smart_mobility. points_vente
CREATE TABLE IF NOT EXISTS `points_vente` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `code` varchar(20) NOT NULL,
  `type_point` enum('guichet','agence','mobile','en_ligne') DEFAULT 'guichet',
  `adresse` text,
  `telephone` varchar(20) DEFAULT NULL,
  `responsable_nom` varchar(100) DEFAULT NULL,
  `responsable_telephone` varchar(20) DEFAULT NULL,
  `statut` enum('actif','inactif','suspendu') DEFAULT 'actif',
  `horaire_ouverture` varchar(100) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.points_vente : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. recharges_carte
CREATE TABLE IF NOT EXISTS `recharges_carte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `carte_id` int NOT NULL,
  `montant` decimal(10,2) NOT NULL,
  `devise` varchar(10) DEFAULT 'CDF',
  `mode_paiement` enum('especes','mobile_money','carte_bancaire','virement','autre') NOT NULL,
  `reference_paiement` varchar(100) DEFAULT NULL,
  `operateur_mobile` varchar(50) DEFAULT NULL,
  `numero_telephone_paiement` varchar(20) DEFAULT NULL,
  `frais_recharge` decimal(10,2) DEFAULT '0.00',
  `solde_avant` decimal(10,2) NOT NULL,
  `solde_apres` decimal(10,2) NOT NULL,
  `effectue_par` int DEFAULT NULL,
  `point_vente` varchar(100) DEFAULT NULL,
  `statut_recharge` enum('en_attente','reussie','echouee','annulee') DEFAULT 'en_attente',
  `date_recharge` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.recharges_carte : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. reclamations_colis
CREATE TABLE IF NOT EXISTS `reclamations_colis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `colis_id` int NOT NULL,
  `type_reclamation` enum('retard','perte','dommage','erreur_livraison','autre') NOT NULL,
  `description` text NOT NULL,
  `montant_reclame` decimal(10,2) DEFAULT NULL,
  `photos` text,
  `documents` text,
  `statut_reclamation` enum('ouverte','en_cours','resolue','rejetee','fermee') DEFAULT 'ouverte',
  `resolution` text,
  `montant_indemnisation` decimal(10,2) DEFAULT NULL,
  `date_resolution` datetime DEFAULT NULL,
  `traitee_par` int DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.reclamations_colis : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. reservations
CREATE TABLE IF NOT EXISTS `reservations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_reservation` varchar(50) NOT NULL,
  `client_id` int NOT NULL,
  `trajet_id` int NOT NULL,
  `shift_id` int DEFAULT NULL,
  `bus_id` int DEFAULT NULL,
  `arret_depart` varchar(100) NOT NULL,
  `arret_arrivee` varchar(100) NOT NULL,
  `date_voyage` date NOT NULL,
  `heure_depart` time DEFAULT NULL,
  `nombre_places` int DEFAULT '1',
  `sieges_reserves` text,
  `montant_total` decimal(10,2) NOT NULL,
  `statut_reservation` enum('en_attente','confirmee','payee','annulee','expiree') DEFAULT 'en_attente',
  `date_expiration` datetime DEFAULT NULL,
  `code_confirmation` varchar(20) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_reservation` (`numero_reservation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.reservations : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. shifts
CREATE TABLE IF NOT EXISTS `shifts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `bus_numero` varchar(20) NOT NULL,
  `date_prevue` date NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `chauffeur_id` int NOT NULL,
  `controleur_id` int NOT NULL,
  `receveur_id` int NOT NULL,
  `trajet_id` int NOT NULL,
  `shift_effectuee` int NOT NULL,
  `statut` enum('planifie','actif','termine','annule') DEFAULT 'planifie',
  `notes` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.shifts : ~3 rows (environ)
INSERT INTO `shifts` (`id`, `bus_numero`, `date_prevue`, `heure_debut`, `heure_fin`, `chauffeur_id`, `controleur_id`, `receveur_id`, `trajet_id`, `shift_effectuee`, `statut`, `notes`, `date_creation`) VALUES
	(1, '012', '2025-10-09', '07:00:00', '15:00:00', 1, 2, 1, 4, 0, 'planifie', NULL, '2025-10-08 21:43:29'),
	(2, '006', '2025-10-11', '06:00:00', '12:00:00', 0, 0, 27, 0, 0, 'planifie', NULL, '2025-10-11 01:36:05'),
	(5, '006', '2025-10-11', '13:00:00', '19:00:00', 0, 0, 23, 0, 0, 'termine', NULL, '2025-10-11 01:49:40');

-- Listage de la structure de table safari_smart_mobility. statistiques_bi
CREATE TABLE IF NOT EXISTS `statistiques_bi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_stat` varchar(50) NOT NULL,
  `valeur` decimal(15,2) NOT NULL,
  `periode` date NOT NULL,
  `bus_id` int DEFAULT NULL,
  `details` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.statistiques_bi : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. suivi_colis
CREATE TABLE IF NOT EXISTS `suivi_colis` (
  `id` int NOT NULL AUTO_INCREMENT,
  `colis_id` int NOT NULL,
  `statut` varchar(50) NOT NULL,
  `localisation` varchar(100) DEFAULT NULL,
  `bus_id` int DEFAULT NULL,
  `description_etape` text,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `effectue_par` int DEFAULT NULL,
  `date_etape` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.suivi_colis : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. tarifs
CREATE TABLE IF NOT EXISTS `tarifs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `trajet_id` int DEFAULT NULL,
  `type_tarif` enum('normal','entreprise','etudiant','senior','enfant','touriste') DEFAULT 'normal',
  `prix` decimal(10,2) NOT NULL,
  `devise` varchar(10) DEFAULT 'CDF',
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=97 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.tarifs : ~94 rows (environ)
INSERT INTO `tarifs` (`id`, `nom`, `trajet_id`, `type_tarif`, `prix`, `devise`, `statut`, `date_debut`, `date_fin`, `date_creation`) VALUES
	(1, 'Tarif Normal - Gare Centrale - Lemba', 1, 'normal', 3000.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(2, 'Tarif Étudiant - Gare Centrale - Lemba', 1, 'etudiant', 2550.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(3, 'Tarif Senior - Gare Centrale - Lemba', 1, 'senior', 2700.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(4, 'Tarif Enfant - Gare Centrale - Lemba', 1, 'enfant', 2400.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(5, 'Tarif Normal - Gare Centrale - Ndjili', 12, 'normal', 4500.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(6, 'Tarif Étudiant - Gare Centrale - Ndjili', 12, 'etudiant', 3825.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(7, 'Tarif Senior - Gare Centrale - Ndjili', 12, 'senior', 4050.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(8, 'Tarif Enfant - Gare Centrale - Ndjili', 12, 'enfant', 3600.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(9, 'Tarif Normal - Gare Centrale - Kimbanseke', 13, 'normal', 5000.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(10, 'Tarif Étudiant - Gare Centrale - Kimbanseke', 13, 'etudiant', 4250.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(11, 'Tarif Senior - Gare Centrale - Kimbanseke', 13, 'senior', 4500.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(12, 'Tarif Enfant - Gare Centrale - Kimbanseke', 13, 'enfant', 4000.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(13, 'Tarif Normal - Gare Centrale - Selembao', 14, 'normal', 2750.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(14, 'Tarif Étudiant - Gare Centrale - Selembao', 14, 'etudiant', 2337.50, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(15, 'Tarif Senior - Gare Centrale - Selembao', 14, 'senior', 2475.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(16, 'Tarif Enfant - Gare Centrale - Selembao', 14, 'enfant', 2200.00, 'CDF', 'actif', '2025-01-01', NULL, '2025-10-14 23:30:08'),
	(17, 'Tarif Normal - Gare Centrale - Bandalungwa', 7, 'normal', 200.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:23:38'),
	(18, 'Tarif Étudiant - Gare Centrale - Bandalungwa', 7, 'etudiant', 170.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:23:39'),
	(19, 'Tarif Senior - Gare Centrale - Bandalungwa', 7, 'senior', 180.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:23:39'),
	(20, 'Tarif Enfant - Gare Centrale - Bandalungwa', 7, 'enfant', 160.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:23:39'),
	(21, 'Tarif Normal - Gare Centrale - Barumbu', 10, 'normal', 150.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:25:51'),
	(22, 'Tarif Étudiant - Gare Centrale - Barumbu', 10, 'etudiant', 127.50, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:25:51'),
	(23, 'Tarif Senior - Gare Centrale - Barumbu', 10, 'senior', 135.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:25:51'),
	(24, 'Tarif Enfant - Gare Centrale - Barumbu', 10, 'enfant', 120.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:25:51'),
	(25, 'Tarif Normal - Gare Centrale - Bandalungwa', 7, 'normal', 150.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:26:21'),
	(26, 'Tarif Étudiant - Gare Centrale - Bandalungwa', 7, 'etudiant', 127.50, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:26:21'),
	(27, 'Tarif Senior - Gare Centrale - Bandalungwa', 7, 'senior', 135.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:26:21'),
	(28, 'Tarif Enfant - Gare Centrale - Bandalungwa', 7, 'enfant', 120.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:26:21'),
	(29, 'Tarif Normal - Gare Centrale - Kalamu', 5, 'normal', 300.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:31:13'),
	(30, 'Tarif Étudiant - Gare Centrale - Kalamu', 5, 'etudiant', 255.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:31:13'),
	(31, 'Tarif Senior - Gare Centrale - Kalamu', 5, 'senior', 270.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:31:13'),
	(32, 'Tarif Enfant - Gare Centrale - Kalamu', 5, 'enfant', 240.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:31:13'),
	(33, 'Tarif Normal - Gare Centrale - Bandalungwa', 7, 'normal', 250.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:33:03'),
	(34, 'Tarif Étudiant - Gare Centrale - Bandalungwa', 7, 'etudiant', 213.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:33:03'),
	(35, 'Tarif Enfant - Gare Centrale - Bandalungwa', 7, 'enfant', 200.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:33:03'),
	(36, 'Tarif Senior - Gare Centrale - Bandalungwa', 7, 'senior', 225.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:33:03'),
	(37, 'Tarif Normal - Gare Centrale - Kasa-Vubu', 8, 'normal', 1450.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:36:43'),
	(38, 'Tarif Étudiant - Gare Centrale - Kasa-Vubu', 8, 'etudiant', 1232.50, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:36:44'),
	(39, 'Tarif Senior - Gare Centrale - Kasa-Vubu', 8, 'senior', 1305.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:36:44'),
	(40, 'Tarif Enfant - Gare Centrale - Kasa-Vubu', 8, 'enfant', 1160.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:36:44'),
	(41, 'Tarif Normal - Gare Centrale - Kasa-Vubu', 8, 'normal', 150.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:36:59'),
	(42, 'Tarif Étudiant - Gare Centrale - Kasa-Vubu', 8, 'etudiant', 128.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:36:59'),
	(43, 'Tarif Enfant - Gare Centrale - Kasa-Vubu', 8, 'enfant', 120.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:36:59'),
	(44, 'Tarif Senior - Gare Centrale - Kasa-Vubu', 8, 'senior', 135.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:37:00'),
	(45, 'Tarif Normal - Gare Centrale - Kimbanseke', 13, 'normal', 1900.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:38:41'),
	(46, 'Tarif Senior - Gare Centrale - Kimbanseke', 13, 'senior', 1710.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:38:41'),
	(47, 'Tarif Étudiant - Gare Centrale - Kimbanseke', 13, 'etudiant', 1615.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:38:41'),
	(48, 'Tarif Enfant - Gare Centrale - Kimbanseke', 13, 'enfant', 1520.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:38:41'),
	(49, 'Tarif Normal - Gare Centrale - Kimbanseke', 13, 'normal', 1500.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:38:57'),
	(50, 'Tarif Étudiant - Gare Centrale - Kimbanseke', 13, 'etudiant', 1275.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:38:57'),
	(51, 'Tarif Enfant - Gare Centrale - Kimbanseke', 13, 'enfant', 1200.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:38:57'),
	(52, 'Tarif Senior - Gare Centrale - Kimbanseke', 13, 'senior', 1350.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:38:58'),
	(53, 'Tarif Normal - Gare Centrale - Kimbanseke', 13, 'normal', 1500.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:39:28'),
	(54, 'Tarif Étudiant - Gare Centrale - Kimbanseke', 13, 'etudiant', 1275.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:39:28'),
	(55, 'Tarif Senior - Gare Centrale - Kimbanseke', 13, 'senior', 1350.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:39:28'),
	(56, 'Tarif Enfant - Gare Centrale - Kimbanseke', 13, 'enfant', 1200.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:39:28'),
	(57, 'Tarif Normal - Gare Centrale - Kimbanseke', 13, 'normal', 1600.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:40:52'),
	(58, 'Tarif Senior - Gare Centrale - Kimbanseke', 13, 'senior', 1440.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:40:52'),
	(59, 'Tarif Étudiant - Gare Centrale - Kimbanseke', 13, 'etudiant', 1360.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:40:52'),
	(60, 'Tarif Enfant - Gare Centrale - Kimbanseke', 13, 'enfant', 1280.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:40:52'),
	(61, 'Tarif Normal - Gare Centrale - Kimbanseke', 13, 'normal', 1550.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:42:27'),
	(62, 'Tarif Étudiant - Gare Centrale - Kimbanseke', 13, 'etudiant', 1318.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:42:27'),
	(63, 'Tarif Senior - Gare Centrale - Kimbanseke', 13, 'senior', 1395.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:42:27'),
	(64, 'Tarif Enfant - Gare Centrale - Kimbanseke', 13, 'enfant', 1240.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 03:42:27'),
	(65, 'Tarif Normal - Gare Centrale - Kimbanseke', 13, 'normal', 1600.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:07:29'),
	(66, 'Tarif Senior - Gare Centrale - Kimbanseke', 13, 'senior', 1440.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:07:29'),
	(67, 'Tarif Étudiant - Gare Centrale - Kimbanseke', 13, 'etudiant', 1360.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:07:29'),
	(68, 'Tarif Enfant - Gare Centrale - Kimbanseke', 13, 'enfant', 1280.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:07:29'),
	(69, 'Tarif Senior - Gare Centrale - Kimbanseke', 13, 'senior', 1440.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:07:51'),
	(70, 'Tarif Étudiant - Gare Centrale - Kimbanseke', 13, 'etudiant', 1360.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:07:51'),
	(71, 'Tarif Enfant - Gare Centrale - Kimbanseke', 13, 'enfant', 1280.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:07:51'),
	(72, 'Tarif Normal - Gare Centrale - Kimbanseke', 13, 'normal', 1600.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:07:51'),
	(73, 'Tarif Normal - KISANGANI - KINMAZIERE', 24, 'normal', 200.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:08:34'),
	(74, 'Tarif Étudiant - KISANGANI - KINMAZIERE', 24, 'etudiant', 170.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:08:34'),
	(75, 'Tarif Senior - KISANGANI - KINMAZIERE', 24, 'senior', 180.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:08:35'),
	(76, 'Tarif Enfant - KISANGANI - KINMAZIERE', 24, 'enfant', 160.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:08:35'),
	(77, 'Tarif Normal - KISANGANI - KINMAZIERE', 24, 'normal', 300.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:08:53'),
	(78, 'Tarif Étudiant - KISANGANI - KINMAZIERE', 24, 'etudiant', 255.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:08:53'),
	(79, 'Tarif Senior - KISANGANI - KINMAZIERE', 24, 'senior', 270.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:08:53'),
	(80, 'Tarif Enfant - KISANGANI - KINMAZIERE', 24, 'enfant', 240.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:08:53'),
	(81, 'Tarif Normal - KISANGANI - KINMAZIERE', 24, 'normal', 300.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:09:09'),
	(82, 'Tarif Enfant - KISANGANI - KINMAZIERE', 24, 'enfant', 240.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:09:09'),
	(83, 'Tarif Senior - KISANGANI - KINMAZIERE', 24, 'senior', 270.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:09:09'),
	(84, 'Tarif Étudiant - KISANGANI - KINMAZIERE', 24, 'etudiant', 255.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:09:09'),
	(85, 'Tarif Normal - Gare Centrale - Kimbanseke', 13, 'normal', 100.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:14:16'),
	(86, 'Tarif Étudiant - Gare Centrale - Kimbanseke', 13, 'etudiant', 85.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:14:16'),
	(87, 'Tarif Enfant - Gare Centrale - Kimbanseke', 13, 'enfant', 80.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:14:16'),
	(88, 'Tarif Senior - Gare Centrale - Kimbanseke', 13, 'senior', 90.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:14:16'),
	(89, 'Tarif Senior - UPN - GARE CENTRALE', 1, 'senior', 270.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:14:53'),
	(90, 'Tarif Normal - UPN - GARE CENTRALE', 1, 'normal', 300.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:14:53'),
	(91, 'Tarif Étudiant - UPN - GARE CENTRALE', 1, 'etudiant', 255.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:14:53'),
	(92, 'Tarif Enfant - UPN - GARE CENTRALE', 1, 'enfant', 240.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:14:53'),
	(93, 'Tarif Normal - Gare Centrale - Ndjili', 12, 'normal', 450.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:18:53'),
	(94, 'Tarif Étudiant - Gare Centrale - Ndjili', 12, 'etudiant', 383.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:18:53'),
	(95, 'Tarif Senior - Gare Centrale - Ndjili', 12, 'senior', 405.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:18:53'),
	(96, 'Tarif Enfant - Gare Centrale - Ndjili', 12, 'enfant', 360.00, 'CDF', 'actif', NULL, NULL, '2025-10-16 04:18:53');

-- Listage de la structure de table safari_smart_mobility. trajets
CREATE TABLE IF NOT EXISTS `trajets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) DEFAULT NULL,
  `nom` varchar(100) NOT NULL,
  `distance_totale` decimal(10,2) DEFAULT NULL,
  `duree_estimee` varchar(50) DEFAULT NULL,
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `latitude_depart` decimal(10,8) DEFAULT NULL,
  `longitude_depart` decimal(11,8) DEFAULT NULL,
  `latitude_arrivee` decimal(10,8) DEFAULT NULL,
  `longitude_arrivee` decimal(11,8) DEFAULT NULL,
  `couleur` varchar(50) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.trajets : ~8 rows (environ)
INSERT INTO `trajets` (`id`, `code`, `nom`, `distance_totale`, `duree_estimee`, `statut`, `latitude_depart`, `longitude_depart`, `latitude_arrivee`, `longitude_arrivee`, `couleur`, `date_creation`) VALUES
	(1, 'L1', 'UPN - GARE CENTRALE', 12.89, '45', 'actif', -4.40548800, 15.25721300, -4.30711700, 15.31872100, '#3b82f6', '2025-10-14 11:09:38'),
	(2, 'L2', 'LEMBA - HOTEL DES POSTES', 9.47, '50', 'actif', -4.38732300, 15.32837900, -4.30464500, 15.30772200, '#D9BA96', '2025-10-14 11:09:38'),
	(24, 'L3', 'KISANGANI - KINMAZIERE', 15.90, '90', 'actif', -4.41024300, 15.41120100, -4.30560400, 15.31344600, '#3BF4F7', '2025-10-16 02:33:27'),
	(25, 'L4', 'MASINA Q3 - PLACE DES EVOLUEE', 15.00, '88', 'actif', -4.40472700, 15.37819200, -4.30610200, 15.28586300, '#943841', '2025-10-16 02:38:03'),
	(26, 'L5', 'NGIRI NGIRI - GARE CENTRAL', 5.88, '30', 'actif', -4.35633600, 15.29937000, -4.30711700, 15.31872100, '#E7451D', '2025-10-16 02:49:16'),
	(27, 'L6', 'MASINA Q1 - BATETELA', 12.96, '46', 'actif', -4.38629900, 15.39676300, -4.34928400, 15.28591800, '#EAF73B', '2025-10-16 04:44:03'),
	(28, 'L7', 'KAPELA - CLINIC NGALIEMA', 7.21, '38', 'actif', -4.35697600, 15.32032300, -4.31463500, 15.27104800, '#B1359A', '2025-10-21 16:36:46'),
	(29, 'L8', 'RP NGABA - PLACE DES EVOLUE', 9.70, '53', 'actif', -4.38936600, 15.31199400, -4.30610200, 15.28586300, '#33D136', '2025-10-21 18:32:31');

-- Listage de la structure de table safari_smart_mobility. trajets_effectues
CREATE TABLE IF NOT EXISTS `trajets_effectues` (
  `id` int NOT NULL AUTO_INCREMENT,
  `shift_effectuee` int(10) unsigned zerofill DEFAULT NULL,
  `bus_id` int NOT NULL,
  `trajet_id` int NOT NULL,
  `date_depart` int NOT NULL,
  `heure_depart` time DEFAULT NULL,
  `heure_arrivee` time DEFAULT NULL,
  `nombre_passagers` int DEFAULT '0',
  `revenus` decimal(10,2) DEFAULT '0.00',
  `distance_parcourue` decimal(10,2) DEFAULT NULL,
  `carburant_consomme` decimal(10,2) DEFAULT NULL,
  `incidents` text,
  `statut` enum('termine','en_cours','interronpue','annulee') DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.trajets_effectues : ~53 rows (environ)
INSERT INTO `trajets_effectues` (`id`, `shift_effectuee`, `bus_id`, `trajet_id`, `date_depart`, `heure_depart`, `heure_arrivee`, `nombre_passagers`, `revenus`, `distance_parcourue`, `carburant_consomme`, `incidents`, `statut`, `date_creation`) VALUES
	(1, NULL, 1, 1, 20251012, '06:00:00', '10:30:00', 45, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(2, NULL, 2, 2, 20251012, '06:30:00', '11:00:00', 38, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(3, NULL, 3, 1, 20251012, '14:00:00', '18:30:00', 42, 0.00, NULL, NULL, NULL, 'en_cours', '2025-10-12 16:17:44'),
	(4, NULL, 4, 3, 20251012, '14:30:00', NULL, 35, 0.00, NULL, NULL, NULL, 'en_cours', '2025-10-12 16:17:44'),
	(5, NULL, 1, 1, 20251011, '06:00:00', '10:30:00', 48, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(6, NULL, 2, 2, 20251011, '06:30:00', '11:00:00', 40, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(7, NULL, 3, 1, 20251011, '14:00:00', '18:30:00', 44, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(8, NULL, 4, 3, 20251011, '14:30:00', '19:00:00', 37, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(9, NULL, 5, 4, 20251011, '07:00:00', '12:00:00', 30, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(10, NULL, 1, 1, 20251010, '06:00:00', '10:30:00', 46, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(11, NULL, 2, 2, 20251010, '06:30:00', '11:00:00', 39, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(12, NULL, 3, 1, 20251010, '14:00:00', '18:30:00', 43, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(13, NULL, 4, 3, 20251010, '14:30:00', '19:00:00', 36, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(14, NULL, 1, 1, 20251009, '06:00:00', '10:30:00', 47, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(15, NULL, 2, 2, 20251009, '06:30:00', '11:00:00', 41, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(16, NULL, 3, 1, 20251009, '14:00:00', '18:30:00', 45, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(17, NULL, 4, 3, 20251009, '14:30:00', '19:00:00', 38, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(18, NULL, 5, 4, 20251009, '07:00:00', '12:00:00', 32, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(19, NULL, 1, 1, 20251008, '06:00:00', '10:30:00', 44, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(20, NULL, 2, 2, 20251008, '06:30:00', '11:00:00', 37, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(21, NULL, 3, 1, 20251008, '14:00:00', '18:30:00', 41, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(22, NULL, 1, 1, 20251007, '06:00:00', '10:30:00', 45, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(23, NULL, 2, 2, 20251007, '06:30:00', '11:00:00', 38, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(24, NULL, 4, 3, 20251007, '14:30:00', '19:00:00', 35, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(25, NULL, 1, 1, 20251006, '06:00:00', '10:30:00', 46, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(26, NULL, 2, 2, 20251006, '06:30:00', '11:00:00', 39, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(27, NULL, 3, 1, 20251006, '14:00:00', '18:30:00', 42, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(28, NULL, 5, 4, 20251006, '07:00:00', '12:00:00', 31, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(29, NULL, 1, 1, 20251005, '06:00:00', '10:30:00', 47, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(30, NULL, 2, 2, 20251005, '06:30:00', '11:00:00', 40, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(31, NULL, 4, 3, 20251005, '14:30:00', '19:00:00', 36, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(32, NULL, 1, 1, 20251002, '06:00:00', '10:30:00', 48, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(33, NULL, 2, 2, 20251002, '06:30:00', '11:00:00', 41, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(34, NULL, 3, 1, 20251001, '14:00:00', '18:30:00', 43, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(35, NULL, 4, 3, 20250930, '14:30:00', '19:00:00', 37, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(36, NULL, 5, 4, 20250929, '07:00:00', '12:00:00', 33, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(37, NULL, 1, 1, 20250928, '06:00:00', '10:30:00', 46, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(38, NULL, 2, 2, 20250927, '06:30:00', '11:00:00', 39, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(39, NULL, 3, 1, 20250926, '14:00:00', '18:30:00', 44, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(40, NULL, 4, 3, 20250925, '14:30:00', '19:00:00', 38, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(41, NULL, 1, 1, 20250924, '06:00:00', '10:30:00', 45, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(42, NULL, 2, 2, 20250923, '06:30:00', '11:00:00', 40, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(43, NULL, 5, 4, 20250922, '07:00:00', '12:00:00', 34, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(44, NULL, 1, 1, 20250921, '06:00:00', '10:30:00', 47, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(45, NULL, 2, 2, 20250920, '06:30:00', '11:00:00', 42, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(46, NULL, 3, 1, 20250919, '14:00:00', '18:30:00', 41, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(47, NULL, 4, 3, 20250918, '14:30:00', '19:00:00', 36, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(48, NULL, 1, 1, 20250917, '06:00:00', '10:30:00', 48, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(49, NULL, 2, 2, 20250916, '06:30:00', '11:00:00', 43, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(50, NULL, 5, 4, 20250915, '07:00:00', '12:00:00', 35, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(51, NULL, 3, 1, 20250914, '14:00:00', '18:30:00', 42, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(52, NULL, 4, 3, 20250913, '14:30:00', '19:00:00', 39, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44'),
	(53, NULL, 1, 1, 20250912, '06:00:00', '10:30:00', 46, 0.00, NULL, NULL, NULL, 'termine', '2025-10-12 16:17:44');

-- Listage de la structure de table safari_smart_mobility. transactions_billeterie
CREATE TABLE IF NOT EXISTS `transactions_billeterie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_transaction` enum('vente','reservation','annulation','remboursement') NOT NULL,
  `billet_id` int DEFAULT NULL,
  `reservation_id` int DEFAULT NULL,
  `montant` decimal(10,2) NOT NULL,
  `devise` varchar(10) DEFAULT 'CDF',
  `mode_paiement` enum('especes','mobile_money','carte_bancaire','autre') NOT NULL,
  `reference_paiement` varchar(100) DEFAULT NULL,
  `statut_transaction` enum('en_attente','reussie','echouee','annulee') DEFAULT 'en_attente',
  `operateur_mobile` varchar(50) DEFAULT NULL,
  `numero_telephone_paiement` varchar(20) DEFAULT NULL,
  `frais_transaction` decimal(10,2) DEFAULT '0.00',
  `effectue_par` int DEFAULT NULL,
  `point_vente` varchar(100) DEFAULT NULL,
  `details_transaction` text,
  `date_transaction` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.transactions_billeterie : ~10 rows (environ)
INSERT INTO `transactions_billeterie` (`id`, `type_transaction`, `billet_id`, `reservation_id`, `montant`, `devise`, `mode_paiement`, `reference_paiement`, `statut_transaction`, `operateur_mobile`, `numero_telephone_paiement`, `frais_transaction`, `effectue_par`, `point_vente`, `details_transaction`, `date_transaction`) VALUES
	(1, 'vente', 1, NULL, 5000.00, 'CDF', 'mobile_money', NULL, 'reussie', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-12 14:30:49'),
	(2, 'vente', 2, NULL, 5000.00, 'CDF', 'carte_bancaire', NULL, 'reussie', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-12 15:30:49'),
	(3, 'vente', 3, NULL, 4000.00, 'CDF', 'especes', NULL, 'reussie', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-12 16:00:49'),
	(4, 'reservation', 4, NULL, 5000.00, 'CDF', 'mobile_money', NULL, 'en_attente', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-12 13:30:49'),
	(5, 'reservation', 5, NULL, 7000.00, 'CDF', 'especes', NULL, 'en_attente', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-12 14:30:49'),
	(6, 'vente', 6, NULL, 5000.00, 'CDF', 'mobile_money', NULL, 'reussie', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-11 16:30:49'),
	(7, 'vente', 7, NULL, 4000.00, 'CDF', 'especes', NULL, 'reussie', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-11 16:30:49'),
	(8, 'vente', 8, NULL, 5000.00, 'CDF', 'mobile_money', NULL, 'reussie', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-12 15:45:49'),
	(9, 'vente', 9, NULL, 7000.00, 'CDF', 'carte_bancaire', NULL, 'reussie', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-12 16:10:49'),
	(10, 'vente', 10, NULL, 4000.00, 'CDF', 'especes', NULL, 'reussie', NULL, NULL, 0.00, NULL, NULL, NULL, '2025-10-12 16:20:49');

-- Listage de la structure de table safari_smart_mobility. transactions_carte
CREATE TABLE IF NOT EXISTS `transactions_carte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `carte_id` int NOT NULL,
  `type_transaction` enum('paiement_billet','recharge','remboursement','annulation') NOT NULL,
  `billet_id` int DEFAULT NULL,
  `montant` decimal(10,2) NOT NULL,
  `devise` varchar(10) DEFAULT 'CDF',
  `solde_avant` decimal(10,2) NOT NULL,
  `solde_apres` decimal(10,2) NOT NULL,
  `reduction_appliquee` decimal(10,2) DEFAULT '0.00',
  `bus_id` int DEFAULT NULL,
  `trajet_id` int DEFAULT NULL,
  `reference_transaction` varchar(100) DEFAULT NULL,
  `description_transaction` text,
  `date_transaction` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.transactions_carte : ~0 rows (environ)

-- Listage de la structure de table safari_smart_mobility. utilisateurs
CREATE TABLE IF NOT EXISTS `utilisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `departement` enum('PL','BT','RH') NOT NULL DEFAULT 'PL',
  `role` enum('admin','supervisor','operator','viewer') DEFAULT 'viewer',
  `statut` enum('actif','inactif','suspendu') DEFAULT 'actif',
  `avatar` varchar(10) DEFAULT NULL,
  `derniere_connexion` datetime DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Listage des données de la table safari_smart_mobility.utilisateurs : ~8 rows (environ)
INSERT INTO `utilisateurs` (`id`, `nom`, `email`, `mot_de_passe`, `departement`, `role`, `statut`, `avatar`, `derniere_connexion`, `date_creation`) VALUES
	(1, 'Superviseur Planification', 'admin.pl@safari.cd', '$argon2id$v=19$m=65536,t=4,p=1$MWVZSm9PZFdjWnhBNzVlSg$/1PvHawr7q6CyhaIsm8X+DqolE2rqEZdgzrIbT74YMQ', 'PL', 'supervisor', 'actif', 'SP', '2025-10-08 15:47:48', '2025-10-08 15:18:56'),
	(2, 'Admin Billetterie', 'admin.bt@safari.cd', '$argon2id$v=19$m=65536,t=4,p=1$MWVZSm9PZFdjWnhBNzVlSg$/1PvHawr7q6CyhaIsm8X+DqolE2rqEZdgzrIbT74YMQ', 'BT', 'admin', 'actif', 'AB', '2025-10-16 21:28:29', '2025-10-08 15:18:56'),
	(3, 'Admin RH', 'admin.rh@safari.cd', '$argon2id$v=19$m=65536,t=4,p=1$MWVZSm9PZFdjWnhBNzVlSg$/1PvHawr7q6CyhaIsm8X+DqolE2rqEZdgzrIbT74YMQ', 'RH', 'admin', 'actif', 'RH', '2025-10-11 04:49:46', '2025-10-08 15:18:56'),
	(4, 'Landry Mwanda', 'landry.pl@safari.cd', '$argon2id$v=19$m=65536,t=4,p=1$MWVZSm9PZFdjWnhBNzVlSg$/1PvHawr7q6CyhaIsm8X+DqolE2rqEZdgzrIbT74YMQ', 'PL', 'operator', 'actif', 'KM', '2025-10-08 16:11:38', '2025-10-08 15:18:56'),
	(5, 'Larry Kaboba', 'larry.kaboba@safari.cd', '$argon2id$v=19$m=65536,t=4,p=1$MWVZSm9PZFdjWnhBNzVlSg$/1PvHawr7q6CyhaIsm8X+DqolE2rqEZdgzrIbT74YMQ', 'BT', 'operator', 'actif', 'LK', NULL, '2025-10-08 15:18:56'),
	(6, 'Marie Tshala', 'marie.tshala@safari.cd', '$argon2id$v=19$m=65536,t=4,p=1$MWVZSm9PZFdjWnhBNzVlSg$/1PvHawr7q6CyhaIsm8X+DqolE2rqEZdgzrIbT74YMQ', 'RH', 'viewer', 'actif', 'MT', NULL, '2025-10-08 15:18:56'),
	(7, 'Kyle Masangu', 'admin.all@safari.cd', '$argon2id$v=19$m=65536,t=4,p=1$MWVZSm9PZFdjWnhBNzVlSg$/1PvHawr7q6CyhaIsm8X+DqolE2rqEZdgzrIbT74YMQ', 'PL', 'admin', 'actif', 'KM', '2025-10-23 11:33:01', '2025-10-08 15:28:58'),
	(8, 'Testeur 1', 'testeur1.pl@safari.com', '$2y$10$OYsD.ULr8jq.PZQyf1ocR.jkAuDQ2t.Utru52aSkWWsj/wzvtwxPe', 'PL', 'viewer', 'inactif', 'T1', NULL, '2025-10-13 22:06:15');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
