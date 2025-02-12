-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mar. 17 déc. 2024 à 15:47
-- Version du serveur : 5.7.24
-- Version de PHP : 8.3.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `mon_petit_panier`
--

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

CREATE TABLE `commandes` (
  `id_commande` int(11) NOT NULL,
  `date_commande` date DEFAULT NULL,
  `id_user` int(11) DEFAULT NULL,
  `montant` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id_commande`, `date_commande`, `id_user`, `montant`) VALUES
(1, '2024-12-17', 4, 8),
(2, '2024-12-17', 4, 8),
(3, '2024-12-17', 4, 8),
(4, '2024-12-17', 4, 8),
(5, '2024-12-17', 4, 8),
(6, '2024-12-17', 4, 8),
(7, '2024-12-17', 4, 8),
(8, '2024-12-17', 4, 8),
(9, '2024-12-17', 4, 46),
(10, '2024-12-17', 4, 74),
(11, '2024-12-17', 4, 9);

-- --------------------------------------------------------

--
-- Structure de la table `ingredient`
--

CREATE TABLE `ingredient` (
  `id_ingredient` int(11) NOT NULL,
  `ingredients` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `ingredient`
--

INSERT INTO `ingredient` (`id_ingredient`, `ingredients`) VALUES
(1, 'pomme'),
(2, 'pate'),
(3, 'beurre'),
(4, 'sucre'),
(5, 'canelle'),
(6, 'sauce tomate'),
(7, 'bechamelle'),
(8, 'boeuf'),
(9, 'vinaigre'),
(10, 'riz'),
(11, 'saumon'),
(12, 'orties'),
(13, 'eau'),
(14, 'saucisse'),
(15, 'persil'),
(16, 'fraise'),
(17, 'mascarpone'),
(18, 'biscuit'),
(19, 'chantilly'),
(20, 'crème patissière'),
(21, 'pate à l\'amande'),
(22, 'amande'),
(23, 'magnioc'),
(24, 'tomate'),
(25, 'banane plantain'),
(26, 'chaire de tomate'),
(27, 'pain'),
(28, 'ail'),
(29, 'brioche_vapeur'),
(30, 'viande'),
(31, 'vermicelles'),
(32, 'pousse soja'),
(33, 'carotte'),
(34, 'nems'),
(35, 'menthe'),
(36, 'salade'),
(37, 'crevette'),
(38, 'sauce nem'),
(39, 'oeuf'),
(40, 'lait'),
(41, 'chocolat'),
(42, 'sauce pimentée'),
(43, 'fromage'),
(44, 'champignon'),
(45, 'epinard'),
(46, 'peperonni'),
(47, 'patate'),
(48, 'caramel');

-- --------------------------------------------------------

--
-- Structure de la table `paniers`
--

CREATE TABLE `paniers` (
  `id_panier` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `id_plat` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `paniers`
--

INSERT INTO `paniers` (`id_panier`, `id_user`, `id_plat`, `quantite`) VALUES
(84, 4, 4, 8),
(85, 4, 3, 4);

-- --------------------------------------------------------

--
-- Structure de la table `recettes`
--

CREATE TABLE `recettes` (
  `id_plat` int(11) NOT NULL,
  `nom_recette` varchar(255) DEFAULT NULL,
  `poids` varchar(255) DEFAULT NULL,
  `origine` varchar(255) DEFAULT NULL,
  `prix` int(255) DEFAULT NULL,
  `type_recette` varchar(255) DEFAULT NULL,
  `type_plat` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `recettes`
--

INSERT INTO `recettes` (`id_plat`, `nom_recette`, `poids`, `origine`, `prix`, `type_recette`, `type_plat`, `photo`, `description`) VALUES
(1, 'Tarte tatin', '1.5kg', 'France', 10, 'vegetarien', 'entree', 'photo/tarte-tatin.jpg', 'Une bonne tarte a la pomme faite maison pour le plaisir du sucré.'),
(2, 'Lasagne', '2kg', 'Italie', 12, 'normal', 'plat', 'photo/lasagne.jpg', 'Un plat typique italien qui change des pâtes classiques.'),
(3, 'Sushi', '40g', 'Japon', 11, 'normal', 'plat', 'photo/sushi.webp', 'Le plat traditionnel japonais parfait pour une soirée entre amis.'),
(4, 'Soupe au orties', '300g', 'Inconnue', 4, 'vegetarien', 'plat', 'photo/ortie.jpg', 'Une soupe peu classique mais délicieuse. Parfait si vous voulez changer d\'horizons.'),
(5, 'Rougaille saucisse', '1.2kg', 'Ile de la Réunion', 12, 'normal', 'plat', 'photo/rougaille.webp', 'Un délicieux mélange de saucisses et de riz. Si vous voulez un plat solide, Celui ci est recommandé.'),
(6, 'Magnolia', '400g', 'Turquie', 8, 'normal', 'dessert', 'photo/magnolia.webp', 'Un dessert inconnu, mais une merveille turque. Il faut le voir pour le croire.'),
(7, 'Paris Brest', '350g', 'France', 9, 'normal', 'dessert', 'photo/paris-brest.jpg', 'Le dessert du parisien par exellence. Si il vous faut du sucre, ne cherchez pas plus loin.'),
(8, 'Foufou sauce graine', '600g', 'Afrique', 11, 'normal', 'plat', 'photo/foufou.jpg', 'Un plat inconnu au bataillon. Il ne faut pourtant pas se fier au apparence du foufou sauce graine.'),
(9, 'Pan tumaca', '200g', 'Mexique', 9, 'normal', 'entrée', 'photo/tumaca.jpg', 'Une entrée légère mais délicieuse, le Pan tumaca viens ajouter du gout à vos compositions.'),
(10, 'Nikuman', '200g', 'Japon', 8, 'normal', 'plat', 'photo/nikuman.jpeg', 'De la viande entourée de brioche à l\'air bon n\'est-ce pas ? Et bien c\'est aussi bon que dans vos rêves.'),
(11, 'Bo bun', '1.2kg', 'Vietnam', 14, 'vegetarien', 'plat', 'photo/bo-bun.jpg', 'Le plat vietnamien par exellence. Il y a tout dans ce plat. Si vous voulez manger a votre faim, un Bo bun suffit.'),
(12, 'Rouleaux de printemps', '150g', 'Japon', 3, 'vegetarien', 'entrée', 'photo/rouleau.jpg', 'Le rouleau de printemps, une entrée simple mais efficace dont tout le monde raffole.'),
(13, 'Tarte au chocolat', '1.1kg', 'Australie', 7, 'normal', 'dessert', 'photo/tarte-chocolat.jpg', 'Une tarte contenant du chocolat, aussi simple et bon que ça.'),
(14, 'Topokki', '300g', 'Corée', 4, 'normal', 'plat', 'photo/topokki.webp', 'Un plat coréen qui vient ajouter du piquant a votre journée.'),
(15, 'Koulibiac', '1.1kg', 'Russie', 13, 'normal', 'plat', 'photo/koulibiac.jpg', 'Un des plat familiaux les plus sain et nutritif possible. Succès en famille garantie.'),
(16, 'Pizza', '1kg', 'Italie', 11, 'normal', 'plat', 'photo/pizza.webp', 'Le plat le plus culte d\'italie, la pizza. Sous toutes ses variantes elle restera le plat le plus varié et délicieux.'),
(17, 'Patata de tortilla', '700g', 'Espagne', 8, 'normal', 'plat', 'photo/tortilla.jpg', 'Un plat espagnol qui remplit. Je vous conseille d\'ajouter un peu de citron dessus, le résultat est suprenant.'),
(18, 'Buche de Noël', '1.3kg', 'Inconnue', 10, 'normal', 'dessert', 'photo/buche.jpg', 'Le plat de noël par exellence. Totalement conseillé pour les fêtes de noël en famille.'),
(19, 'Tigre qui pleure', '1.2kg', 'France', 12, 'normal', 'plat', 'photo/tigre.jpg', 'De la bonne viande accompagné avec une sauce et du riz qui vous fera gouter le septième ciel.');

-- --------------------------------------------------------

--
-- Structure de la table `recettes-ingredient`
--

CREATE TABLE `recettes-ingredient` (
  `id_main` int(11) NOT NULL,
  `id_ingredient` int(11) DEFAULT NULL,
  `id_plat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `recettes-ingredient`
--

INSERT INTO `recettes-ingredient` (`id_main`, `id_ingredient`, `id_plat`) VALUES
(13, 12, 4),
(14, 13, 4),
(15, 14, 5),
(16, 6, 5),
(17, 15, 5),
(18, 10, 5),
(19, 16, 6),
(20, 17, 6),
(21, 18, 6),
(22, 19, 6),
(23, 20, 7),
(24, 21, 7),
(25, 22, 7),
(26, 23, 8),
(27, 24, 8),
(28, 8, 8),
(29, 25, 8),
(30, 26, 9),
(31, 27, 9),
(32, 28, 9),
(33, 15, 9),
(34, 29, 10),
(35, 30, 10),
(36, 31, 11),
(37, 32, 11),
(38, 33, 11),
(39, 34, 11),
(40, 35, 11),
(41, 36, 11),
(42, 37, 12),
(43, 35, 12),
(44, 32, 12),
(45, 36, 12),
(46, 38, 12),
(47, 2, 13),
(48, 39, 13),
(49, 40, 13),
(50, 41, 13),
(51, 10, 14),
(52, 39, 14),
(53, 42, 14),
(54, 43, 14),
(55, 39, 15),
(56, 11, 15),
(57, 44, 15),
(58, 10, 15),
(59, 45, 15),
(60, 6, 16),
(61, 24, 16),
(62, 44, 16),
(63, 46, 16),
(64, 43, 16),
(65, 2, 16),
(66, 39, 17),
(67, 40, 17),
(68, 47, 17),
(69, 41, 10),
(70, 48, 10),
(71, 2, 10),
(72, 40, 10),
(158, 2, 2),
(159, 6, 2),
(160, 7, 2),
(161, 8, 2),
(166, 4, 18),
(167, 39, 18),
(168, 40, 18),
(169, 41, 18),
(170, 8, 19),
(171, 10, 19),
(172, 15, 19),
(173, 31, 19),
(174, 36, 19),
(198, 1, 1),
(199, 2, 1),
(200, 3, 1),
(201, 4, 1),
(202, 5, 1),
(205, 1, 21),
(206, 11, 21),
(207, 9, 3),
(208, 10, 3),
(209, 11, 3);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id_user` int(11) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `mdp` varchar(255) DEFAULT NULL,
  `statut` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id_user`, `nom`, `mdp`, `statut`) VALUES
