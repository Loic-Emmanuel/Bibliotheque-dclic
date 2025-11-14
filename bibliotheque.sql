-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : ven. 14 nov. 2025 à 14:56
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bibliotheque`
--

-- --------------------------------------------------------

--
-- Structure de la table `lecteurs`
--

DROP TABLE IF EXISTS `lecteurs`;
CREATE TABLE IF NOT EXISTS `lecteurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `lecteurs`
--

INSERT INTO `lecteurs` (`id`, `nom`, `prenom`, `email`) VALUES
(1, 'Yao', 'Allou Loic Emmanuel', 'allouyao21@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `liste_lecture`
--

DROP TABLE IF EXISTS `liste_lecture`;
CREATE TABLE IF NOT EXISTS `liste_lecture` (
  `id_livre` int NOT NULL,
  `id_lecteur` int NOT NULL,
  `date_emprunt` date DEFAULT NULL,
  `date_retour` date DEFAULT NULL,
  PRIMARY KEY (`id_livre`,`id_lecteur`),
  KEY `id_lecteur` (`id_lecteur`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `liste_lecture`
--

INSERT INTO `liste_lecture` (`id_livre`, `id_lecteur`, `date_emprunt`, `date_retour`) VALUES
(15, 1, '2025-11-14', NULL),
(6, 1, '2025-11-14', NULL),
(18, 1, '2025-11-14', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `livres`
--

DROP TABLE IF EXISTS `livres`;
CREATE TABLE IF NOT EXISTS `livres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `auteur` varchar(100) NOT NULL,
  `description` text,
  `maison_edition` varchar(100) DEFAULT NULL,
  `nombre_exemplaire` int DEFAULT '0',
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `livres`
--

INSERT INTO `livres` (`id`, `titre`, `auteur`, `description`, `maison_edition`, `nombre_exemplaire`, `image`) VALUES
(15, 'Rerum quis veniam a', 'Ut harum dolorum sed', 'Omnis fugit dolorem', 'Commodo et et sunt i', 3, '691716792e034_1763120761.jpg'),
(16, 'Occaecat ipsam dolor', 'Ut fugiat do ut rati', 'Quis dolore debitis', 'Obcaecati cumque ess', 43, '691716564ab9d_1763120726.jpeg'),
(17, 'Mika bereddd', 'mmdd', 'dededede jdefjf rfrhnf rfhjrhfh frhfjrehe htrhete fhbezfhbrezf rhefbherbzdendje dbenbd e dehbdhe dhe', 'pzdddd', 1585, '691716433f361_1763120707.png'),
(18, 'Eveniet similique e', 'Ut est esse ea ipsa', 'Iure fugiat non elit', 'Hic earum tempore s', 32, NULL),
(6, 'Le Seigneur des Anneaux', 'J.R.R. Tolkien', 'Une épopée fantastique dans la Terre du Milieu.', 'Christian Bourgois', 4, '691716b2d543c_1763120818.webp'),
(8, 'Orgueil et Préjugés', 'Jane Austen', 'Roman sur la vie et les amours de la famille Bennet.', 'Penguin Classics', 3, '6917169f52278_1763120799.jpg'),
(13, 'Quis cillum nisi fug', 'Quidem ullam est vol', 'Ipsa ad labore cons', 'Dolor nihil deleniti', 62, '69171686a0b6c_1763120774.jpg'),
(26, 'Sed at excepteur fac', 'Magnam proident nes', 'Tenetur sit libero', 'Quis similique dolor', 16, '691715d085a10_1763120592.png'),
(25, 'Deserunt non sed dol', 'Dolores quo dolore a', 'Nostrum labore est', 'Sit ullam consectet', 45, '6917162778317_1763120679.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
