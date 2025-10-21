-- Insertion d'alertes de test pour la démonstration

INSERT INTO `alertes` (`type_alerte`, `titre`, `message`, `bus_id`, `membre_id`, `statut`, `priorite`, `localisation`, `date_alerte`) VALUES
('critical', 'Bus #421 - Panne mécanique', 'Problème moteur détecté. Bus en panne sur la Ligne 2. Dépanneuse en route. Passagers transférés.', 1, NULL, 'nouveau', 'haute', 'Route de Matadi', NOW() - INTERVAL 45 MINUTE),
('critical', 'Bus #208 - Accident signalé', 'Accident mineur signalé. Bus immobilisé au niveau de Lemba. Aucun blessé. Intervention requise.', 3, NULL, 'nouveau', 'haute', 'Lemba, Kinshasa', NOW() - INTERVAL 15 MINUTE),
('critical', 'Bus #315 - Document expiré', 'Assurance expirée depuis 3 jours. Bus suspendu automatiquement. Renouvellement urgent requis.', 2, NULL, 'en_cours', 'haute', NULL, NOW() - INTERVAL 2 HOUR),
('warning', 'Bus #156 - Contrôle technique à renouveler', 'Le contrôle technique expire dans 7 jours. Planifier le renouvellement avant le 14/10/2025.', 4, NULL, 'nouveau', 'moyenne', NULL, NOW() - INTERVAL 1 DAY),
('warning', 'Bus #512 - Niveau de carburant bas', 'Niveau de carburant à 15%. Ravitaillement recommandé avant le prochain trajet.', 6, NULL, 'nouveau', 'moyenne', NULL, NOW() - INTERVAL 3 HOUR),
('warning', 'Équipe incomplète - Bus #642', 'Pas de receveur affecté pour le shift de demain 06:00-14:00. Affectation urgente requise.', 10, NULL, 'nouveau', 'haute', NULL, NOW() - INTERVAL 6 HOUR),
('info', 'Bus #238 - Autorisé à reprendre le service', 'Réparations terminées. Contrôle qualité validé. Le bus est autorisé à reprendre le service.', 7, NULL, 'en_cours', 'basse', NULL, NOW() - INTERVAL 5 HOUR),
('info', 'Nouveau chauffeur affecté - Bus #310', 'Grace Lumbu a été affectée comme chauffeur principal du Bus #310. Formation complétée avec succès.', 8, NULL, 'resolu', 'basse', NULL, NOW() - INTERVAL 1 DAY),
('info', 'Maintenance préventive programmée - Bus #156', 'Maintenance préventive programmée pour le 10/10/2025. Durée estimée: 4 heures. Bus de remplacement assigné.', 4, NULL, 'nouveau', 'moyenne', NULL, NOW() - INTERVAL 1 DAY),
('success', 'Bus #421 - Réparation terminée', 'La réparation du système de freinage a été complétée avec succès. Le bus est prêt à reprendre le service.', 1, NULL, 'resolu', 'basse', NULL, NOW() - INTERVAL 2 DAY),
('success', 'Formation sécurité complétée', 'Tous les chauffeurs ont complété la formation sécurité obligatoire. Certificats délivrés.', NULL, NULL, 'resolu', 'basse', NULL, NOW() - INTERVAL 3 DAY),
('warning', 'Bus #175 - Pneus à remplacer', 'L\'usure des pneus avant dépasse 80%. Remplacement recommandé dans les 48 heures.', 9, NULL, 'nouveau', 'moyenne', NULL, NOW() - INTERVAL 8 HOUR);