(4, 'Aomigo', '$2y$10$dzx0/nfSPs9cywdgz/aIdOXSs3UA4ShAZs3oFVX.V5xylYJsBu8V2', 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `commandes`
--
ALTER TABLE `commandes`
  ADD PRIMARY KEY (`id_commande`),
  ADD UNIQUE KEY `id_commande` (`id_commande`);

--
-- Index pour la table `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`id_ingredient`),
  ADD UNIQUE KEY `id_ingredient` (`id_ingredient`);

--
-- Index pour la table `paniers`
--
ALTER TABLE `paniers`
  ADD PRIMARY KEY (`id_panier`),
  ADD UNIQUE KEY `id_panier` (`id_panier`);

--
-- Index pour la table `recettes`
--
ALTER TABLE `recettes`
  ADD PRIMARY KEY (`id_plat`),
  ADD UNIQUE KEY `id_plat` (`id_plat`);

--
-- Index pour la table `recettes-ingredient`
--
ALTER TABLE `recettes-ingredient`
  ADD PRIMARY KEY (`id_main`),
  ADD UNIQUE KEY `id_main` (`id_main`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id_user`),
  ADD UNIQUE KEY `id_user` (`id_user`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `commandes`
--
ALTER TABLE `commandes`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `id_ingredient` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT pour la table `paniers`
--
ALTER TABLE `paniers`
  MODIFY `id_panier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=86;

--
-- AUTO_INCREMENT pour la table `recettes`
--
ALTER TABLE `recettes`
  MODIFY `id_plat` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT pour la table `recettes-ingredient`
--
ALTER TABLE `recettes-ingredient`
  MODIFY `id_main` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=210;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
