CREATE DATABASE IF NOT EXISTS heatalert CHARACTER
SET
    utf8mb4 COLLATE utf8mb4_unicode_ci;

USE heatalert;

-- 1. ZONES
CREATE TABLE zones (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    ville VARCHAR(255) NOT NULL,
    gouvernorat VARCHAR(255) NULL,
    code_postal VARCHAR(10) NULL,
    latitude DECIMAL(10, 7) NULL,
    longitude DECIMAL(10, 7) NULL,
    description TEXT NULL,
    actif TINYINT (1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

-- 2. USERS
CREATE TABLE users (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    nom VARCHAR(255) NOT NULL,
    prenom VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    telephone VARCHAR(20) NULL,
    role VARCHAR(255) NOT NULL DEFAULT 'ROLE_USER',
    date_inscription TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    zone_id BIGINT UNSIGNED NULL,
    remember_token VARCHAR(100) NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY users_email_unique (email),
    CONSTRAINT users_zone_id_foreign FOREIGN KEY (zone_id) REFERENCES zones (id) ON DELETE SET NULL
) ENGINE = InnoDB;

-- 3. ALERTES_METEO
CREATE TABLE alertes_meteo (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL DEFAULT 'canicule',
    niveau VARCHAR(20) NOT NULL DEFAULT 'jaune',
    statut VARCHAR(20) NOT NULL DEFAULT 'brouillon',
    description TEXT NULL,
    temperature_min DECIMAL(5, 2) NULL,
    temperature_max DECIMAL(5, 2) NULL,
    temperature_ressentie DECIMAL(5, 2) NULL,
    humidite DECIMAL(5, 2) NULL,
    indice_uv DECIMAL(4, 1) NULL,
    vitesse_vent DECIMAL(6, 2) NULL,
    risque_coupure TINYINT (1) NOT NULL DEFAULT 0,
    source VARCHAR(255) NULL,
    date_debut DATETIME NOT NULL,
    date_fin DATETIME NULL,
    zone_id BIGINT UNSIGNED NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT alertes_meteo_zone_id_foreign FOREIGN KEY (zone_id) REFERENCES zones (id) ON DELETE SET NULL
) ENGINE = InnoDB;

-- 4. CONSEILS
CREATE TABLE conseils (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    titre VARCHAR(255) NOT NULL,
    contenu TEXT NOT NULL,
    categorie VARCHAR(50) NOT NULL DEFAULT 'hydratation',
    niveau_alerte_cible VARCHAR(20) NULL,
    icone VARCHAR(255) NULL,
    actif TINYINT (1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE = InnoDB;

-- 5. ALERTE_CONSEIL (table pivot N-N, utilisée par ton modèle Conseil)
CREATE TABLE alerte_conseil (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    alerte_meteo_id BIGINT UNSIGNED NOT NULL,
    conseil_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY alerte_conseil_unique (alerte_meteo_id, conseil_id),
    CONSTRAINT alerte_conseil_alerte_fk FOREIGN KEY (alerte_meteo_id) REFERENCES alertes_meteo (id) ON DELETE CASCADE,
    CONSTRAINT alerte_conseil_conseil_fk FOREIGN KEY (conseil_id) REFERENCES conseils (id) ON DELETE CASCADE
) ENGINE = InnoDB;

