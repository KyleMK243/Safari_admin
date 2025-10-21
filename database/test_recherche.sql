-- Test pour vérifier si la recherche fonctionne

-- 1. Vérifier la structure de la table
DESCRIBE equipe_bord;

-- 2. Vérifier si la colonne matricule existe
SELECT COUNT(*) as has_matricule 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'safari_smart_mobility' 
  AND TABLE_NAME = 'equipe_bord' 
  AND COLUMN_NAME = 'matricule';

-- 3. Tester une recherche simple
SELECT id, nom, matricule, poste, telephone, email, statut 
FROM equipe_bord 
WHERE nom LIKE '%Jean%' 
   OR matricule LIKE '%Jean%' 
   OR email LIKE '%Jean%'
LIMIT 5;

-- 4. Vérifier les données existantes
SELECT id, nom, matricule, poste, statut 
FROM equipe_bord 
ORDER BY date_creation DESC 
LIMIT 10;
