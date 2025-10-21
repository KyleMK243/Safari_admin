-- Insertion de données de test pour la billetterie

-- Insérer quelques clients de test (si la table existe)
INSERT INTO `clients` (`nom`, `prenom`, `telephone`, `email`, `date_creation`) VALUES
('Mukendi', 'Jean', '+243 812 345 678', 'jean.mukendi@email.com', NOW()),
('Lumbu', 'Grace', '+243 823 456 789', 'grace.lumbu@email.com', NOW()),
('Tshala', 'Marie', '+243 834 567 890', 'marie.tshala@email.com', NOW()),
('Kabila', 'Joseph', '+243 845 678 901', 'joseph.kabila@email.com', NOW()),
('Nsimba', 'Paul', '+243 856 789 012', 'paul.nsimba@email.com', NOW())
ON DUPLICATE KEY UPDATE nom=nom;

-- Insérer des billets de test
INSERT INTO `billets` (`numero_billet`, `trajet_id`, `tarif_id`, `client_id`, `arret_depart`, `arret_arrivee`, `date_voyage`, `heure_depart`, `prix_paye`, `statut_billet`, `mode_paiement`, `date_achat`) VALUES
('BT-2025-001234', 1, 1, 1, 'Kinshasa Centre', 'Matadi Port', CURDATE(), '08:00:00', 5000, 'paye', 'mobile_money', NOW() - INTERVAL 2 HOUR),
('BT-2025-001235', 1, 1, 3, 'Kinshasa Centre', 'Matadi Port', CURDATE(), '10:00:00', 5000, 'paye', 'carte_bancaire', NOW() - INTERVAL 1 HOUR),
('BT-2025-001236', 2, 2, 5, 'Kinshasa Centre', 'Kikwit', CURDATE(), '14:00:00', 4000, 'paye', 'especes', NOW() - INTERVAL 30 MINUTE),
('RES-2025-00456', 1, 1, 2, 'Kinshasa Centre', 'Matadi Port', CURDATE() + INTERVAL 1 DAY, '06:00:00', 5000, 'reserve', 'mobile_money', NOW() - INTERVAL 3 HOUR),
('RES-2025-00457', 3, 3, 4, 'Kinshasa Centre', 'Lubumbashi', CURDATE() + INTERVAL 1 DAY, '08:00:00', 7000, 'reserve', 'especes', NOW() - INTERVAL 2 HOUR),
('BT-2025-001237', 1, 1, 1, 'Kinshasa Centre', 'Matadi Port', CURDATE() - INTERVAL 1 DAY, '08:00:00', 5000, 'utilise', 'mobile_money', NOW() - INTERVAL 1 DAY),
('BT-2025-001238', 2, 2, 3, 'Kinshasa Centre', 'Kikwit', CURDATE() - INTERVAL 1 DAY, '10:00:00', 4000, 'utilise', 'especes', NOW() - INTERVAL 1 DAY),
('BT-2025-001239', 1, 1, 2, 'Kinshasa Centre', 'Matadi Port', CURDATE(), '12:00:00', 5000, 'paye', 'mobile_money', NOW() - INTERVAL 45 MINUTE),
('BT-2025-001240', 3, 3, 4, 'Kinshasa Centre', 'Lubumbashi', CURDATE(), '15:00:00', 7000, 'paye', 'carte_bancaire', NOW() - INTERVAL 20 MINUTE),
('BT-2025-001241', 2, 2, 5, 'Kinshasa Centre', 'Kikwit', CURDATE(), '16:00:00', 4000, 'paye', 'especes', NOW() - INTERVAL 10 MINUTE);

-- Insérer des transactions correspondantes
INSERT INTO `transactions_billeterie` (`type_transaction`, `billet_id`, `montant`, `devise`, `mode_paiement`, `statut_transaction`, `date_transaction`) VALUES
('vente', 1, 5000, 'CDF', 'mobile_money', 'reussie', NOW() - INTERVAL 2 HOUR),
('vente', 2, 5000, 'CDF', 'carte_bancaire', 'reussie', NOW() - INTERVAL 1 HOUR),
('vente', 3, 4000, 'CDF', 'especes', 'reussie', NOW() - INTERVAL 30 MINUTE),
('reservation', 4, 5000, 'CDF', 'mobile_money', 'en_attente', NOW() - INTERVAL 3 HOUR),
('reservation', 5, 7000, 'CDF', 'especes', 'en_attente', NOW() - INTERVAL 2 HOUR),
('vente', 6, 5000, 'CDF', 'mobile_money', 'reussie', NOW() - INTERVAL 1 DAY),
('vente', 7, 4000, 'CDF', 'especes', 'reussie', NOW() - INTERVAL 1 DAY),
('vente', 8, 5000, 'CDF', 'mobile_money', 'reussie', NOW() - INTERVAL 45 MINUTE),
('vente', 9, 7000, 'CDF', 'carte_bancaire', 'reussie', NOW() - INTERVAL 20 MINUTE),
('vente', 10, 4000, 'CDF', 'especes', 'reussie', NOW() - INTERVAL 10 MINUTE);
