-- Vérifier si la table alertes_historique existe
SHOW TABLES LIKE 'alertes_historique';

-- Afficher la structure
DESCRIBE alertes_historique;

-- Compter les enregistrements
SELECT COUNT(*) as total_historique FROM alertes_historique;

-- Afficher les 10 derniers traitements avec détails
SELECT 
    h.id,
    h.alerte_id,
    a.titre as alerte_titre,
    h.action,
    h.type_traitement,
    h.solution,
    h.raison,
    h.commentaire,
    h.traite_par_nom,
    h.date_action
FROM alertes_historique h
LEFT JOIN alertes a ON h.alerte_id = a.id
ORDER BY h.date_action DESC
LIMIT 10;
