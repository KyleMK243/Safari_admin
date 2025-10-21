-- Script de vérification des données pour la page BI

-- ========================================
-- 1. VÉRIFIER LES TABLES
-- ========================================
SHOW TABLES LIKE 'trajets_effectues';
SHOW TABLES LIKE 'billets';
SHOW TABLES LIKE 'bus';
SHOW TABLES LIKE 'trajets';

-- ========================================
-- 2. COMPTER LES DONNÉES
-- ========================================
SELECT 'Bus actifs' as Type, COUNT(*) as Total FROM bus WHERE statut = 'actif'
UNION ALL
SELECT 'Trajets effectués (30j)', COUNT(*) FROM trajets_effectues WHERE date_depart >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
UNION ALL
SELECT 'Billets vendus (30j)', COUNT(*) FROM billets WHERE date_achat >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
UNION ALL
SELECT 'Trajets (routes)', COUNT(*) FROM trajets;

-- ========================================
-- 3. VÉRIFIER LES TRAJETS EFFECTUÉS
-- ========================================
SELECT 
    DATE(date_depart) as date,
    COUNT(*) as total_trajets,
    SUM(nombre_passagers) as total_passagers
FROM trajets_effectues
WHERE date_depart >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
GROUP BY DATE(date_depart)
ORDER BY date DESC
LIMIT 10;

-- ========================================
-- 4. VÉRIFIER LES BILLETS
-- ========================================
SELECT 
    DATE(date_achat) as date,
    COUNT(*) as total_billets,
    SUM(prix_paye) as revenus
FROM billets
WHERE date_achat >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
AND statut_billet IN ('paye', 'utilise')
GROUP BY DATE(date_achat)
ORDER BY date DESC
LIMIT 10;

-- ========================================
-- 5. VÉRIFIER LES BUS
-- ========================================
SELECT 
    numero,
    immatriculation,
    capacite,
    statut
FROM bus
ORDER BY statut, numero
LIMIT 10;

-- ========================================
-- 6. VÉRIFIER LES TRAJETS (ROUTES)
-- ========================================
SELECT 
    id,
    nom,
    ligne_numero,
    ville_depart,
    ville_arrivee
FROM trajets
LIMIT 10;

-- ========================================
-- 7. TEST DE LA REQUÊTE KPI - BUS ACTIFS
-- ========================================
SELECT COUNT(*) as bus_actifs FROM bus WHERE statut = 'actif';

-- ========================================
-- 8. TEST DE LA REQUÊTE KPI - TRAJETS (30j)
-- ========================================
SELECT COUNT(*) as trajets_effectues 
FROM trajets_effectues 
WHERE DATE(date_depart) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE();

-- ========================================
-- 9. TEST DE LA REQUÊTE KPI - PASSAGERS (30j)
-- ========================================
SELECT COUNT(*) as passagers 
FROM billets 
WHERE DATE(date_achat) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()
AND statut_billet IN ('paye', 'utilise');

-- ========================================
-- 10. TEST DE LA REQUÊTE KPI - REVENUS (30j)
-- ========================================
SELECT COALESCE(SUM(prix_paye), 0) as revenus 
FROM billets 
WHERE DATE(date_achat) BETWEEN DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND CURDATE()
AND statut_billet IN ('paye', 'utilise');

-- ========================================
-- 11. RÉSUMÉ GLOBAL
-- ========================================
SELECT 
    'RÉSUMÉ DES DONNÉES' as Info,
    (SELECT COUNT(*) FROM bus WHERE statut = 'actif') as Bus_Actifs,
    (SELECT COUNT(*) FROM trajets_effectues WHERE date_depart >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)) as Trajets_30j,
    (SELECT COUNT(*) FROM billets WHERE date_achat >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)) as Billets_30j,
    (SELECT COALESCE(SUM(prix_paye), 0) FROM billets WHERE date_achat >= DATE_SUB(CURDATE(), INTERVAL 30 DAY) AND statut_billet IN ('paye', 'utilise')) as Revenus_30j;

-- ========================================
-- SI TOUTES LES VALEURS SONT À 0, EXÉCUTEZ :
-- ========================================
-- SOURCE c:/laragon/www/SafariSmartMobily/database/insert_trajets_effectues_test.sql;
-- SOURCE c:/laragon/www/SafariSmartMobily/database/insert_billets_test.sql;
