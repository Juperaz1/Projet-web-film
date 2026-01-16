-- Création de la base de données
CREATE DATABASE IF NOT EXISTS `Film_db`;
USE `Film_db`;

-- Table UTILISATEUR
CREATE TABLE `UTILISATEUR` (
    `id_utilisateur` INT AUTO_INCREMENT PRIMARY KEY,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `mot_de_passe_hash` VARCHAR(255) NOT NULL,
    `nom` VARCHAR(100) NOT NULL,
    `date_inscription` DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Table FILM
CREATE TABLE `FILM` (
    `id_film` INT AUTO_INCREMENT PRIMARY KEY,
    `titre` VARCHAR(255) NOT NULL,
    `annee` SMALLINT NOT NULL,
    `duree` SMALLINT NOT NULL COMMENT 'Durée en minutes',
    `synopsis` TEXT,
    `genre` VARCHAR(100),
    `prix_location_par_defaut` DECIMAL(5,2) NOT NULL,
    `chemin_affiche` VARCHAR(500) DEFAULT NULL
);

-- Table FAVORI
CREATE TABLE `FAVORI` (
    `id_favori` INT AUTO_INCREMENT PRIMARY KEY,
    `id_utilisateur` INT NOT NULL,
    `id_film` INT NOT NULL,
    `date_ajout` DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`id_utilisateur`) REFERENCES `UTILISATEUR`(`id_utilisateur`) ON DELETE CASCADE,
    FOREIGN KEY (`id_film`) REFERENCES `FILM`(`id_film`) ON DELETE CASCADE,
    UNIQUE KEY `unique_favori` (`id_utilisateur`, `id_film`)
);

-- Table TARIF_DYNAMIQUE
CREATE TABLE `TARIF_DYNAMIQUE` (
    `id_tarif` INT AUTO_INCREMENT PRIMARY KEY,
    `jour_semaine` ENUM(
            'lundi',
            'mardi', 
            'mercredi', 
            'jeudi', 
            'vendredi', 
            'samedi', 
            'dimanche') NOT NULL,
    `pourcentage_reduction` DECIMAL(5,2) NOT NULL COMMENT 'Ex: -20.00 pour 20% de réduction',
    `actif` BOOLEAN DEFAULT TRUE
);

-- Table LOCATION
CREATE TABLE `LOCATION` (
    `id_location` INT AUTO_INCREMENT PRIMARY KEY,
    `id_utilisateur` INT NOT NULL,
    `id_film` INT NOT NULL,
    `date_location` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `date_retour_prevue` DATE NOT NULL,
    `prix_final` DECIMAL(5,2) NOT NULL,
    `statut` ENUM('loué', 'retourné') DEFAULT 'loué',
    FOREIGN KEY (`id_utilisateur`) REFERENCES `UTILISATEUR`(`id_utilisateur`) ON DELETE CASCADE,
    FOREIGN KEY (`id_film`) REFERENCES `FILM`(`id_film`) ON DELETE CASCADE
);

INSERT INTO `TARIF_DYNAMIQUE` (`jour_semaine`, `pourcentage_reduction`, `actif`) VALUES
('mardi', -20.00, TRUE),
('jeudi', -15.00, TRUE),
('dimanche',-10.00, TRUE);

-- Insertion de quelques films exemple
INSERT INTO `FILM` (`titre`, `annee`, `duree`, `synopsis`, `genre`, `prix_location_par_defaut`, `chemin_affiche`) VALUES
('Inception', 2010, 148, 'Un voleur qui s infiltre dans les rêves', 'Science-Fiction', 3.99, 'https://fr.web.img6.acsta.net/c_310_420/medias/nmedia/18/72/34/14/19476654.jpg'),
('Le Parrain', 1972, 175, 'L''histoire d''une famille mafieuse', 'Drame', 2.99, 'https://fr.web.img6.acsta.net/c_310_420/pictures/22/01/14/08/39/1848157.jpg'),
('Interstellar', 2014, 169, 'Un voyage spatial pour sauver l''humanité', 'Science-Fiction', 4.50, 'https://fr.web.img5.acsta.net/c_310_420/pictures/14/09/24/12/08/158828.jpg'),
('La La Land', 2016, 128, 'Une histoire d''amour à Los Angeles', 'Musical', 3.25, 'https://fr.web.img2.acsta.net/c_310_420/pictures/16/11/10/13/52/169386.jpg'),
('Les Évadés', 1994, 142, 'Un banquier emprisonné s''évade', 'Drame', 2.75, 'https://fr.web.img6.acsta.net/c_310_420/medias/nmedia/18/63/30/68/18686447.jpg');
