CREATE TABLE IF NOT EXISTS `parametres_plateforme` (
    `id` INT UNSIGNED PRIMARY KEY DEFAULT 1,
    `nom_plateforme` VARCHAR(100) NOT NULL DEFAULT 'Cado.me',
    `description_plateforme` TEXT DEFAULT NULL,
    `email_contact` VARCHAR(255) DEFAULT NULL,
    `maintenance` TINYINT(1) NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default row if not exists
INSERT IGNORE INTO `parametres_plateforme` (`id`, `nom_plateforme`, `description_plateforme`, `email_contact`, `maintenance`, `date_creation`, `date_modification`)
VALUES (1, 'Cado.me', 'Votre plateforme communautaire', '', 0, NOW(), NOW());
