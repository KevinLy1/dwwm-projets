CREATE DATABASE IF NOT EXISTS blog CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE blog;

CREATE TABLE IF NOT EXISTS comments (
    id_comment INT NOT NULL AUTO_INCREMENT,
    id_user INT NULL,
    id_post INT NOT NULL,
    date_comment DATETIME NOT NULL,
    contenu TEXT NOT NULL,
    PRIMARY KEY (id_comment),
    KEY id_user (id_user),
    KEY id_post (id_post)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS posts (
    id_post INT NOT NULL AUTO_INCREMENT,
    id_user INT NULL,
    date_post DATETIME NOT NULL,
    titre VARCHAR(200) NOT NULL,
    contenu TEXT NOT NULL,
    photo VARCHAR(200) NOT NULL,
    PRIMARY KEY (id_post),
    KEY id_user (id_user)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS users (
    id_user INT NOT NULL AUTO_INCREMENT,
    nom VARCHAR(30) NOT NULL,
    mdp VARCHAR(255) NOT NULL,
    email VARCHAR(150) NOT NULL,
    PRIMARY KEY (id_user)
) ENGINE=InnoDB;

-- Contraintes
ALTER TABLE posts
    ADD CONSTRAINT contrainte1 FOREIGN KEY (id_user) REFERENCES users (id_user)
        ON DELETE SET NULL ON UPDATE CASCADE;

ALTER TABLE comments
    ADD CONSTRAINT contrainte2 FOREIGN KEY (id_user) REFERENCES users (id_user)
        ON DELETE SET NULL ON UPDATE CASCADE,
    ADD CONSTRAINT contrainte3 FOREIGN KEY (id_post) REFERENCES posts (id_post)
        ON DELETE CASCADE ON UPDATE CASCADE;
