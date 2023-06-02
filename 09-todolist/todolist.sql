-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 02 juin 2023 à 16:10
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
-- Base de données : `todolist`
--

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

CREATE TABLE `tasks` (
  `id_task` int(3) NOT NULL,
  `date_task` datetime DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` int(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tasks`
--

INSERT INTO `tasks` (`id_task`, `date_task`, `description`, `status`) VALUES
(1, '2023-06-02 16:06:18', 'Ranger la chambre', 1),
(2, '2023-06-02 16:06:23', 'Laver la voiture', 1),
(3, '2023-06-02 16:06:29', 'Écrire mon rapport', 2),
(4, '2023-06-02 16:06:36', 'Passer l\'aspirateur', 2),
(5, '2023-06-02 16:06:45', 'Faire les courses', 3),
(6, '2023-06-02 16:06:50', 'Dormir', 4),
(7, '2023-06-02 16:07:04', 'Finir l\'ECF back-end avant 17 heures', 3),
(8, '2023-06-02 16:07:53', 'Manger', 4),
(9, '2023-06-02 16:10:40', 'Devenir développeur', 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id_task`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id_task` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
