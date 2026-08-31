-- =============================================
-- CADO.ME - MIGRATION FONDATION
-- Phase 1: Tables fondamentales
-- =============================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------
-- Table: utilisateurs
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `utilisateurs` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `prenom` VARCHAR(100) NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `identifiant` VARCHAR(100) NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `mot_de_passe` VARCHAR(255) NOT NULL,
    `avatar` VARCHAR(500) DEFAULT NULL,
    `biographie` TEXT DEFAULT NULL,
    `role_plateforme` ENUM('aucun', 'super_administrateur', 'support') NOT NULL DEFAULT 'aucun',
    `statut` ENUM('actif', 'inactif', 'suspendu') NOT NULL DEFAULT 'actif',
    `email_verifie` TINYINT(1) NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL,
    UNIQUE KEY `uk_utilisateurs_email` (`email`),
    UNIQUE KEY `uk_utilisateurs_identifiant` (`identifiant`),
    KEY `idx_utilisateurs_statut` (`statut`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: communautes
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `communautes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `proprietaire_id` INT UNSIGNED NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `slug` VARCHAR(120) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `logo` VARCHAR(500) DEFAULT NULL,
    `image_couverture` VARCHAR(500) DEFAULT NULL,
    `couleur_principale` VARCHAR(7) DEFAULT '#7830E0',
    `couleur_secondaire` VARCHAR(7) DEFAULT NULL,
    `statut` ENUM('active', 'suspendue', 'archivee') NOT NULL DEFAULT 'active',
    `visibilite` ENUM('publique', 'privee') NOT NULL DEFAULT 'privee',
    `parametres` JSON DEFAULT NULL,
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL,
    UNIQUE KEY `uk_communautes_slug` (`slug`),
    KEY `idx_communautes_proprietaire` (`proprietaire_id`),
    KEY `idx_communautes_statut` (`statut`),
    KEY `idx_communautes_visibilite` (`visibilite`),
    CONSTRAINT `fk_communautes_proprietaire` FOREIGN KEY (`proprietaire_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: membres_communautes
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `membres_communautes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `role` ENUM('membre', 'moderateur', 'administrateur', 'proprietaire') NOT NULL DEFAULT 'membre',
    `statut` ENUM('actif', 'inactif', 'suspendu') NOT NULL DEFAULT 'actif',
    `date_adhesion` DATETIME NOT NULL,
    `date_derniere_activite` DATETIME DEFAULT NULL,
    `date_modification` DATETIME NOT NULL,
    UNIQUE KEY `uk_membres_communaute_utilisateur` (`communaute_id`, `utilisateur_id`),
    KEY `idx_membres_communaute` (`communaute_id`),
    KEY `idx_membres_utilisateur` (`utilisateur_id`),
    CONSTRAINT `fk_membres_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_membres_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: publications
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `publications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `contenu` TEXT DEFAULT NULL,
    `type` ENUM('texte', 'image', 'video', 'fichier', 'lien', 'sondage') NOT NULL DEFAULT 'texte',
    `statut` ENUM('active', 'brouillon', 'supprimee', 'masquee') NOT NULL DEFAULT 'active',
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL,
    KEY `idx_publications_communaute` (`communaute_id`),
    KEY `idx_publications_utilisateur` (`utilisateur_id`),
    KEY `idx_publications_communaute_date` (`communaute_id`, `date_creation`),
    KEY `idx_publications_communaute_statut` (`communaute_id`, `statut`),
    CONSTRAINT `fk_publications_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_publications_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: medias_publications
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `medias_publications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `publication_id` INT UNSIGNED NOT NULL,
    `type` ENUM('image', 'video', 'fichier', 'audio') NOT NULL,
    `nom_fichier` VARCHAR(255) NOT NULL,
    `nom_stockage` VARCHAR(255) NOT NULL,
    `chemin` VARCHAR(500) NOT NULL,
    `url` VARCHAR(500) DEFAULT NULL,
    `mime_type` VARCHAR(100) NOT NULL,
    `taille` INT UNSIGNED NOT NULL DEFAULT 0,
    `largeur` INT UNSIGNED DEFAULT NULL,
    `hauteur` INT UNSIGNED DEFAULT NULL,
    `ordre` INT NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL,
    KEY `idx_medias_publication` (`publication_id`),
    CONSTRAINT `fk_medias_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_medias_publication` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: commentaires
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `commentaires` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `publication_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `commentaire_parent_id` INT UNSIGNED DEFAULT NULL,
    `contenu` TEXT NOT NULL,
    `statut` ENUM('actif', 'supprime', 'masque') NOT NULL DEFAULT 'actif',
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL,
    KEY `idx_commentaires_communaute` (`communaute_id`),
    KEY `idx_commentaires_publication` (`publication_id`),
    KEY `idx_commentaires_parent` (`commentaire_parent_id`),
    CONSTRAINT `fk_commentaires_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_commentaires_publication` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_commentaires_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: likes_publications
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `likes_publications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `publication_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `date_creation` DATETIME NOT NULL,
    UNIQUE KEY `uk_likes` (`communaute_id`, `publication_id`, `utilisateur_id`),
    CONSTRAINT `fk_likes_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_likes_publication` FOREIGN KEY (`publication_id`) REFERENCES `publications` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_likes_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: formations
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `formations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `titre` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `statut` ENUM('brouillon', 'active', 'supprimee') NOT NULL DEFAULT 'brouillon',
    `ordre` INT NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL,
    UNIQUE KEY `uk_formations_communaute_slug` (`communaute_id`, `slug`),
    KEY `idx_formations_communaute` (`communaute_id`),
    CONSTRAINT `fk_formations_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: lecons
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `lecons` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `formation_id` INT UNSIGNED NOT NULL,
    `titre` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `contenu` LONGTEXT DEFAULT NULL,
    `video_url` VARCHAR(500) DEFAULT NULL,
    `ordre` INT NOT NULL DEFAULT 0,
    `statut` ENUM('active', 'brouillon', 'supprimee') NOT NULL DEFAULT 'active',
    `date_creation` DATETIME NOT NULL,
    KEY `idx_lecons_formation` (`formation_id`),
    KEY `idx_lecons_communaute` (`communaute_id`),
    CONSTRAINT `fk_lecons_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_lecons_formation` FOREIGN KEY (`formation_id`) REFERENCES `formations` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: progression_formations
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `progression_formations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `lecon_id` INT UNSIGNED NOT NULL,
    `terminee` TINYINT(1) NOT NULL DEFAULT 0,
    `date_completion` DATETIME DEFAULT NULL,
    `date_creation` DATETIME NOT NULL,
    UNIQUE KEY `uk_progression` (`communaute_id`, `utilisateur_id`, `lecon_id`),
    CONSTRAINT `fk_progression_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_progression_lecon` FOREIGN KEY (`lecon_id`) REFERENCES `lecons` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: ressources
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `ressources` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `titre` VARCHAR(200) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `type` ENUM('fichier', 'lien', 'document', 'image', 'video') NOT NULL DEFAULT 'fichier',
    `chemin` VARCHAR(500) DEFAULT NULL,
    `url` VARCHAR(500) DEFAULT NULL,
    `nom_fichier` VARCHAR(255) DEFAULT NULL,
    `statut` ENUM('active', 'supprimee') NOT NULL DEFAULT 'active',
    `ordre` INT NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL,
    KEY `idx_ressources_communaute` (`communaute_id`),
    CONSTRAINT `fk_ressources_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: evenements
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `evenements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `titre` VARCHAR(200) NOT NULL,
    `slug` VARCHAR(220) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `date_debut` DATETIME NOT NULL,
    `date_fin` DATETIME DEFAULT NULL,
    `type` ENUM('webinaire', 'meetup', 'atelier', 'autre') NOT NULL DEFAULT 'autre',
    `lien` VARCHAR(500) DEFAULT NULL,
    `image` VARCHAR(500) DEFAULT NULL,
    `statut` ENUM('active', 'annulee', 'terminee') NOT NULL DEFAULT 'active',
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL,
    KEY `idx_evenements_communaute` (`communaute_id`),
    KEY `idx_evenements_communaute_date` (`communaute_id`, `date_debut`),
    CONSTRAINT `fk_evenements_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: notifications
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `notifications` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `type` VARCHAR(50) NOT NULL,
    `titre` VARCHAR(200) NOT NULL,
    `message` TEXT DEFAULT NULL,
    `lien` VARCHAR(500) DEFAULT NULL,
    `lue` TINYINT(1) NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL,
    KEY `idx_notifications_communaute_utilisateur` (`communaute_id`, `utilisateur_id`),
    KEY `idx_notifications_utilisateur_lue` (`utilisateur_id`, `lue`),
    CONSTRAINT `fk_notifications_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_notifications_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: conversations
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `conversations` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL,
    KEY `idx_conversations_communaute` (`communaute_id`),
    CONSTRAINT `fk_conversations_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: participants_conversations
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `participants_conversations` (
    `conversation_id` INT UNSIGNED NOT NULL,
    `communaute_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `date_creation` DATETIME NOT NULL,
    PRIMARY KEY (`conversation_id`, `utilisateur_id`),
    CONSTRAINT `fk_participants_conversation` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_participants_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: messages
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `messages` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `conversation_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `contenu` TEXT NOT NULL,
    `lu` TINYINT(1) NOT NULL DEFAULT 0,
    `date_creation` DATETIME NOT NULL,
    KEY `idx_messages_conversation` (`conversation_id`),
    KEY `idx_messages_communaute` (`communaute_id`),
    CONSTRAINT `fk_messages_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_messages_conversation` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_messages_utilisateur` FOREIGN KEY (`utilisateur_id`) REFERENCES `utilisateurs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: signalements
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `signalements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `utilisateur_id` INT UNSIGNED NOT NULL,
    `publication_id` INT UNSIGNED DEFAULT NULL,
    `commentaire_id` INT UNSIGNED DEFAULT NULL,
    `motif` VARCHAR(500) NOT NULL,
    `statut` ENUM('en_attente', 'traite', 'rejete') NOT NULL DEFAULT 'en_attente',
    `date_creation` DATETIME NOT NULL,
    KEY `idx_signalements_communaute` (`communaute_id`),
    CONSTRAINT `fk_signalements_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: invitations_communautes
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `invitations_communautes` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `email` VARCHAR(255) NOT NULL,
    `token` VARCHAR(64) NOT NULL,
    `role` ENUM('membre', 'moderateur', 'administrateur') NOT NULL DEFAULT 'membre',
    `expire_le` DATETIME NOT NULL,
    `acceptee` TINYINT(1) DEFAULT NULL,
    `date_creation` DATETIME NOT NULL,
    UNIQUE KEY `uk_invitations_token` (`token`),
    KEY `idx_invitations_communaute` (`communaute_id`),
    CONSTRAINT `fk_invitations_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: plans
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `plans` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nom` VARCHAR(100) NOT NULL,
    `description` TEXT DEFAULT NULL,
    `prix_mensuel` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `prix_annuel` DECIMAL(8,2) NOT NULL DEFAULT 0.00,
    `limite_membres` INT UNSIGNED NOT NULL DEFAULT 50,
    `limite_stockage` BIGINT UNSIGNED NOT NULL DEFAULT 1073741824,
    `limite_formations` INT UNSIGNED NOT NULL DEFAULT 3,
    `limite_communautes` INT UNSIGNED NOT NULL DEFAULT 1,
    `actif` TINYINT(1) NOT NULL DEFAULT 1,
    `date_creation` DATETIME NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: abonnements
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `abonnements` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED NOT NULL,
    `plan_id` INT UNSIGNED NOT NULL,
    `statut` ENUM('actif', 'inactif', 'suspendu', 'annule') NOT NULL DEFAULT 'actif',
    `periode_debut` DATE NOT NULL,
    `periode_fin` DATE NOT NULL,
    `fournisseur` VARCHAR(50) DEFAULT NULL,
    `identifiant_externe` VARCHAR(255) DEFAULT NULL,
    `date_creation` DATETIME NOT NULL,
    `date_modification` DATETIME NOT NULL,
    KEY `idx_abonnements_communaute` (`communaute_id`),
    CONSTRAINT `fk_abonnements_communaute` FOREIGN KEY (`communaute_id`) REFERENCES `communautes` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_abonnements_plan` FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Table: journaux_audit
-- -------------------------------------------
CREATE TABLE IF NOT EXISTS `journaux_audit` (
    `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `communaute_id` INT UNSIGNED DEFAULT NULL,
    `utilisateur_id` INT UNSIGNED DEFAULT NULL,
    `action` VARCHAR(100) NOT NULL,
    `entite` VARCHAR(100) NOT NULL,
    `entite_id` INT UNSIGNED DEFAULT NULL,
    `donnees` JSON DEFAULT NULL,
    `adresse_ip` VARCHAR(45) DEFAULT NULL,
    `date_creation` DATETIME NOT NULL,
    KEY `idx_audit_communaute` (`communaute_id`),
    KEY `idx_audit_utilisateur` (`utilisateur_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------
-- Données initiales
-- -------------------------------------------

-- Plan gratuit par défaut
INSERT INTO `plans` (`nom`, `description`, `prix_mensuel`, `prix_annuel`, `limite_membres`, `limite_stockage`, `limite_formations`, `limite_communautes`, `actif`, `date_creation`)
VALUES ('Gratuit', 'Parfait pour commencer', 0.00, 0.00, 50, 1073741824, 3, 1, 1, NOW());

-- Super administrateur par défaut
INSERT INTO `utilisateurs` (`prenom`, `nom`, `identifiant`, `email`, `mot_de_passe`, `role_plateforme`, `statut`, `email_verifie`, `date_creation`, `date_modification`)
VALUES ('Admin', 'Cado.me', 'admin', 'admin@cado.me', '$2y$12$j5XCDcL7YlMhBgacKYTjkunQzZX0W69AhrepB9hYfMyfiKgL9Nbbi', 'super_administrateur', 'actif', 1, NOW(), NOW());

SET FOREIGN_KEY_CHECKS = 1;
