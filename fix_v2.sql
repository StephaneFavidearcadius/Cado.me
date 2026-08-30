-- Correction : remplacer &#039; (apostrophe encodée) par '
UPDATE formations SET titre = REPLACE(titre, '&#039;', '''') WHERE titre LIKE '%&#039;%';
UPDATE formations SET description = REPLACE(description, '&#039;', '''') WHERE description LIKE '%&#039;%';

UPDATE modules_formation SET titre = REPLACE(titre, '&#039;', '''') WHERE titre LIKE '%&#039;%';
UPDATE modules_formation SET description = REPLACE(description, '&#039;', '''') WHERE description LIKE '%&#039;%';

UPDATE lecons SET titre = REPLACE(titre, '&#039;', '''') WHERE titre LIKE '%&#039;%';
UPDATE lecons SET description = REPLACE(description, '&#039;', '''') WHERE description LIKE '%&#039;%';
UPDATE lecons SET contenu = REPLACE(contenu, '&#039;', '''') WHERE contenu LIKE '%&#039;%';

UPDATE publications SET contenu = REPLACE(contenu, '&#039;', '''') WHERE contenu LIKE '%&#039;%';
UPDATE commentaires SET contenu = REPLACE(contenu, '&#039;', '''') WHERE contenu LIKE '%&#039;%';
UPDATE messages SET contenu = REPLACE(contenu, '&#039;', '''') WHERE contenu LIKE '%&#039;%';

UPDATE communautes SET nom = REPLACE(nom, '&#039;', '''') WHERE nom LIKE '%&#039;%';
UPDATE communautes SET description = REPLACE(description, '&#039;', '''') WHERE description LIKE '%&#039;%';

UPDATE utilisateurs SET prenom = REPLACE(prenom, '&#039;', '''') WHERE prenom LIKE '%&#039;%';
UPDATE utilisateurs SET nom = REPLACE(nom, '&#039;', '''') WHERE nom LIKE '%&#039;%';

-- Aussi corriger les autres entités HTML encodées
UPDATE formations SET titre = REPLACE(titre, '&amp;', '&') WHERE titre LIKE '%&amp;%';
UPDATE formations SET description = REPLACE(description, '&amp;', '&') WHERE description LIKE '%&amp;%';
UPDATE modules_formation SET titre = REPLACE(titre, '&amp;', '&') WHERE titre LIKE '%&amp;%';
UPDATE modules_formation SET description = REPLACE(description, '&amp;', '&') WHERE description LIKE '%&amp;%';
UPDATE publications SET contenu = REPLACE(contenu, '&amp;', '&') WHERE contenu LIKE '%&amp;%';
UPDATE commentaires SET contenu = REPLACE(contenu, '&amp;', '&') WHERE contenu LIKE '%&amp;%';
UPDATE messages SET contenu = REPLACE(contenu, '&amp;', '&') WHERE contenu LIKE '%&amp;%';
UPDATE communautes SET nom = REPLACE(nom, '&amp;', '&') WHERE nom LIKE '%&amp;%';
UPDATE communautes SET description = REPLACE(description, '&amp;', '&') WHERE description LIKE '%&amp;%';
UPDATE utilisateurs SET prenom = REPLACE(prenom, '&amp;', '&') WHERE prenom LIKE '%&amp;%';
UPDATE utilisateurs SET nom = REPLACE(nom, '&amp;', '&') WHERE nom LIKE '%&amp;%';
