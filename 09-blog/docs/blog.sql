-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 09 juin 2023 à 14:55
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

CREATE TABLE `comments` (
  `id_comment` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_post` int(11) NOT NULL,
  `date_comment` datetime NOT NULL,
  `contenu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id_comment`, `id_user`, `id_post`, `date_comment`, `contenu`) VALUES
(2, 1, 2, '2023-06-09 14:31:25', 'Un seul commentaire'),
(3, 2, 3, '2023-06-09 14:35:58', 'Un seul commentaire'),
(4, NULL, 11, '2023-06-09 14:43:42', 'Commentaire User3'),
(5, NULL, 10, '2023-06-09 14:44:01', 'Commentaire User3'),
(6, 4, 11, '2023-06-09 14:44:59', 'Commentaire User4'),
(7, 4, 10, '2023-06-09 14:45:03', 'Commentaire User4'),
(8, 4, 6, '2023-06-09 14:45:10', 'Commentaire User4'),
(9, 4, 7, '2023-06-09 14:45:19', 'Commentaire User4'),
(10, 2, 11, '2023-06-09 14:45:42', 'Commentaire User2'),
(11, 2, 10, '2023-06-09 14:45:49', 'Commentaire User2'),
(12, 1, 11, '2023-06-09 14:46:05', 'Commentaire User1'),
(13, 1, 10, '2023-06-09 14:46:09', 'Commentaire User1'),
(14, 1, 12, '2023-06-09 14:51:13', 'Pas mal pour un début !'),
(15, 2, 12, '2023-06-09 14:53:09', 'Bien joué !'),
(16, 4, 12, '2023-06-09 14:53:25', 'Bravo !');

-- --------------------------------------------------------

--
-- Structure de la table `posts`
--

CREATE TABLE `posts` (
  `id_post` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `date_post` datetime NOT NULL,
  `titre` varchar(200) NOT NULL,
  `contenu` text NOT NULL,
  `photo` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `posts`
--

INSERT INTO `posts` (`id_post`, `id_user`, `date_post`, `titre`, `contenu`, `photo`) VALUES
(1, 1, '2023-06-09 14:21:11', 'Premier article de ce blog !', 'Premier article et premier contenu !', ''),
(2, 1, '2023-06-09 14:35:02', 'Exemple d&#039;article - Cas 1', 'Sans photo, avec un seul commentaire et auteur de l&#039;article existant', ''),
(3, 1, '2023-06-09 14:37:42', 'Exemple d&#039;article - Cas 2', 'Identique cas 1 mais avec photo', '64831be722565_Alaska.jpg'),
(4, 1, '2023-06-09 14:35:13', 'Exemple d&#039;article - Cas 3', 'Sans photo, sans commentaire et auteur de l&#039;article existant', ''),
(5, 2, '2023-06-09 14:36:59', 'Exemple d&#039;article - Cas 4', 'Identique cas 3 mais avec photo', '64831ceb41681_Cool Winter Night.jpg'),
(6, NULL, '2023-06-09 14:39:21', 'Exemple d&#039;article - Cas 5', 'Sans photo, un seul commentaire, auteur de l&#039;article supprimé', ''),
(7, NULL, '2023-06-09 14:39:45', 'Exemple d&#039;article - Cas 6', 'Identique cas 5 mais avec photo', '64831d9109e2a_Cell art.jpg'),
(8, NULL, '2023-06-09 14:40:39', 'Exemple d&#039;article - Cas 7', 'Sans photo, sans commentaires, auteur de l&#039;article supprimé', ''),
(9, NULL, '2023-06-09 14:40:53', 'Exemple d&#039;article - Cas 8', 'Identique cas 7 mais avec photo', '64831dd5844f2_Glacier National Park.jpg'),
(10, 4, '2023-06-09 14:42:06', 'Exemple d&#039;article - Cas 9', 'Sans photo, avec plusieurs commentaires, auteur de l&#039;article existant', ''),
(11, 4, '2023-06-09 14:42:26', 'Exemple d&#039;article - Cas 10', 'Identique cas 9 mais avec photo', '64831e325ac29_Glowing Bridge.jpg'),
(12, 1, '2023-06-09 14:52:49', 'Fonctionnalités du blog', 'Visiteur non connecté :\r\n• Lire les articles présentés en page d’accueil\r\n• Possibilité de s&#039;inscrire et se connecter\r\n\r\nSi connecté :\r\n• Commenter un article\r\n• Supprimer ses propres commentaires\r\n• Rédiger un article\r\n• Consulter ses articles\r\n• Modifier ses articles\r\n• Supprimer ses articles\r\n• Lire/Modifier son profil\r\n• Se déconnecter', '64831fe60bfb1_blog_communautaire.png');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `mdp` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `nom`, `mdp`, `email`) VALUES
(1, 'User1', '$2y$10$ASZrGWL9bxYXWJADtEOrNOMAD4aSYUPCFRiWkuOE/NaHAsD9d3/tK', 'user1@user1.com'),
(2, 'User2', '$2y$10$Hf0RTJi5ai1u4vhzmrfbUOYEryTjrAI5PoA05KUCgQypXoRSubUiO', 'user2@user2.com'),
(4, 'User4', '$2y$10$Ia9Nqjqf.E1oh4.MGMFTH.XJ1y3O8pDwKcaTr0HiQlkAYZyX0B5Ue', 'user4@user4.com');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id_comment`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_post` (`id_post`);

--
-- Index pour la table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id_post`),
  ADD KEY `id_user` (`id_user`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `comments`
--
ALTER TABLE `comments`
  MODIFY `id_comment` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT pour la table `posts`
--
ALTER TABLE `posts`
  MODIFY `id_post` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `contrainte2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `contrainte3` FOREIGN KEY (`id_post`) REFERENCES `posts` (`id_post`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `contrainte1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
