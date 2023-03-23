-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 22 mars 2023 à 09:02
-- Version du serveur : 10.4.24-MariaDB
-- Version de PHP : 8.2.0

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
DROP DATABASE IF EXISTS blog;
CREATE DATABASE blog;

use blog;

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `idArticle` int(11) NOT NULL,
  `contenu` varchar(512) NOT NULL,
  `datePubli` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `idAuteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`idArticle`, `contenu`, `datePubli`, `idAuteur`) VALUES
(1, 'Ca c\'est depuis le compte admin mais c\'est modifié 2', '2023-03-17 20:43:03', 2),
(2, 'Ca c\'est depuis le compte admin mais c\'est modifié 2', '2023-03-17 20:43:03', 2),
(3, 'Ca c\'est depuis le compte admin mais c\'est modifié 2', '2023-03-17 20:43:03', 2),
(4, 'Ca c\'est depuis le compte admin mais c\'est modifié 2', '2023-03-17 20:43:03', 2),
(5, 'Ca c\'est depuis le compte admin mais c\'est modifié 2', '2023-03-17 20:43:03', 1);

-- --------------------------------------------------------

--
-- Structure de la table `liker`
--

CREATE TABLE `liker` (
  `idUser` int(11) NOT NULL,
  `idArticle` int(11) NOT NULL,
  `liker` enum('1','-1') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `liker`
--

INSERT INTO `liker` (`idUser`, `idArticle`, `liker`) VALUES
(1, 1, '1'),
(1, 2, '-1'),
(1, 3, '-1'),
(1, 4, '-1');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `idUser` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `role` enum('moderator','publisher') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`idUser`, `username`, `password`, `role`) VALUES
(1, 'florent', 'florent', 'moderator'),
(2, 'yael', 'yael', 'publisher'),
(3, 'autre', 'autre', NULL);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`idArticle`);

--
-- Index pour la table `liker`
--
ALTER TABLE `liker`
  ADD PRIMARY KEY (`idUser`,`idArticle`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`idUser`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `idArticle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
