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

-- Les données exportées n'étaient pas sélectionnées.

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table safari_smart_mobility. arrets
CREATE TABLE IF NOT EXISTS `arrets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trajet_id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `distance_avec_debut` int NOT NULL,
  `temps_arret` int DEFAULT '0',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

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
  `ligne_affectee` varchar(100) DEFAULT '',
  `statut` enum('actif','maintenance','panne','inactif') DEFAULT 'actif',
  `modules` text,
  `notes` text,
  `derniere_activite` datetime DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero` (`numero`),
  UNIQUE KEY `immatriculation` (`immatriculation`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table safari_smart_mobility. equipe_bord
CREATE TABLE IF NOT EXISTS `equipe_bord` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `poste` enum('chauffeur','controleur','receveur') NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `adresse` text,
  `bus_affecte` varchar(20) DEFAULT NULL,
  `statut` enum('actif','conge','inactif') DEFAULT 'actif',
  `date_embauche` date DEFAULT NULL,
  `notes` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table safari_smart_mobility. modules
CREATE TABLE IF NOT EXISTS `modules` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `description` text COLLATE utf8mb4_general_ci,
  `departement` enum('PL','BT','RH') COLLATE utf8mb4_general_ci NOT NULL,
  `icone` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `route` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `ordre` int DEFAULT '0',
  `actif` tinyint(1) DEFAULT '1',
  `date_creation` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table safari_smart_mobility. points_chifte
CREATE TABLE IF NOT EXISTS `points_chifte` (
  `id` int NOT NULL AUTO_INCREMENT,
  `trajet_id` int NOT NULL,
  `nom` varchar(100) NOT NULL,
  `distance_avec_debut` decimal(10,2) DEFAULT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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
  `statut` enum('planifie','actif','termine','annule') DEFAULT 'planifie',
  `notes` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table safari_smart_mobility. trajets
CREATE TABLE IF NOT EXISTS `trajets` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `distance_totale` decimal(10,2) DEFAULT NULL,
  `statut` enum('actif','inactif') DEFAULT 'actif',
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

-- Listage de la structure de table safari_smart_mobility. trajets_effectues
CREATE TABLE IF NOT EXISTS `trajets_effectues` (
  `id` int NOT NULL AUTO_INCREMENT,
  `shift_id` int NOT NULL,
  `bus_id` int NOT NULL,
  `trajet_id` int NOT NULL,
  `heure_depart` datetime DEFAULT NULL,
  `heure_arrivee` datetime DEFAULT NULL,
  `nombre_passagers` int DEFAULT '0',
  `revenus` decimal(10,2) DEFAULT '0.00',
  `distance_parcourue` decimal(10,2) DEFAULT NULL,
  `carburant_consomme` decimal(10,2) DEFAULT NULL,
  `incidents` text,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

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

-- Les données exportées n'étaient pas sélectionnées.

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
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Les données exportées n'étaient pas sélectionnées.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
