-- Schema minimal de TEST pour Royal Inventory / Mahavantana.
-- Destination unique: base MySQL/MariaDB `gestion_stock_test`.
-- Ne contient aucune donnee reelle de l'entreprise.
-- Importer manuellement dans phpMyAdmin; ne pas executer sur une base de production.

CREATE DATABASE IF NOT EXISTS `gestion_stock_test`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `gestion_stock_test`;

CREATE TABLE IF NOT EXISTS `user` (
  `Id_User` INT NOT NULL AUTO_INCREMENT,
  `Full_Name` VARCHAR(150) NOT NULL DEFAULT '',
  `Initials` VARCHAR(30) NOT NULL DEFAULT '',
  `Current_Name` VARCHAR(150) NOT NULL DEFAULT '',
  `User_Name` VARCHAR(80) NOT NULL,
  `Departement` VARCHAR(100) NOT NULL DEFAULT '',
  `Sex` VARCHAR(30) NOT NULL DEFAULT '',
  `Password` VARCHAR(255) NOT NULL DEFAULT '',
  `Permission` CHAR(1) NOT NULL DEFAULT 'N',
  `Note` TEXT NULL,
  `img_path` VARCHAR(255) NOT NULL DEFAULT '',
  `point_de_vente` VARCHAR(100) NOT NULL DEFAULT '',
  `numero_commande` INT NOT NULL DEFAULT 0,
  `description_date` VARCHAR(150) NOT NULL DEFAULT '',
  `stock_client_name` VARCHAR(100) NOT NULL DEFAULT '',
  `stock_numero_commande` INT NOT NULL DEFAULT 0,
  `stock_description_date` VARCHAR(150) NOT NULL DEFAULT '',
  `point_de_vente_vente_calc` VARCHAR(100) NOT NULL DEFAULT '',
  `numero_vente_calc` INT NOT NULL DEFAULT 0,
  `description_date_vente_calc` VARCHAR(150) NOT NULL DEFAULT '',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id_User`),
  UNIQUE KEY `uq_user_name` (`User_Name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `shop` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `long_name` VARCHAR(150) NOT NULL DEFAULT '',
  `short_name` VARCHAR(100) NOT NULL,
  `shop_user` VARCHAR(80) NOT NULL DEFAULT '',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_shop_short_name` (`short_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `memo` (
  `id_memo` INT NOT NULL,
  `note_memo` VARCHAR(150) NOT NULL DEFAULT '',
  `valeur_memo` INT NOT NULL DEFAULT 0,
  `description_date` VARCHAR(150) NOT NULL DEFAULT '',
  PRIMARY KEY (`id_memo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `produit` (
  `id_x` INT NOT NULL AUTO_INCREMENT,
  `id_cat` INT NOT NULL DEFAULT 0,
  `id_unite` INT NOT NULL DEFAULT 1,
  `nom_x` VARCHAR(255) NOT NULL,
  `prix_de_vente` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `prix_fournisseur` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `benefice` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `reference_x` VARCHAR(100) NOT NULL,
  `note_x` TEXT NULL,
  `user_x` VARCHAR(80) NOT NULL DEFAULT '',
  `user` VARCHAR(80) NOT NULL DEFAULT '',
  `img_path_x` VARCHAR(255) NOT NULL DEFAULT '',
  `pu_aparafa` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `pu_ambato_tantely` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `pu_soalazaina` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `note_prix` VARCHAR(80) NOT NULL DEFAULT '',
  `note_prix_amparafa` VARCHAR(80) NOT NULL DEFAULT '',
  `note_prix_tantely` VARCHAR(80) NOT NULL DEFAULT '',
  `note_prix_soalazaina` VARCHAR(80) NOT NULL DEFAULT '',
  `difference_prix` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `difference_prix_amparafa` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `difference_prix_tantely` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `difference_prix_soalazaina` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `stock_id` INT NOT NULL DEFAULT 0,
  `date_time_x` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_x`),
  UNIQUE KEY `uq_produit_reference` (`reference_x`),
  KEY `idx_produit_nom` (`nom_x`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `commande` (
  `id_commande` INT NOT NULL AUTO_INCREMENT,
  `valeur_memo` INT NOT NULL DEFAULT 0,
  `numero_commande` INT NOT NULL DEFAULT 0,
  `nom_du_client` VARCHAR(150) NOT NULL DEFAULT '',
  `description_date` VARCHAR(150) NOT NULL DEFAULT '',
  `id_x` INT NOT NULL DEFAULT 0,
  `qt` DECIMAL(15,3) NOT NULL DEFAULT 0,
  `prix_de_vente` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `prix_client` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `prix_aparafa` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `state` VARCHAR(50) NOT NULL DEFAULT '',
  `note_commande` TEXT NULL,
  `user` VARCHAR(80) NOT NULL DEFAULT '',
  `id_stock` INT NOT NULL DEFAULT 0,
  `client_name` VARCHAR(150) NOT NULL DEFAULT '',
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_commande`),
  KEY `idx_commande_user` (`user`),
  KEY `idx_commande_numero` (`numero_commande`),
  KEY `idx_commande_id_x` (`id_x`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `vente_calc` (
  `id_commande` INT NOT NULL AUTO_INCREMENT,
  `numero_commande` INT NOT NULL DEFAULT 0,
  `nom_du_client` VARCHAR(150) NOT NULL DEFAULT '',
  `description_date` VARCHAR(150) NOT NULL DEFAULT '',
  `id_x` INT NOT NULL DEFAULT 0,
  `qt` DECIMAL(15,3) NOT NULL DEFAULT 0,
  `prix_de_vente` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `prix_aparafa` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `state` VARCHAR(50) NOT NULL DEFAULT '',
  `note_commande` TEXT NULL,
  `user` VARCHAR(80) NOT NULL DEFAULT '',
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_commande`),
  KEY `idx_vente_calc_user` (`user`),
  KEY `idx_vente_calc_numero` (`numero_commande`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stock_prep` (
  `id_stock_prep` INT NOT NULL AUTO_INCREMENT,
  `numero_stock_prep` INT NOT NULL DEFAULT 0,
  `nom_du_client` VARCHAR(150) NOT NULL DEFAULT '',
  `description_date` VARCHAR(150) NOT NULL DEFAULT '',
  `id_x` INT NOT NULL DEFAULT 0,
  `qt` DECIMAL(15,3) NOT NULL DEFAULT 0,
  `prix_de_vente` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `prix_client` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `state` VARCHAR(50) NOT NULL DEFAULT '',
  `note` TEXT NULL,
  `user_stock_prep` VARCHAR(80) NOT NULL DEFAULT '',
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_stock_prep`),
  KEY `idx_stock_prep_user` (`user_stock_prep`),
  KEY `idx_stock_prep_numero` (`numero_stock_prep`),
  KEY `idx_stock_prep_id_x` (`id_x`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `stock_prep_calc` LIKE `stock_prep`;

CREATE TABLE IF NOT EXISTS `mvt` (
  `id_mvt` INT NOT NULL AUTO_INCREMENT,
  `type_de_mvt` VARCHAR(50) NOT NULL DEFAULT '',
  `id_x` INT NOT NULL DEFAULT 0,
  `qt` DECIMAL(15,3) NOT NULL DEFAULT 0,
  `prix_unitaire` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `prix_client` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `prix_aparafa` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `nom_client_fournisseur` VARCHAR(150) NOT NULL DEFAULT '',
  `description_date` VARCHAR(150) NOT NULL DEFAULT '',
  `numero_commande_stock` INT NOT NULL DEFAULT 0,
  `ref_commande_stock` VARCHAR(80) NOT NULL DEFAULT '',
  `note` TEXT NULL,
  `user_mvt` VARCHAR(80) NOT NULL DEFAULT '',
  `Date_du_Journal_mvt` DATE NULL,
  `date_mvt` DATE NULL,
  `status` VARCHAR(80) NOT NULL DEFAULT '',
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_mvt`),
  KEY `idx_mvt_type_numero` (`type_de_mvt`, `numero_commande_stock`),
  KEY `idx_mvt_id_x` (`id_x`),
  KEY `idx_mvt_point` (`nom_client_fournisseur`),
  KEY `idx_mvt_date_journal` (`Date_du_Journal_mvt`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `mvt_calc` LIKE `mvt`;
CREATE TABLE IF NOT EXISTS `mvt_history` LIKE `mvt`;

CREATE TABLE IF NOT EXISTS `recap_vente` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `no_activite` INT NOT NULL DEFAULT 0,
  `nb_ligne` INT NOT NULL DEFAULT 0,
  `Montant` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `difference_aparafa` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `resolution` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `mihoatra` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `history` MEDIUMTEXT NULL,
  `note_general` TEXT NULL,
  `c_point` VARCHAR(150) NOT NULL DEFAULT '',
  `directory` VARCHAR(255) NOT NULL DEFAULT '',
  `status` VARCHAR(80) NOT NULL DEFAULT 'NON_RESOLU',
  `Date_du_Journal` DATE NULL,
  `responsable` VARCHAR(80) NOT NULL DEFAULT 'No_Change',
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_recap_no_activite` (`no_activite`),
  KEY `idx_recap_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `recap_vente_calc` LIKE `recap_vente`;

CREATE TABLE IF NOT EXISTS `depense` (
  `id_depense` INT NOT NULL AUTO_INCREMENT,
  `montant` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `depense_aparafa` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `depense_tsinjo` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `motif` TEXT NULL,
  `activity_no` INT NOT NULL DEFAULT 0,
  `user_depense` VARCHAR(80) NOT NULL DEFAULT '',
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_depense`),
  KEY `idx_depense_activity` (`activity_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `history` (
  `history_id` INT NOT NULL AUTO_INCREMENT,
  `before_change` VARCHAR(255) NOT NULL DEFAULT '',
  `after_change` VARCHAR(255) NOT NULL DEFAULT '',
  `flag` VARCHAR(50) NOT NULL DEFAULT '',
  `details` MEDIUMTEXT NULL,
  `responsable` VARCHAR(80) NOT NULL DEFAULT '',
  `type` VARCHAR(80) NOT NULL DEFAULT '',
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`history_id`),
  KEY `idx_history_type_before` (`type`, `before_change`),
  KEY `idx_history_type_after` (`type`, `after_change`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `checking` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `id_x` INT NOT NULL DEFAULT 0,
  `shop` VARCHAR(150) NOT NULL DEFAULT '',
  `checking_type` VARCHAR(100) NOT NULL DEFAULT '',
  `value` DECIMAL(15,2) NOT NULL DEFAULT 0,
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_checking_lookup` (`id_x`, `shop`, `checking_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `chat` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `from_id` VARCHAR(80) NOT NULL DEFAULT '',
  `to_id` VARCHAR(80) NOT NULL DEFAULT '',
  `msg` TEXT NULL,
  `img_path` VARCHAR(255) NOT NULL DEFAULT '',
  `status` VARCHAR(50) NOT NULL DEFAULT 'new',
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_chat_users_status` (`from_id`, `to_id`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `contact` (
  `id_contact` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL DEFAULT '',
  `mobil` VARCHAR(80) NOT NULL DEFAULT '',
  `mail` VARCHAR(150) NOT NULL DEFAULT '',
  `modified_by` VARCHAR(80) NOT NULL DEFAULT '',
  `date_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_contact`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `tbl_events` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL DEFAULT '',
  `start` DATETIME NULL,
  `end` DATETIME NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `user`
  (`Full_Name`, `Initials`, `Current_Name`, `User_Name`, `Departement`, `Sex`, `Password`, `Permission`, `Note`, `point_de_vente`, `numero_commande`, `description_date`)
VALUES
  ('Utilisateur TEST', 'TEST', 'Utilisateur TEST', 'test_admin', 'TEST', 'TEST', 'test123', 'Y', 'Compte fictif TEST pour gestion_stock_test', 'Amparafa', 1, 'Journal du 01/01/26')
ON DUPLICATE KEY UPDATE
  `Full_Name` = VALUES(`Full_Name`),
  `Permission` = VALUES(`Permission`),
  `Note` = VALUES(`Note`);

INSERT INTO `memo` (`id_memo`, `note_memo`, `valeur_memo`, `description_date`) VALUES
  (1, 'TEST vente', 1, 'Journal du 01/01/26'),
  (2, 'TEST stock', 1, 'Ajout TEST du 01/01/26'),
  (3, 'TEST produit', 1, 'Produit TEST')
ON DUPLICATE KEY UPDATE
  `note_memo` = VALUES(`note_memo`),
  `description_date` = VALUES(`description_date`);

INSERT INTO `shop` (`long_name`, `short_name`, `shop_user`) VALUES
  ('Point de vente TEST Amparafa', 'Amparafa', 'test_admin'),
  ('Point de vente TEST Soalazaina', 'Soalazaina', 'test_admin'),
  ('Point de vente TEST Bejofo', 'Bejofo', 'test_admin'),
  ('Point de vente TEST Ambato Tantely', 'Ambato_Tantely', 'test_admin')
ON DUPLICATE KEY UPDATE
  `long_name` = VALUES(`long_name`),
  `shop_user` = VALUES(`shop_user`);

INSERT INTO `produit`
  (`nom_x`, `prix_de_vente`, `prix_fournisseur`, `benefice`, `reference_x`, `note_x`, `user_x`, `pu_aparafa`, `pu_ambato_tantely`, `pu_soalazaina`)
VALUES
  ('ARTICLE TEST - NE PAS UTILISER EN PRODUCTION', 1000, 700, 300, 'TEST-PROD-001', 'Donnee fictive TEST', 'test_admin', 1200, 1000, 1100)
ON DUPLICATE KEY UPDATE
  `nom_x` = VALUES(`nom_x`),
  `note_x` = VALUES(`note_x`);
