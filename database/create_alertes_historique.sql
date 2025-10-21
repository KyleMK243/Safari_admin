-- Table pour l'historique des traitements d'alertes
CREATE TABLE IF NOT EXISTS `alertes_historique` (
  `id` int NOT NULL AUTO_INCREMENT,
  `alerte_id` int NOT NULL,
  `action` enum('traiter','resoudre','ignorer') NOT NULL,
  `type_traitement` varchar(100) DEFAULT NULL,
  `solution` varchar(100) DEFAULT NULL,
  `raison` varchar(100) DEFAULT NULL,
  `commentaire` text,
  `traite_par` int DEFAULT NULL,
  `date_action` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `alerte_id` (`alerte_id`),
  KEY `traite_par` (`traite_par`),
  CONSTRAINT `fk_alertes_historique_alerte` FOREIGN KEY (`alerte_id`) REFERENCES `alertes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
