-- Correction rapide des formations (titre + description)
UPDATE formations 
SET 
    titre = REPLACE(titre, '&amp;#039;', ''''),
    description = REPLACE(description, '&amp;#039;', '''')
WHERE titre LIKE '%&amp;#039;%' OR description LIKE '%&amp;#039;%';

-- Correction des modules
UPDATE modules_formation 
SET 
    titre = REPLACE(titre, '&amp;#039;', ''''),
    description = REPLACE(description, '&amp;#039;', '''')
WHERE titre LIKE '%&amp;#039;%' OR description LIKE '%&amp;#039;%';

-- Correction des leçons
UPDATE lecons 
SET 
    titre = REPLACE(titre, '&amp;#039;', ''''),
    description = REPLACE(description, '&amp;#039;', ''''),
    contenu = REPLACE(contenu, '&amp;#039;', '''')
WHERE titre LIKE '%&amp;#039;%' OR description LIKE '%&amp;#039;%' OR contenu LIKE '%&amp;#039;%';

-- Correction des publications
UPDATE publications SET contenu = REPLACE(contenu, '&amp;#039;', '''') WHERE contenu LIKE '%&amp;#039;%';

-- Correction des commentaires
UPDATE commentaires SET contenu = REPLACE(contenu, '&amp;#039;', '''') WHERE contenu LIKE '%&amp;#039;%';

-- Correction des messages
UPDATE messages SET contenu = REPLACE(contenu, '&amp;#039;', '''') WHERE contenu LIKE '%&amp;#039;%';

-- Correction des communautés
UPDATE communautes 
SET 
    nom = REPLACE(nom, '&amp;#039;', ''''),
    description = REPLACE(description, '&amp;#039;', '''')
WHERE nom LIKE '%&amp;#039;%' OR description LIKE '%&amp;#039;%';

-- Correction des utilisateurs
UPDATE utilisateurs 
SET 
    prenom = REPLACE(prenom, '&amp;#039;', ''''),
    nom = REPLACE(nom, '&amp;#039;', '''')
WHERE prenom LIKE '%&amp;#039;%' OR nom LIKE '%&amp;#039;%';
