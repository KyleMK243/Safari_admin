-- Migration pour ajouter les colonnes manquantes à la table equipe_bord

ALTER TABLE `equipe_bord` 
ADD COLUMN `matricule` VARCHAR(50) UNIQUE NULL AFTER `nom`,
ADD COLUMN `date_naissance` DATE NULL AFTER `adresse`,
ADD COLUMN `type_contrat` ENUM('cdi', 'cdd', 'stage', 'interim') DEFAULT 'cdi' AFTER `date_embauche`,
ADD COLUMN `salaire` DECIMAL(10,2) NULL AFTER `type_contrat`,
ADD COLUMN `photo` VARCHAR(255) NULL AFTER `salaire`;

-- Modifier l'enum poste pour ajouter mécanicien et administratif
ALTER TABLE `equipe_bord` 
MODIFY COLUMN `poste` ENUM('chauffeur', 'controleur', 'receveur', 'mecanicien', 'administratif') NOT NULL;

-- Modifier l'enum statut pour ajouter suspendu
ALTER TABLE `equipe_bord` 
MODIFY COLUMN `statut` ENUM('actif', 'conge', 'suspendu', 'inactif') DEFAULT 'actif';

-- Générer des matricules pour les agents existants
UPDATE `equipe_bord` SET 
  `matricule` = CONCAT('EMP-2025-', LPAD(id, 3, '0'))
WHERE `matricule` IS NULL;
