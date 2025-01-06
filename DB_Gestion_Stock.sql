-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : lun. 06 jan. 2025 à 19:11
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
-- Base de données : `DB_Gestion_Stock`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

CREATE TABLE `administrateur` (
  `id_admin` int(11) NOT NULL,
  `nom_admin` varchar(27) DEFAULT NULL,
  `prenom_admin` varchar(27) DEFAULT NULL,
  `mail_admin` varchar(50) DEFAULT NULL,
  `telephone_admin` varchar(27) DEFAULT NULL,
  `pass_admin` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`id_admin`, `nom_admin`, `prenom_admin`, `mail_admin`, `telephone_admin`, `pass_admin`) VALUES
(107, 'root', 'root', 'root@gmail.com', '1', '$2y$10$Y6Bk1vTCP0LlxB1lXMBlDulOfpUkoGe/ehCcKXVfHe9Fv68zGX7B.'),
(108, 'Dianka', 'Seydou', 'diankaseydou@gmail.com', '76185868', '$2y$10$z0JC17qTjvijezjzJ5xpLuyttD.DoDEUzaeGu7f6D0MEE9HVYuthm'),
(109, 'Baya', 'Seck', 'bayaseck@gmail.com', '466883939', '$2y$10$o8cbfUDoero0mhTSINpXaeMS4pbNxBSss.t2UByeuwNVTuuzQdyJu'),
(110, 'Sene', 'ali', 'ali@gmail.com', '772345533', '$2y$10$K8snvFHK/HWmAZt3xbjHsOf2RfLuw6j/dbGvLJRz/iiH4GXVT0T6S');

-- --------------------------------------------------------

--
-- Structure de la table `bon_commande`
--

