-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : lun. 24 mars 2025 à 08:27
-- Version du serveur : 8.4.3
-- Version de PHP : 8.3.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gameverse_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

CREATE TABLE `inscriptions` (
  `id` int NOT NULL,
  `tournoi_id` int NOT NULL,
  `joueur_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id`, `tournoi_id`, `joueur_id`) VALUES
(36, 13, 10),
(37, 13, 12),
(39, 13, 11),
(45, 13, 5),
(46, 14, 5),
(47, 14, 10),
(48, 14, 12),
(49, 14, 11);

-- --------------------------------------------------------

--
-- Structure de la table `matchs`
--

CREATE TABLE `matchs` (
  `id` int NOT NULL,
  `tournoi_id` int NOT NULL,
  `joueur1_id` int NOT NULL,
  `joueur2_id` int NOT NULL,
  `score_joueur1` int DEFAULT NULL,
  `score_joueur2` int DEFAULT NULL,
  `statut` enum('en attente','validé','refusé') COLLATE utf8mb4_general_ci DEFAULT 'en attente',
  `preuve` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `matchs`
--

INSERT INTO `matchs` (`id`, `tournoi_id`, `joueur1_id`, `joueur2_id`, `score_joueur1`, `score_joueur2`, `statut`, `preuve`) VALUES
(4, 14, 5, 10, NULL, 5, 'en attente', NULL),
(5, 14, 5, 10, NULL, NULL, 'en attente', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `messages`
--

CREATE TABLE `messages` (
  `id` int NOT NULL,
  `sender_id` int NOT NULL,
  `receiver_id` int NOT NULL,
  `message` text COLLATE utf8mb4_general_ci NOT NULL,
  `timestamp` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `participants`
--

CREATE TABLE `participants` (
  `id` int NOT NULL,
  `tournoi_id` int NOT NULL,
  `user_id` int NOT NULL,
  `score` int DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `scores`
--

CREATE TABLE `scores` (
  `id` int NOT NULL,
  `joueur_id` int NOT NULL,
  `jeu` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `score` int NOT NULL,
  `date_enregistrement` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `scores`
--

INSERT INTO `scores` (`id`, `joueur_id`, `jeu`, `score`, `date_enregistrement`) VALUES
(4, 5, 'Pixel Runner', 1, '2025-03-08 17:35:25'),
(5, 5, 'Tower Defense Arena', 70, '2025-03-10 08:22:02'),
(6, 5, 'Card Clash', 2, '2025-03-10 08:40:04'),
(7, 5, 'Card Clash', 2, '2025-03-10 08:44:49'),
(8, 5, 'Word Battle', 16, '2025-03-10 08:49:56'),
(9, 5, 'Word Battle', 40, '2025-03-10 08:53:49'),
(10, 5, 'Card Clash', 3, '2025-03-10 08:55:12'),
(11, 10, 'Tower Defense Arena', 40, '2025-03-10 09:25:32'),
(12, 12, 'Tower Defense Arena', 10, '2025-03-10 09:26:23'),
(13, 12, 'Word Battle', 32, '2025-03-10 09:27:36'),
(14, 11, 'Pixel Runner', 2, '2025-03-10 09:41:55'),
(16, 11, 'Card Clash', 2, '2025-03-10 10:01:44'),
(18, 5, 'Pixel Runner', 2, '2025-03-11 08:09:49'),
(24, 5, 'Word Battle', 40, '2025-03-11 08:11:31'),
(26, 5, 'Word Battle', 14, '2025-03-11 08:16:36'),
(27, 5, 'Word Battle', 10, '2025-03-11 08:17:41'),
(28, 5, 'Word Battle', 6, '2025-03-11 08:23:41');

-- --------------------------------------------------------

--
-- Structure de la table `tournois`
--

CREATE TABLE `tournois` (
  `id` int NOT NULL,
  `nom` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `date_debut` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `date_fin` datetime DEFAULT NULL,
  `statut` enum('ouvert','en cours','terminé') COLLATE utf8mb4_general_ci DEFAULT 'ouvert',
  `max_joueurs` int NOT NULL DEFAULT '16',
  `type_tournoi` varchar(50) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Élimination directe',
  `jeu` varchar(100) COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Pixel Runner'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `tournois`
--

INSERT INTO `tournois` (`id`, `nom`, `date_debut`, `date_fin`, `statut`, `max_joueurs`, `type_tournoi`, `jeu`) VALUES
(13, 'Test', '2025-03-11 08:56:27', '2025-03-12 08:56:27', 'ouvert', 50, 'Élimination directe', 'Pixel Runner'),
(14, 'Test2', '2025-03-11 09:03:34', '2025-03-12 09:03:34', 'ouvert', 5, 'Élimination directe', 'Pixel Runner');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `role` enum('admin','moderator','organizer','user') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'user',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `avatar` varchar(255) COLLATE utf8mb4_general_ci DEFAULT 'default.png',
  `banned` tinyint(1) DEFAULT '0',
  `last_active` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `last_activity` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`, `avatar`, `banned`, `last_active`, `last_activity`) VALUES
(5, 'Administrateur', 'player2@test.com', '$2y$10$VJkeB0iEUKAAWuz/Rsscu.g9jmZG/R.dofxfUzdx.7astPdS5AkyS', 'admin', '2025-02-25 08:00:26', 'images.png', 1, '2025-03-13 08:07:20', '2025-03-13 09:07:20'),
(6, 'test', 'test@test.com', '$2y$10$Ob6Z45giNs3U/WBZ4PInkOvd3mbZi00Gg2BRg9xPXZziyVdfzgey6', 'user', '2025-02-25 09:33:12', 'DALL·E 2025-03-10 08.53.51 - A pixel art sprite of an arrow projectile for a tower defense game. The arrow should have a medieval or fantasy style, with a sharp tip and feathered .webp', 0, '2025-03-17 14:05:53', NULL),
(10, 'organisateur', 'testnum@test.com', '$2y$10$BWFEWW1m2MDNhNbXfiQUsePzo0XZV9F4sAuu.im09LzVt3SVBBdcu', 'organizer', '2025-02-27 08:55:48', 'default.png', 0, '2025-03-11 09:05:16', '2025-03-11 10:05:16'),
(11, 'moderateur', 'uyidsghfsdjf@gmail.com', '$2y$10$vVFIEzRYMdhJW58SCC0MQOYZtRpoyvy9DsiJ7R9uPSZBaQ/ptyA96', 'moderator', '2025-03-03 13:46:14', 'téléchargement (1).png', 0, '2025-03-10 09:42:12', '2025-03-10 10:30:04'),
(12, 'Zzedar', 'zzedar16@gmail.com', '$2y$10$hCRAM49QVdAHtdmS/l9R/.OMExOuAzpRnLAaiJsHQeDV.VyvBHjSC', 'user', '2025-03-10 09:21:04', 'default.png', 0, '2025-03-24 08:17:13', '2025-03-24 09:17:13');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournoi_id` (`tournoi_id`),
  ADD KEY `joueur_id` (`joueur_id`);

--
-- Index pour la table `matchs`
--
ALTER TABLE `matchs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournoi_id` (`tournoi_id`),
  ADD KEY `joueur1_id` (`joueur1_id`),
  ADD KEY `joueur2_id` (`joueur2_id`);

--
-- Index pour la table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Index pour la table `participants`
--
ALTER TABLE `participants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournoi_id` (`tournoi_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `joueur_id` (`joueur_id`);

--
-- Index pour la table `tournois`
--
ALTER TABLE `tournois`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `matchs`
--
ALTER TABLE `matchs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `participants`
--
ALTER TABLE `participants`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `scores`
--
ALTER TABLE `scores`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT pour la table `tournois`
--
ALTER TABLE `tournois`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `inscriptions`
--
ALTER TABLE `inscriptions`
  ADD CONSTRAINT `inscriptions_ibfk_1` FOREIGN KEY (`tournoi_id`) REFERENCES `tournois` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inscriptions_ibfk_2` FOREIGN KEY (`joueur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `matchs`
--
ALTER TABLE `matchs`
  ADD CONSTRAINT `matchs_ibfk_1` FOREIGN KEY (`tournoi_id`) REFERENCES `tournois` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matchs_ibfk_2` FOREIGN KEY (`joueur1_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `matchs_ibfk_3` FOREIGN KEY (`joueur2_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `participants`
--
ALTER TABLE `participants`
  ADD CONSTRAINT `participants_ibfk_1` FOREIGN KEY (`tournoi_id`) REFERENCES `tournois` (`id`),
  ADD CONSTRAINT `participants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_ibfk_1` FOREIGN KEY (`joueur_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
