-- =============================================
-- Script de nettoyage du double encodage HTML
-- Exécuter dans phpMyAdmin ou votre client SQL
-- =============================================

-- Publications
UPDATE publications 
SET contenu = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    contenu, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>')
WHERE contenu LIKE '%&amp;%';

-- Commentaires
UPDATE commentaires 
SET contenu = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    contenu, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>')
WHERE contenu LIKE '%&amp;%';

-- Messages
UPDATE messages 
SET contenu = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    contenu, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>')
WHERE contenu LIKE '%&amp;%';

-- Formations
UPDATE formations 
SET titre = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    titre, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>'),
description = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    description, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>')
WHERE titre LIKE '%&amp;%' OR description LIKE '%&amp;%';

-- Modules Formation
UPDATE modules_formation 
SET titre = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    titre, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>'),
description = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    description, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>')
WHERE titre LIKE '%&amp;%' OR description LIKE '%&amp;%';

-- Leçons
UPDATE lecons 
SET titre = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    titre, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>'),
description = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    description, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>')
WHERE titre LIKE '%&amp;%' OR description LIKE '%&amp;%';

-- Communautés
UPDATE communautes 
SET nom = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    nom, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>'),
description = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    description, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>')
WHERE nom LIKE '%&amp;%' OR description LIKE '%&amp;%';

-- Utilisateurs
UPDATE utilisateurs 
SET prenom = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    prenom, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>'),
nom = REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
    nom, 
    '&amp;#039;', ''''),
    '&amp;quot;', '"'),
    '&amp;amp;', '&'),
    '&amp;lt;', '<'),
    '&amp;gt;', '>')
WHERE prenom LIKE '%&amp;%' OR nom LIKE '%&amp;%';