CREATE TABLE `bon_commande` (
  `id_BC` int(11) NOT NULL,
  `idBC_gen` varchar(30) NOT NULL,
  `date` datetime NOT NULL,
  `nomBC` varchar(50) NOT NULL,
  `Etat_commander` int(11) NOT NULL DEFAULT 1,
  `Date_validation` datetime NOT NULL DEFAULT '2024-09-13 08:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bon_commande`
--

INSERT INTO `bon_commande` (`id_BC`, `idBC_gen`, `date`, `nomBC`, `Etat_commander`, `Date_validation`) VALUES
(136, 'BC3/2024-09-17 14:36:32', '2024-09-17 14:36:32', 'Seyod', 4, '2024-09-13 08:00:00'),
(137, 'BC4/2024-09-17 14:43:22', '2024-09-17 14:43:22', 'Bon', 2, '2024-09-18 10:24:18'),
(138, 'BC5/2024-09-17 14:48:55', '2024-09-17 14:48:55', 'Bon2', 2, '2024-09-23 12:04:43'),
(139, 'BC1/2024-09-18 12:10:55', '2024-09-18 12:10:55', 'Bombe', 4, '2024-09-13 08:00:00'),
(140, 'BC2/2024-09-18 12:12:30', '2024-09-18 12:12:30', 'V2V', 4, '2024-09-13 08:00:00'),
(141, 'BC3/2024-09-18 12:17:51', '2024-09-18 12:17:51', 'btn', 4, '2024-09-13 08:00:00'),
(142, 'BC4/2024-09-18 12:25:11', '2024-09-18 12:25:11', 'trfguhj', 4, '2024-09-13 08:00:00'),
(143, 'BC1/2024-09-19 10:44:42', '2024-09-19 10:44:42', 'Commande 1', 2, '2024-12-17 03:33:23'),
(144, 'BC2/2024-09-19 15:19:28', '2024-09-19 15:19:28', 'Commande 2', 5, '2024-09-13 08:00:00'),
(145, 'BC1/2024-09-24 11:20:18', '2024-09-24 11:20:18', 'bon4', 4, '2024-09-24 11:20:53'),
(146, 'BC2/2024-09-24 12:45:10', '2024-09-24 12:45:10', 'Commande_retest', 2, '2024-09-24 13:18:20'),
(147, 'BC1/2024-09-25 11:08:43', '2024-09-25 11:08:43', 'Commandeur_1', 2, '2024-09-25 11:10:45'),
(148, 'BC2/2024-12-17 03:06:14', '2024-12-17 03:06:14', 'Jhonny Test', 4, '2024-09-13 08:00:00'),
(149, 'BC3/2024-12-17 03:07:56', '2024-12-17 03:07:56', 'mememmem', 1, '2024-09-13 08:00:00'),
(150, 'BC4/2024-12-17 03:25:43', '2024-12-17 03:25:43', 'Menwww', 1, '2024-09-13 08:00:00'),
(151, 'BC1/2024-12-17 16:09:04', '2024-12-17 16:09:04', 'mm', 1, '2024-09-13 08:00:00'),
(152, 'BC1/2024-12-18 12:41:57', '2024-12-18 12:41:57', 'cn', 4, '2024-09-13 08:00:00'),
(153, 'BC1/2024-12-20 14:02:40', '2024-12-20 14:02:40', 'gg', 4, '2024-09-13 08:00:00'),
(154, 'BC1/2024-12-25 17:14:54', '2024-12-25 17:14:54', 'Debu1', 5, '2024-09-13 08:00:00'),
(155, 'BC2/2024-12-25 18:07:44', '2024-12-25 18:07:44', 'debug2', 5, '2024-09-13 08:00:00'),
(156, 'BC1/2024-12-27 04:01:07', '2024-12-27 04:01:07', 'Debug3', 5, '2024-12-27 04:27:54');

-- --------------------------------------------------------

--
-- Structure de la table `bon_commande_produit`
--

CREATE TABLE `bon_commande_produit` (
  `idBC_Pr` int(11) NOT NULL,
  `idbc` int(11) NOT NULL,
  `idP` int(11) NOT NULL,
  `dateadd` datetime NOT NULL,
  `quantite` int(11) NOT NULL,
  `reste_a_livrer` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bon_commande_produit`
--

INSERT INTO `bon_commande_produit` (`idBC_Pr`, `idbc`, `idP`, `dateadd`, `quantite`, `reste_a_livrer`) VALUES
(640, 137, 77, '2024-09-17 14:43:38', 16, 16),
(641, 138, 64, '2024-09-17 14:49:14', 17, 17),
(642, 138, 73, '2024-09-17 14:53:17', 45, 45),
(643, 138, 65, '2024-09-18 10:50:37', 30, 30),
(644, 138, 82, '2024-09-18 11:46:43', 15, 15),
(645, 138, 83, '2024-09-18 11:47:02', 15, 15),
(646, 138, 74, '2024-09-18 11:49:01', 60, 60),
(648, 143, 81, '2024-09-19 10:45:43', 500, 500),
(649, 143, 71, '2024-09-19 10:45:43', 40, 40),
(650, 144, 63, '2024-09-19 15:19:37', 16, 16),
(652, 145, 64, '2024-09-24 11:20:31', 100, 100),
(653, 145, 82, '2024-09-24 11:20:42', 200, 200),
(654, 146, 63, '2024-09-24 12:45:20', 500, 500),
(655, 146, 73, '2024-09-24 12:45:34', 50, 50),
(656, 146, 66, '2024-09-24 12:45:48', 100, 100),
(657, 146, 77, '2024-09-24 12:46:13', 600, 600),
(658, 147, 82, '2024-09-25 11:09:37', 100, 100),
(659, 147, 65, '2024-09-25 11:09:37', 300, 300),
(660, 147, 78, '2024-09-25 11:10:08', 500, 500),
(661, 147, 84, '2024-09-25 11:10:30', 200, 200),
(663, 154, 77, '2024-12-25 18:05:31', 11111, 11111),
(664, 154, 63, '2024-12-25 18:02:38', 3900, 3900),
(666, 155, 63, '2024-12-25 18:12:12', 1090, 1090),
(667, 155, 64, '2024-12-26 18:33:15', 50963333, 50963333),
(668, 155, 74, '2024-12-25 18:18:23', 5, 5),
(669, 155, 64, '2024-12-26 18:33:15', 50963333, 50963333),
(672, 155, 64, '2024-12-26 18:33:15', 50963333, 50963333),
(677, 156, 83, '2024-12-27 04:19:54', 50, 50),
(679, 156, 74, '2024-12-27 04:16:19', 47, 47),
(680, 156, 83, '2024-12-27 04:19:54', 50, 50),
(681, 156, 86, '2024-12-27 04:22:07', 555, 555),
(682, 156, 75, '2024-12-27 04:26:31', 12, 12),
(684, 156, 82, '2024-12-27 04:32:23', 55, 55);

-- --------------------------------------------------------

--
-- Structure de la table `bon_livraison`
--

CREATE TABLE `bon_livraison` (
  `idBL` int(11) NOT NULL,
  `id_bc` int(11) NOT NULL,
  `numBL` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `Etat_Livraison` int(11) NOT NULL DEFAULT 1,
  `nomBL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bon_livraison`
--

INSERT INTO `bon_livraison` (`idBL`, `id_bc`, `numBL`, `date`, `Etat_Livraison`, `nomBL`) VALUES
(104, 146, 1, '2024-09-30 15:05:26', 3, 'Testeur'),
(105, 143, 0, '2024-12-18 12:40:37', 5, 'livret2'),
(106, 143, 0, '2024-12-18 13:00:25', 3, 'onRegarde'),
(107, 147, 0, '2024-12-25 18:14:32', 1, 'salut');

-- --------------------------------------------------------

--
-- Structure de la table `bon_livraison_produit`
--

CREATE TABLE `bon_livraison_produit` (
  `idBL_Pr` int(11) NOT NULL,
  `idBL` int(11) NOT NULL,
  `idP` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix_unitaire` int(11) NOT NULL DEFAULT 1,
  `dateadd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bon_livraison_produit`
--

INSERT INTO `bon_livraison_produit` (`idBL_Pr`, `idBL`, `idP`, `quantite`, `prix_unitaire`, `dateadd`) VALUES
(274, 104, 73, 50, 12000, '2024-09-30 15:06:03'),
(275, 104, 63, 500, 12334, '2024-09-30 15:06:20'),
(276, 104, 66, 60, 6000, '2024-09-30 15:07:37'),
(277, 105, 81, 0, 1234567, '2024-12-18 12:41:02'),
(278, 106, 71, 10, 170000, '2024-12-18 13:00:55'),
(279, 106, 81, 500, 170000, '2024-12-18 13:01:27');

-- --------------------------------------------------------

--
-- Structure de la table `bon_sortie`
--

CREATE TABLE `bon_sortie` (
  `idBS` int(11) NOT NULL,
  `user` varchar(25) NOT NULL,
  `structure` varchar(40) NOT NULL,
  `nom` varchar(30) NOT NULL,
  `prenom` varchar(30) NOT NULL,
  `date_creation` datetime NOT NULL,
  `Etat_bon_sortie` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bon_sortie`
--

INSERT INTO `bon_sortie` (`idBS`, `user`, `structure`, `nom`, `prenom`, `date_creation`, `Etat_bon_sortie`) VALUES
(23, '230007', 'DRIAT', 'Ndiaye', 'Mariama', '2024-09-30 15:11:59', 2),
(24, '230007', 'DRIAT', 'Ndiaye', 'Mariama', '2024-11-28 13:00:14', 2),
(25, '230449', 'Finances', 'Said', 'Mohamed', '2024-12-17 03:34:38', 2),
(26, '230449', 'Finances', 'Said', 'Mohamed', '2024-12-25 16:41:13', 2);

-- --------------------------------------------------------

--
-- Structure de la table `bon_sortie_produit`
--

CREATE TABLE `bon_sortie_produit` (
  `idBSP` int(11) NOT NULL,
  `idP` int(11) NOT NULL,
  `idS` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `dateadd` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `bon_sortie_produit`
--

INSERT INTO `bon_sortie_produit` (`idBSP`, `idP`, `idS`, `quantite`, `dateadd`) VALUES
(73, 65, 23, 1000, '2024-09-30 15:12:19'),
(74, 66, 23, 10, '2024-09-30 15:12:34'),
(75, 77, 23, 60, '2024-09-30 15:12:59'),
(76, 64, 24, 390, '2024-12-27 04:40:48'),
(78, 64, 26, 179, '2024-12-27 04:46:42');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `nom_categorie` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `nom_categorie`) VALUES
(1, 'Ordinateur'),
(2, 'chaise'),
(3, 'Lampe'),
(4, 'Ventilateur'),
(5, 'wyerdftgz'),
(6, ''),
(7, 'sdfg'),
(8, 'categ'),
(9, 'Ancre'),
(10, 'Sac'),
(11, 'test'),
(12, 'Basket'),
(13, 'Chaussettes'),
(23, 'Fourniture Bureau'),
(24, 'Materiel Electronique'),
(25, 'Composant Ordinateur'),
(26, 'Materiel de Classe'),
(27, 'B4C'),
(28, 'bureautique'),
(29, 'mm');

-- --------------------------------------------------------

--
-- Structure de la table `fournisseur`
--

CREATE TABLE `fournisseur` (
  `idF` int(11) NOT NULL,
  `nomF` varchar(50) NOT NULL,
  `prenomF` varchar(50) NOT NULL,
  `adresseF` varchar(60) NOT NULL,
  `telF` varchar(20) NOT NULL,
  `emailF` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `fournisseur`
--

INSERT INTO `fournisseur` (`idF`, `nomF`, `prenomF`, `adresseF`, `telF`, `emailF`) VALUES
(1, 'Diop', 'Abdoulaye', 'Dakar Guediawaye wakinan nimzat rue 234', '773546765', 'abdou@gmail.com'),
(2, 'Fall', 'Moussa', 'Dakar Pikine rue 10', '783334432', 'moussa@gmail.com'),
(4, 'gfhgjh', 'fgh', 'fdhgjgk', '12243636', 'sezd@gmail.com'),
(8, 'Said', 'Mohamad', 'Island', '781311371', 'momosaid@gmail.com');

-- --------------------------------------------------------

--
-- Structure de la table `gestion_types`
--

CREATE TABLE `gestion_types` (
  `idTypes` int(11) NOT NULL,
  `nom_Type` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `gestion_types`
--

INSERT INTO `gestion_types` (`idTypes`, `nom_Type`) VALUES
(1, 'Gestion Universitaire'),
(2, 'Gestion Commerce');

-- --------------------------------------------------------

--
-- Structure de la table `historisation`
--

CREATE TABLE `historisation` (
  `idhistoire` int(11) NOT NULL,
  `nom_historisation` varchar(30) NOT NULL,
  `date_creation` datetime DEFAULT NULL,
  `Dernier_modif` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `historisation`
--

INSERT INTO `historisation` (`idhistoire`, `nom_historisation`, `date_creation`, `Dernier_modif`) VALUES
(101, 'Historique Commande', '2024-09-15 13:02:49', '2024-09-15 13:02:49'),
(102, 'Historique Categorie', '2024-09-15 13:02:49', '2024-09-15 13:02:49'),
(103, 'Historique Sous-Categorie', '2024-09-15 13:06:29', '2024-09-15 13:06:29'),
(104, 'Historique Produit', '2024-09-15 13:06:29', '2024-09-15 13:06:29');

-- --------------------------------------------------------

--
-- Structure de la table `historisation_categorie`
--

CREATE TABLE `historisation_categorie` (
  `idHC` int(11) NOT NULL,
  `idCat` int(11) NOT NULL,
  `date_creation` int(11) NOT NULL,
  `Dernier_modif` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historisation_commande`
--

CREATE TABLE `historisation_commande` (
  `idHCMD` int(11) NOT NULL,
  `idCMD` int(11) NOT NULL,
  `date_creation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historisation_produit`
--

CREATE TABLE `historisation_produit` (
  `idHP` int(11) NOT NULL,
  `idP` int(11) NOT NULL,
  `date_creation` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historisation_souscategorie`
--

CREATE TABLE `historisation_souscategorie` (
  `idHSC` int(11) NOT NULL,
  `idSC` int(11) NOT NULL,
  `date_creation` int(11) NOT NULL,
  `Dernier_modif` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `product`
--

CREATE TABLE `product` (
  `idP` int(11) NOT NULL,
  `nomproduit` varchar(50) DEFAULT NULL,
  `Stock_actuel` int(11) DEFAULT 0,
  `Seuil_limite` int(11) NOT NULL DEFAULT 5,
  `Total` int(11) DEFAULT 0,
  `id_Sous_categorie` int(11) NOT NULL,
  `retrait` int(11) DEFAULT 0,
  `id_statut` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `product`
--

INSERT INTO `product` (`idP`, `nomproduit`, `Stock_actuel`, `Seuil_limite`, `Total`, `id_Sous_categorie`, `retrait`, `id_statut`) VALUES
(63, 'Disque Dur externe 5 tera', 763, 10, 0, 26, 0, 2),
(64, 'Disque Dur externe 2 tera', -469, 10, 0, 26, 0, 1),
(65, 'HUB disque dur orico 2bay usb', 399, 5, 0, 26, 0, 1),
(66, 'Cable HDMI 10m', 140, 20, 0, 17, 0, 2),
(67, 'Cable HDMI 2m', 0, 20, 0, 17, 0, 2),
(68, 'CABLE IMPRIMANTE', 0, 20, 0, 17, 0, 2),
(69, 'Cable Resaux 3m FTP', 0, 20, 0, 17, 0, 2),
(70, 'batterie 9 v 20 boites', 0, 20, 0, 33, 0, 2),
(71, 'batterie PM 20 boites', 10, 10, 0, 33, 0, 2),
(72, 'batterie Moins 20 boites', 0, 20, 0, 33, 0, 2),
(73, 'Bic top 505 M paquets 50', 65, 15, 0, 18, 0, 2),
(74, 'stylo a bille schneider (vert-blue-rouge-noir )  p', 10515, 20, 0, 18, 0, 1),
(75, 'stylo super gel (blue - noir ) pilot 1,0', 0, 10, 0, 18, 0, 2),
(76, 'stilo feutre top line ( noir-rouge-blue -vert ) ', 0, 20, 0, 18, 0, 2),
(77, 'Enveloppes GM kaki paquets de 50', 2, 20, 0, 19, 0, 2),
(78, 'Enveloppes moins kaki paquets de 50', 1000, 25, 0, 19, 0, 2),
(79, 'Enveloppes PM blanc cartons de 500', 0, 50, 0, 19, 0, 2),
(80, 'Enveloppes moins blanc paquets de 500', 0, 50, 0, 19, 0, 2),
(81, 'Enveloppe 1', 500, 20, 0, 19, 0, 2),
(82, 'USB1', 726, 10, 0, 20, 0, 2),
(83, 'USB2', 0, 10, 0, 20, 0, 2),
(84, 'Feutre_1', 300, 50, 0, 15, 0, 2),
(85, 'Bombe1', 0, 2147483647, 0, 29, 0, 2),
(86, 'Mon stylo numéro 1', 0, 190, 0, 18, 0, 2);

-- --------------------------------------------------------

--
-- Structure de la table `souscategorie`
--

CREATE TABLE `souscategorie` (
  `idSC` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `id_categorie` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `souscategorie`
--

INSERT INTO `souscategorie` (`idSC`, `nom`, `id_categorie`) VALUES
(15, 'Feutre', 26),
(16, 'Block Note', 26),
(17, 'Cable', 26),
(18, 'Stylo', 23),
(19, 'Enveloppe', 26),
(20, 'Cle USB', 25),
(21, 'Clavier', 26),
(22, 'Ordinateur', 23),
(23, 'Clavier', 25),
(24, 'Scotch', 23),
(25, 'Ciseaux', 23),
(26, 'Disque Dur', 25),
(27, 'Souligneur', 23),
(28, 'Thermometre', 24),
(29, 'Bombe desodorisant', 23),
(30, 'Postiche', 23),
(31, 'Rallonge', 24),
(32, 'Convertisseur', 26),
(33, 'Batterie', 24),
(34, 'Boow0fe', 13);

-- --------------------------------------------------------

--
-- Structure de la table `status_commande`
--

CREATE TABLE `status_commande` (
  `id_status_cmd` int(11) NOT NULL,
  `nom_status_cmd` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `status_commande`
--

INSERT INTO `status_commande` (`id_status_cmd`, `nom_status_cmd`) VALUES
(1, 'Créé'),
(2, 'Validé'),
(3, 'Terminé'),
(4, 'Supprimé'),
(5, 'Sauvegardé');

-- --------------------------------------------------------

--
-- Structure de la table `statut`
--

CREATE TABLE `statut` (
  `id_statut` int(11) NOT NULL,
  `nom_statut` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `statut`
--

INSERT INTO `statut` (`id_statut`, `nom_statut`) VALUES
(1, 'En Service'),
(2, 'Hors Service');

-- --------------------------------------------------------

--
-- Structure de la table `types`
--

CREATE TABLE `types` (
  `idtypes` int(11) NOT NULL,
  `nom_type` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `types`
--

INSERT INTO `types` (`idtypes`, `nom_type`) VALUES
(1, 'Approvisionnement'),
(2, 'Retrait');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `administrateur`
--
ALTER TABLE `administrateur`
  ADD PRIMARY KEY (`id_admin`);

--
-- Index pour la table `bon_commande`
--
ALTER TABLE `bon_commande`
  ADD PRIMARY KEY (`id_BC`),
  ADD KEY `Etat_commande` (`Etat_commander`);

--
-- Index pour la table `bon_commande_produit`
--
ALTER TABLE `bon_commande_produit`
  ADD PRIMARY KEY (`idBC_Pr`),
  ADD KEY `produit_constraint` (`idP`),
  ADD KEY `bon_commade_constraint` (`idbc`);

--
-- Index pour la table `bon_livraison`
--
ALTER TABLE `bon_livraison`
  ADD PRIMARY KEY (`idBL`),
  ADD KEY `bon_commade` (`id_bc`),
  ADD KEY `Etat_Livraison` (`Etat_Livraison`);

--
-- Index pour la table `bon_livraison_produit`
--
ALTER TABLE `bon_livraison_produit`
  ADD PRIMARY KEY (`idBL_Pr`),
  ADD KEY `livraison_constraint` (`idBL`),
  ADD KEY `product_constraint` (`idP`);

--
-- Index pour la table `bon_sortie`
--
ALTER TABLE `bon_sortie`
  ADD PRIMARY KEY (`idBS`),
  ADD KEY `Etat_bon_sortie` (`Etat_bon_sortie`);

--
-- Index pour la table `bon_sortie_produit`
--
ALTER TABLE `bon_sortie_produit`
  ADD PRIMARY KEY (`idBSP`),
  ADD KEY `Bon_sortie_constraint` (`idS`),
  ADD KEY `idP` (`idP`);

--
-- Index pour la table `categorie`
--
ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`);

--
-- Index pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  ADD PRIMARY KEY (`idF`);

--
-- Index pour la table `gestion_types`
--
ALTER TABLE `gestion_types`
  ADD PRIMARY KEY (`idTypes`);

--
-- Index pour la table `historisation`
--
ALTER TABLE `historisation`
  ADD PRIMARY KEY (`idhistoire`);

--
-- Index pour la table `historisation_categorie`
--
ALTER TABLE `historisation_categorie`
  ADD PRIMARY KEY (`idHC`),
  ADD KEY `idCat` (`idCat`);

--
-- Index pour la table `historisation_commande`
--
ALTER TABLE `historisation_commande`
  ADD PRIMARY KEY (`idHCMD`),
  ADD KEY `idCMD` (`idCMD`);

--
-- Index pour la table `historisation_produit`
--
ALTER TABLE `historisation_produit`
  ADD PRIMARY KEY (`idHP`),
  ADD KEY `idP` (`idP`);

--
-- Index pour la table `historisation_souscategorie`
--
ALTER TABLE `historisation_souscategorie`
  ADD PRIMARY KEY (`idHSC`),
  ADD KEY `idSC` (`idSC`);

--
-- Index pour la table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`idP`),
  ADD KEY `cle_etrangere` (`id_Sous_categorie`),
  ADD KEY `cs` (`id_statut`);

--
-- Index pour la table `souscategorie`
--
ALTER TABLE `souscategorie`
  ADD PRIMARY KEY (`idSC`),
  ADD KEY `id_categorie` (`id_categorie`);

--
-- Index pour la table `status_commande`
--
ALTER TABLE `status_commande`
  ADD PRIMARY KEY (`id_status_cmd`);

--
-- Index pour la table `statut`
--
ALTER TABLE `statut`
  ADD PRIMARY KEY (`id_statut`);

--
-- Index pour la table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`idtypes`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `administrateur`
--
ALTER TABLE `administrateur`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT pour la table `bon_commande`
--
ALTER TABLE `bon_commande`
  MODIFY `id_BC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=157;

--
-- AUTO_INCREMENT pour la table `bon_commande_produit`
--
ALTER TABLE `bon_commande_produit`
  MODIFY `idBC_Pr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=685;

--
-- AUTO_INCREMENT pour la table `bon_livraison`
--
ALTER TABLE `bon_livraison`
  MODIFY `idBL` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT pour la table `bon_livraison_produit`
--
ALTER TABLE `bon_livraison_produit`
  MODIFY `idBL_Pr` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=280;

--
-- AUTO_INCREMENT pour la table `bon_sortie`
--
ALTER TABLE `bon_sortie`
  MODIFY `idBS` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `bon_sortie_produit`
--
ALTER TABLE `bon_sortie_produit`
  MODIFY `idBSP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT pour la table `categorie`
--
ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `fournisseur`
--
ALTER TABLE `fournisseur`
  MODIFY `idF` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `gestion_types`
--
ALTER TABLE `gestion_types`
  MODIFY `idTypes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `historisation`
--
ALTER TABLE `historisation`
  MODIFY `idhistoire` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT pour la table `historisation_categorie`
--
ALTER TABLE `historisation_categorie`
  MODIFY `idHC` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historisation_commande`
--
ALTER TABLE `historisation_commande`
  MODIFY `idHCMD` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historisation_produit`
--
ALTER TABLE `historisation_produit`
  MODIFY `idHP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `historisation_souscategorie`
--
ALTER TABLE `historisation_souscategorie`
  MODIFY `idHSC` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `product`
--
ALTER TABLE `product`
  MODIFY `idP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT pour la table `souscategorie`
--
ALTER TABLE `souscategorie`
  MODIFY `idSC` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT pour la table `status_commande`
--
ALTER TABLE `status_commande`
  MODIFY `id_status_cmd` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `statut`
--
ALTER TABLE `statut`
  MODIFY `id_statut` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `types`
--
ALTER TABLE `types`
  MODIFY `idtypes` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bon_commande`
--
ALTER TABLE `bon_commande`
  ADD CONSTRAINT `Etat_commande` FOREIGN KEY (`Etat_commander`) REFERENCES `status_commande` (`id_status_cmd`);

--
-- Contraintes pour la table `bon_commande_produit`
--
ALTER TABLE `bon_commande_produit`
  ADD CONSTRAINT `bon_commade_constraint` FOREIGN KEY (`idbc`) REFERENCES `bon_commande` (`id_BC`),
  ADD CONSTRAINT `produit_constraint` FOREIGN KEY (`idP`) REFERENCES `product` (`idP`);

--
-- Contraintes pour la table `bon_livraison`
--
ALTER TABLE `bon_livraison`
  ADD CONSTRAINT `Etat_Livraison` FOREIGN KEY (`Etat_Livraison`) REFERENCES `status_commande` (`id_status_cmd`),
  ADD CONSTRAINT `bon_commade` FOREIGN KEY (`id_bc`) REFERENCES `bon_commande` (`id_BC`);

--
-- Contraintes pour la table `bon_sortie`
--
ALTER TABLE `bon_sortie`
  ADD CONSTRAINT `bon_sortie_ibfk_1` FOREIGN KEY (`Etat_bon_sortie`) REFERENCES `status_commande` (`id_status_cmd`);

--
-- Contraintes pour la table `bon_sortie_produit`
--
ALTER TABLE `bon_sortie_produit`
  ADD CONSTRAINT `Bon_sortie_constraint` FOREIGN KEY (`idS`) REFERENCES `bon_sortie` (`idBS`),
  ADD CONSTRAINT `bon_sortie_produit_ibfk_1` FOREIGN KEY (`idP`) REFERENCES `product` (`idP`);

--
-- Contraintes pour la table `historisation_categorie`
--
ALTER TABLE `historisation_categorie`
  ADD CONSTRAINT `historisation_categorie_ibfk_1` FOREIGN KEY (`idCat`) REFERENCES `categorie` (`id_categorie`);

--
-- Contraintes pour la table `historisation_commande`
--
ALTER TABLE `historisation_commande`
  ADD CONSTRAINT `historisation_commande_ibfk_1` FOREIGN KEY (`idCMD`) REFERENCES `bon_commande` (`id_BC`);

--
-- Contraintes pour la table `historisation_produit`
--
ALTER TABLE `historisation_produit`
  ADD CONSTRAINT `historisation_produit_ibfk_1` FOREIGN KEY (`idP`) REFERENCES `product` (`idP`);

--
-- Contraintes pour la table `historisation_souscategorie`
--
ALTER TABLE `historisation_souscategorie`
  ADD CONSTRAINT `historisation_souscategorie_ibfk_1` FOREIGN KEY (`idSC`) REFERENCES `souscategorie` (`idSC`);

--
-- Contraintes pour la table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `cle_etrangere` FOREIGN KEY (`id_Sous_categorie`) REFERENCES `souscategorie` (`idSC`),
  ADD CONSTRAINT `cs` FOREIGN KEY (`id_statut`) REFERENCES `statut` (`id_statut`);

--
-- Contraintes pour la table `souscategorie`
--
ALTER TABLE `souscategorie`
  ADD CONSTRAINT `souscategorie_ibfk_1` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
