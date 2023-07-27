-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : mariadb11
-- Généré le : jeu. 27 juil. 2023 à 14:42
-- Version du serveur : 11.0.2-MariaDB-1:11.0.2+maria~ubu2204
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `carloc`
--

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20230727083917', '2023-07-27 10:39:21', 15569);

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext NOT NULL,
  `headers` longtext NOT NULL,
  `queue_name` varchar(190) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `id_user_id` int(11) NOT NULL,
  `date_time_departure` datetime NOT NULL,
  `date_time_end` datetime NOT NULL,
  `total_price` int(11) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `order_vehicle`
--

CREATE TABLE `order_vehicle` (
  `order_id` int(11) NOT NULL,
  `vehicle_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) NOT NULL,
  `username` varchar(20) NOT NULL,
  `name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `sex` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `username`, `name`, `first_name`, `sex`, `created_at`) VALUES
(1, 'admin@admin.com', '[\"ROLE_ADMIN\"]', '$2y$13$X7rlVSdOOzqA1DzDTFO8.uEhIEBOWTtvECWK1BkvFDEItl9c8yF82', 'admin', 'admin', 'Admin', 'other', '2023-07-27 10:41:01'),
(3, 'charlotte@charlotte.com', '[]', '$2y$13$sTS2YKLknxIbL4LlyyqAW.OwzEaZZ45w09oaGmokHHzy4rS10DkIG', 'charlotte', 'Dupont', 'Charlotte', 'female', '2023-07-27 10:47:32'),
(4, 'justine@justine.com', '[]', '$2y$13$4xzvoecLZM4bgMLhbxNpEuuPx3b1lFF.6.WzZyZQXKNN6w6aQ4mvy', 'justine', 'Leopa', 'Justine', 'female', '2023-07-27 10:48:01'),
(5, 'contact@kevinly.fr', '[]', '$2y$13$2bbTa85mELtXD5HSoMq67eSGaiUNyKZ0sX0qoZ.W9Y6EIbnRBSDnW', 'kevin', 'Ly', 'Kevin', 'male', '2023-07-27 16:28:33');

-- --------------------------------------------------------

--
-- Structure de la table `vehicle`
--

CREATE TABLE `vehicle` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `brand` varchar(50) NOT NULL,
  `model` varchar(50) NOT NULL,
  `description` longtext DEFAULT NULL,
  `photo` varchar(200) DEFAULT NULL,
  `daily_price` int(11) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `vehicle`
--

INSERT INTO `vehicle` (`id`, `title`, `brand`, `model`, `description`, `photo`, `daily_price`, `created_at`, `updated_at`) VALUES
(1, 'Peugeot 207 CC', 'Peugeot', '207 CC', 'Une CC de toute beauté', 'peugeot-207-cc-64b807f4795b3127377579.jpg', 100, '2023-07-27 10:44:28', NULL),
(2, 'Tesla Model S', 'Tesla', 'Model S', NULL, 'tesla-model-s-plaid-1540554-64b8f54684ef4993918270.jpg', 200, '2023-07-27 10:44:48', NULL),
(3, 'Tesla Model X', 'Tesla', 'Model X', NULL, 'tesla-model-x-millesime-2022-md-0-64b8f1084bca3550932173.jpg', 300, '2023-07-27 10:45:02', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F529939879F37AE5` (`id_user_id`);

--
-- Index pour la table `order_vehicle`
--
ALTER TABLE `order_vehicle`
  ADD PRIMARY KEY (`order_id`,`vehicle_id`),
  ADD KEY `IDX_EDFA4DCD8D9F6D38` (`order_id`),
  ADD KEY `IDX_EDFA4DCD545317D1` (`vehicle_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`);

--
-- Index pour la table `vehicle`
--
ALTER TABLE `vehicle`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `vehicle`
--
ALTER TABLE `vehicle`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F529939879F37AE5` FOREIGN KEY (`id_user_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `order_vehicle`
--
ALTER TABLE `order_vehicle`
  ADD CONSTRAINT `FK_EDFA4DCD545317D1` FOREIGN KEY (`vehicle_id`) REFERENCES `vehicle` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_EDFA4DCD8D9F6D38` FOREIGN KEY (`order_id`) REFERENCES `order` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
