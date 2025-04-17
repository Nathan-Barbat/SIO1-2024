-- Création de la base de données
CREATE DATABASE jeu_video;
USE jeu_video;

-- Création de la table personnages
CREATE TABLE personnages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    type VARCHAR(50) NOT NULL
);

-- Création de la table armes
CREATE TABLE armes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    nom VARCHAR(100) NOT NULL
);

-- Création de la table pouvoir
CREATE TABLE pouvoir (
    id INT AUTO_INCREMENT PRIMARY KEY,
    type VARCHAR(50) NOT NULL,
    nom VARCHAR(100) NOT NULL
);

-- Création de la table de liaison personnage_arme
CREATE TABLE personnage_arme (
    id_personnage INT,
    id_arme INT,
    PRIMARY KEY (id_personnage, id_arme),
    FOREIGN KEY (id_personnage) REFERENCES personnages(id) ON DELETE CASCADE,
    FOREIGN KEY (id_arme) REFERENCES armes(id) ON DELETE CASCADE
);

-- Création de la table de liaison personnage_pouvoir
CREATE TABLE personnage_pouvoir (
    id_personnage INT,
    id_pouvoir INT,
    PRIMARY KEY (id_personnage, id_pouvoir),
    FOREIGN KEY (id_personnage) REFERENCES personnages(id) ON DELETE CASCADE,
    FOREIGN KEY (id_pouvoir) REFERENCES pouvoir(id) ON DELETE CASCADE
);

-- Insertion de quelques exemples d'armes
INSERT INTO armes (type, nom) VALUES
('mêlée', 'Épée longue'),
('mêlée', 'Hache de guerre'),
('distance', 'Arc elfique'),
('distance', 'Arbalète'),
('magique', 'Baguette de feu'),
('magique', 'Bâton arcanique');

-- Insertion de quelques exemples de pouvoirs
INSERT INTO pouvoir (type, nom) VALUES
('défense', 'Bouclier magique'),
('défense', 'Armure renforcée'),
('attaque', 'Boule de feu'),
('attaque', 'Éclair'),
('régénération', 'Soin mineur'),
('régénération', 'Régénération avancée');