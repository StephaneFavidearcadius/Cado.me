-- =============================================
-- CADO.ME - MIGRATION 003
-- Tables et colonnes manquantes
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------
-- Table: modules_formation
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `modules_formation` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `formation_id` INT UNSIGNED NOT NULL,
    `titre` VARCHAR(200) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `ordre` INT NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL,
    KEY `idx_modules_formation` (`formation_id`),
    CONSTRAINT `fk_modules_formation` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: favoris_publications
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `favoris_publications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `publication_id` INT UNSIGNED NOT NULL,
    `date_creation` DATETIME NOT NULL,
    UNIQUE KEY `uk_favoris` (`utilisateur_id`, `publication_id`),
    KEY `idx_favoris_utilisateur` (`utilisateur_id`),
    CONSTRAINT `fk_favoris_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_favoris_publication` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: medias_messages
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `medias_messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `message_id` INT UNSIGNED NOT NULL,
    `communaute_id` INT UNSIGNED NOT NULL,
    `type_media` ENUM('image', 'video', 'fichier', 'audio') NOT NULL DEFAULT 'fichier',
    `nom_original` VARCHAR(255) NOT NULL,
    `chemin` VARCHAR(500) NOT NULL,
    `taille` INT UNSIGNED NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL,
    KEY `idx_medias_messages_message` (`message_id`),
    CONSTRAINT `fk_medias_messages_message` FOREIGN KEY (`message_id`) REFERENCES `messages` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_medias_messages_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Colonnes manquantes : utilisateurs (whatsapp, reset_token, reset_expires, email_token)
-- -------------------------------------------
ALTER TABLE `utilisateurs`
    ADD COLUMN IF NOT EXISTS `reset_token` VARCHAR(64) DEFAULT NULL AFTER `email_verifie`,
    ADD COLUMN IF NOT EXISTS `reset_expires` DATETIME DEFAULT NULL AFTER `reset_token`,
    ADD COLUMN IF NOT EXISTS `email_token` VARCHAR(64) DEFAULT NULL AFTER `reset_expires`;
ALTER TABLE `utilisateurs`
    ADD COLUMN IF NOT EXISTS `whatsapp` VARCHAR(30) DEFAULT NULL AFTER `biographie`;

-- -------------------------------------------
-- Colonnes manquantes : lecons.module_id, lecons.video_fichier
-- -------------------------------------------
ALTER TABLE `lecons`
    ADD COLUMN IF NOT EXISTS `module_id` INT UNSIGNED DEFAULT NULL AFTER `formation_id`,
    ADD COLUMN IF NOT EXISTS `video_fichier` VARCHAR(500) DEFAULT NULL AFTER `video_url`,
    ADD KEY IF NOT EXISTS `idx_lecons_module` (`module_id`);

-- Ajouter la FK pour module_id si elle n'existe pas
-- (MySQL peut nécessiter un ALTER séparé si la contrainte existe déjà)
-- ALTER TABLE `lecons` ADD CONSTRAINT `fk_lecons_module` FOREIGN KEY (`module_id`) REFERENCES `modules_formation` (`id`) ON DELETE SET NULL;

-- -------------------------------------------
-- Colonne manquante : formations.image_couverture
-- La migration 001 a `image` mais le code utilise `image_couverture`
-- -------------------------------------------
-- On vérifie et on renomme si besoin
SET @col_exists = (SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'formations' AND COLUMN_NAME = 'image_couverture');

SET @sql = IF(@col_exists = 0,
    'ALTER TABLE `formations` ADD COLUMN `image_couverture` VARCHAR(500) DEFAULT NULL AFTER `description`',
    'SELECT 1'
);
PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- -------------------------------------------
-- Table: publications_enregistrees (saves)
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `publications_enregistrees` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `publication_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `date_creation` DATETIME NOT NULL,
    UNIQUE KEY `uk_enregistrees` (`communaute_id`, `publication_id`, `utilisateur_id`),
    CONSTRAINT `fk_enregistrees_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_enregistrees_publication` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_enregistrees_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: rate_limiting (rate limiting basé MySQL)
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `rate_limits` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `cle` VARCHAR(255) NOT NULL,
    `tentatives` INT UNSIGNED NOT NULL DEFAULT 1,
    `date_premiere` DATETIME NOT NULL,
    `date_derniere` DATETIME NOT NULL,
    UNIQUE KEY `uk_rate_limit_cle` (`cle`),
    KEY `idx_rate_limit_date` (`date_premiere`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Index manquants de performance
-- -------------------------------------------
CREATE INDEX IF NOT EXISTS `idx_rate_limits_cleanup` ON `rate_limits` (`date_premiere`);

SET FOREIGN_KEY_CHECKS = 1;
