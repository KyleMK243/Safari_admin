-- Table des permissions disponibles
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` varchar(100) NOT NULL UNIQUE,
  `nom` varchar(200) NOT NULL,
  `description` text,
  `categorie` varchar(100) NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Table de liaison entre rôles et permissions
CREATE TABLE IF NOT EXISTS `role_permissions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `role` enum('admin','supervisor','operator','viewer') NOT NULL,
  `permission_id` int NOT NULL,
  `date_attribution` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permission_unique` (`role`, `permission_id`),
  FOREIGN KEY (`permission_id`) REFERENCES `permissions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertion des permissions par défaut
INSERT INTO `permissions` (`code`, `nom`, `description`, `categorie`) VALUES
-- Dashboard
('dashboard.view', 'Voir le tableau de bord', 'Accès au tableau de bord principal', 'Dashboard'),
('dashboard.stats', 'Voir les statistiques', 'Accès aux statistiques détaillées', 'Dashboard'),

-- Bus
('bus.view', 'Voir les bus', 'Consulter la liste des bus', 'Bus'),
('bus.create', 'Créer des bus', 'Ajouter de nouveaux bus', 'Bus'),
('bus.edit', 'Modifier les bus', 'Modifier les informations des bus', 'Bus'),
('bus.delete', 'Supprimer les bus', 'Supprimer des bus du système', 'Bus'),
('bus.assign', 'Affecter des bus', 'Affecter des bus aux trajets', 'Bus'),

-- Trajets
('trajets.view', 'Voir les trajets', 'Consulter la liste des trajets', 'Trajets'),
('trajets.create', 'Créer des trajets', 'Ajouter de nouveaux trajets', 'Trajets'),
('trajets.edit', 'Modifier les trajets', 'Modifier les informations des trajets', 'Trajets'),
('trajets.delete', 'Supprimer les trajets', 'Supprimer des trajets', 'Trajets'),

-- Équipe
('equipe.view', 'Voir l\'équipe', 'Consulter la liste des membres', 'Équipe'),
('equipe.create', 'Ajouter des membres', 'Ajouter de nouveaux membres', 'Équipe'),
('equipe.edit', 'Modifier les membres', 'Modifier les informations des membres', 'Équipe'),
('equipe.delete', 'Supprimer des membres', 'Supprimer des membres', 'Équipe'),
('equipe.assign', 'Affecter l\'équipe', 'Affecter des membres aux shifts', 'Équipe'),

-- Billets
('billets.view', 'Voir les billets', 'Consulter les billets vendus', 'Billets'),
('billets.create', 'Vendre des billets', 'Créer de nouvelles ventes', 'Billets'),
('billets.cancel', 'Annuler des billets', 'Annuler des billets vendus', 'Billets'),
('billets.stats', 'Statistiques billets', 'Voir les statistiques de vente', 'Billets'),

-- Alertes
('alertes.view', 'Voir les alertes', 'Consulter les alertes', 'Alertes'),
('alertes.create', 'Créer des alertes', 'Créer de nouvelles alertes', 'Alertes'),
('alertes.resolve', 'Résoudre les alertes', 'Marquer les alertes comme résolues', 'Alertes'),
('alertes.delete', 'Supprimer les alertes', 'Supprimer des alertes', 'Alertes'),

-- Rapports
('rapports.view', 'Voir les rapports', 'Accès aux rapports', 'Rapports'),
('rapports.export', 'Exporter les rapports', 'Exporter les rapports en PDF/Excel', 'Rapports'),

-- Utilisateurs
('users.view', 'Voir les utilisateurs', 'Consulter la liste des utilisateurs', 'Utilisateurs'),
('users.create', 'Créer des utilisateurs', 'Ajouter de nouveaux utilisateurs', 'Utilisateurs'),
('users.edit', 'Modifier les utilisateurs', 'Modifier les informations des utilisateurs', 'Utilisateurs'),
('users.delete', 'Supprimer des utilisateurs', 'Supprimer des utilisateurs', 'Utilisateurs'),
('users.permissions', 'Gérer les permissions', 'Modifier les permissions des rôles', 'Utilisateurs'),

-- Paramètres
('settings.view', 'Voir les paramètres', 'Accès aux paramètres', 'Paramètres'),
('settings.edit', 'Modifier les paramètres', 'Modifier la configuration système', 'Paramètres');

-- Attribution des permissions par défaut pour chaque rôle
-- Admin : Toutes les permissions
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'admin', id FROM `permissions`;

-- Supervisor : Toutes sauf gestion utilisateurs et paramètres
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'supervisor', id FROM `permissions`
WHERE `code` NOT IN ('users.create', 'users.delete', 'users.permissions', 'settings.edit');

-- Operator : Opérations courantes
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'operator', id FROM `permissions`
WHERE `code` IN (
  'dashboard.view', 'dashboard.stats',
  'bus.view', 'bus.edit', 'bus.assign',
  'trajets.view',
  'equipe.view', 'equipe.assign',
  'billets.view', 'billets.create', 'billets.stats',
  'alertes.view', 'alertes.create', 'alertes.resolve'
);

-- Viewer : Lecture seule
INSERT INTO `role_permissions` (`role`, `permission_id`)
SELECT 'viewer', id FROM `permissions`
WHERE `code` IN (
  'dashboard.view',
  'bus.view',
  'trajets.view',
  'equipe.view',
  'billets.view',
  'alertes.view',
  'rapports.view'
);
