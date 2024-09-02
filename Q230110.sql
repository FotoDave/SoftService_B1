-- phpMyAdmin SQL Dump
-- version 5.2.1-1.el8.remi
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost
-- Généré le : dim. 05 mai 2024 à 11:26
-- Version du serveur : 8.0.36
-- Version de PHP : 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `Q230110`
--

-- --------------------------------------------------------

--
-- Structure de la table `web1_tblMembersNewsletter`
--

CREATE TABLE `web1_tblMembersNewsletter` (
  `id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `timestamp` int NOT NULL,
  `firstname` text NOT NULL,
  `lastname` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

--
-- Déchargement des données de la table `web1_tblMembersNewsletter`
--

INSERT INTO `web1_tblMembersNewsletter` (`id`, `email`, `timestamp`, `firstname`, `lastname`) VALUES
(1, 'albert_einstein@student.helmo.be', 1552986513, 'Albert', 'EINSTEIN');

-- --------------------------------------------------------

--
-- Structure de la table `webB1_Actualite`
--

CREATE TABLE `webB1_Actualite` (
  `aid` int NOT NULL,
  `date_actualite` date NOT NULL,
  `intitule` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `image` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `amorce` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `actualite` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `did` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `webB1_Actualite`
--

INSERT INTO `webB1_Actualite` (`aid`, `date_actualite`, `intitule`, `image`, `amorce`, `actualite`, `visible`, `did`) VALUES
(2, '2024-04-28', 'Gestion de projet informatique', 'debf2job-5382501_1280.jpg', 'La gestion de projets informatiques est cruciale pour le succès des initiatives technologiques modernes.', 'La gestion de projets informatiques est cruciale pour le succès des initiatives technologiques modernes. Elle implique une planification rigoureuse, une coordination étroite des équipes et un suivi continu des progrès pour respecter les délais et les budgets. Les méthodologies agiles, comme Scrum et Kanban, sont souvent privilégiées pour leur flexibilité et leur capacité à s\'adapter aux changements rapides. Les outils de gestion de projet, tels que Jira et Asana, facilitent la collaboration en temps réel et assurent une transparence accrue. La clé d\'une gestion réussie réside dans la communication efficace, une compréhension claire des objectifs et l\'engagement de toutes les parties prenantes.', 1, NULL),
(3, '2024-04-30', 'Planification des tâches', '8bf0aman-593333_1280.jpg', 'La planification des tâches en milieu professionnel est essentielle pour optimiser la productivité et garantir l\'efficacité des équipes.', 'La planification des tâches en milieu professionnel est essentielle pour optimiser la productivité et garantir l\'efficacité des équipes. Elle permet de définir clairement les priorités, d\'allouer les ressources adéquates et de respecter les délais. L\'utilisation d\'outils numériques modernes, tels que Asana, Trello ou Microsoft Planner, facilite la visualisation des projets, la collaboration en temps réel et le suivi des progrès. En adoptant des techniques de gestion du temps comme la méthode Pomodoro ou la matrice Eisenhower, les professionnels peuvent mieux gérer leurs charges de travail et minimiser le stress. La clé d\'une planification réussie repose sur la flexibilité et l\'adaptation continue aux imprévus.', 1, NULL),
(4, '2024-05-03', 'Efficacité du Cloud', 'b5f65network-2402637_1280.jpg', 'L\'efficacité du cloud computing en milieu professionnel représente une révolution dans la manière dont les entreprises stockent des données, déploient des applications et collaborent à l\'échelle mondiale.', 'L\'efficacité du cloud computing en milieu professionnel représente une révolution dans la manière dont les entreprises stockent des données, déploient des applications et collaborent à l\'échelle mondiale. Le cloud offre une flexibilité sans précédent, permettant aux équipes de travailler de manière synchronisée, quel que soit leur emplacement géographique. Grâce à des solutions comme Amazon Web Services, Microsoft Azure et Google Cloud, les entreprises bénéficient de mises à jour en temps réel, d\'une évolutivité facile et d\'une réduction significative des coûts d\'infrastructure. La sécurité renforcée et la récupération rapide en cas de sinistre sont d\'autres atouts majeurs. En somme, le cloud augmente l\'agilité des entreprises, leur permettant de s\'adapter rapidement aux changements du marché et d\'innover continuellement.', 1, 1),
(5, '2024-05-03', 'Protection contre les failles de sécurité', '80741fingerprint-2904774_1280.jpg', 'La protection contre les failles de sécurité en milieu professionnel est une priorité absolue à l\'ère du numérique. Pour défendre les infrastructures critiques et les données sensibles, les entreprises doivent adopter une approche proactive en matière de cybersécurité.', 'La protection contre les failles de sécurité en milieu professionnel est une priorité absolue à l\'ère du numérique. Pour défendre les infrastructures critiques et les données sensibles, les entreprises doivent adopter une approche proactive en matière de cybersécurité. Cela inclut la mise en œuvre de systèmes de détection et de réponse aux incidents (SIEM), des audits réguliers de sécurité, et des formations continues sur les meilleures pratiques de sécurité pour les employés. L\'utilisation de technologies de pointe telles que le chiffrement de données, la multi-authentification, et les pare-feu avancés est essentielle pour prévenir les intrusions et les pertes de données. De plus, les organisations doivent se tenir informées des dernières menaces et travailler en étroite collaboration avec des experts en sécurité pour adapter continuellement leurs stratégies de défense face à un paysage de menaces en constante évolution.', 1, NULL),
(6, '2024-05-05', 'Quelques conseils en immobilier', '36deareal-estate-6688945_1280.jpg', 'Le secteur immobilier est un pilier essentiel de l\'économie mondiale, offrant des opportunités d\'investissement et un refuge pour la croissance patrimoniale.', 'Le secteur immobilier est un pilier essentiel de l\'économie mondiale, offrant des opportunités d\'investissement et un refuge pour la croissance patrimoniale. Qu\'il s\'agisse de l\'achat d\'une maison familiale ou d\'un investissement dans un immeuble commercial, l\'immobilier reste un choix privilégié pour sa stabilité à long terme. Avec les taux d\'intérêt fluctuants, la digitalisation croissante et les tendances émergentes telles que le co-living et les espaces de travail flexibles, le marché évolue rapidement. Les professionnels et les investisseurs doivent être informés des réglementations locales, des dynamiques de marché et des innovations technologiques pour naviguer avec succès dans ce domaine en pleine transformation.', 0, 2),
(7, '2024-05-06', 'Le marché boursier', '57e57entrepreneur-1340649_1280.jpg', 'Le marché boursier est un moteur vital de l\'économie mondiale, permettant aux entreprises de lever des fonds et aux investisseurs d\'acheter une part de leur succès', 'Le marché boursier est un moteur vital de l\'économie mondiale, permettant aux entreprises de lever des fonds et aux investisseurs d\'acheter une part de leur succès. Les actions, obligations et indices offrent diverses opportunités d\'investissement et servent de baromètre à la confiance des investisseurs. La volatilité des marchés, influencée par les politiques économiques, les avancées technologiques et les événements mondiaux, crée des risques, mais aussi des gains potentiels pour ceux qui savent naviguer dans les tendances changeantes. Pour réussir dans ce paysage, il est crucial d\'avoir une stratégie bien définie, d\'étudier les fondamentaux des entreprises et de rester informé des mouvements du marché en temps réel.', 0, 3);

-- --------------------------------------------------------

--
-- Structure de la table `webB1_Departement`
--

CREATE TABLE `webB1_Departement` (
  `did` int NOT NULL,
  `departement` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `webB1_Departement`
--

INSERT INTO `webB1_Departement` (`did`, `departement`, `libelle`) VALUES
(1, 'Développement', NULL),
(2, 'Commerciale', 'Ceci concerne le département commercial'),
(3, 'Consultance', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `webB1_Fonction`
--

CREATE TABLE `webB1_Fonction` (
  `fid` int NOT NULL,
  `pid` int NOT NULL,
  `rid` int DEFAULT NULL,
  `did` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `webB1_Fonction`
--

INSERT INTO `webB1_Fonction` (`fid`, `pid`, `rid`, `did`) VALUES
(3, 2, 3, 1),
(4, 3, 2, 1),
(5, 3, 1, 3),
(6, 3, 1, 2),
(7, 4, 2, 2),
(8, 4, 2, 3);

-- --------------------------------------------------------

--
-- Structure de la table `webB1_Personnel`
--

CREATE TABLE `webB1_Personnel` (
  `pid` int NOT NULL,
  `prenom` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `nom` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `courriel` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `mot_passe` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `telephone` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `photo` varchar(50) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_520_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `webB1_Personnel`
--

INSERT INTO `webB1_Personnel` (`pid`, `prenom`, `nom`, `courriel`, `mot_passe`, `telephone`, `photo`, `description`) VALUES
(2, 'Coln', 'Jane', 'j.coln@gmail.com', '$2y$10$8zauDqaU.fGbQD45fFDqBez7pUhe.DThGb44EHbkvL0ejNgG/MY.G', '456465', '8c670profil.png', 'Bon employé'),
(3, 'Dave Wallace', 'Foto Foto', 'davewallacefotofoto@gmail.com', '$2y$10$RXkfLjEgrX.eVoeRX9KiOeOKU/oENdIwEs2eu4v4Mn5YCmoFl/yQa', '0465868173', '344bbprofil.png', 'erzaera'),
(4, 'Stark', 'Kanhan', 'k.stark@gmail.com', '$2y$10$1cq1S1DS0wtQOVaknXFSM.WLtqywn/ArzMfVdB4RAX2hvqeeuobpS', '456788231', 'ead79man-8647994_1280.png', 'Employé dynamique;\r\nJe suis qualifié pour être nommé au service Consultance');

-- --------------------------------------------------------

--
-- Structure de la table `webB1_Role`
--

CREATE TABLE `webB1_Role` (
  `rid` int NOT NULL,
  `role` varchar(50) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Déchargement des données de la table `webB1_Role`
--

INSERT INTO `webB1_Role` (`rid`, `role`) VALUES
(1, 'Assistant'),
(2, 'Responsable'),
(3, 'Dévéloppeur'),
(4, 'Graphiste'),
(5, 'Comptable'),
(6, 'Commerciale');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `web1_tblMembersNewsletter`
--
ALTER TABLE `web1_tblMembersNewsletter`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Index pour la table `webB1_Actualite`
--
ALTER TABLE `webB1_Actualite`
  ADD PRIMARY KEY (`aid`),
  ADD KEY `did` (`did`);

--
-- Index pour la table `webB1_Departement`
--
ALTER TABLE `webB1_Departement`
  ADD PRIMARY KEY (`did`);

--
-- Index pour la table `webB1_Fonction`
--
ALTER TABLE `webB1_Fonction`
  ADD PRIMARY KEY (`fid`),
  ADD KEY `pid` (`pid`),
  ADD KEY `rid` (`rid`),
  ADD KEY `did` (`did`);

--
-- Index pour la table `webB1_Personnel`
--
ALTER TABLE `webB1_Personnel`
  ADD PRIMARY KEY (`pid`);

--
-- Index pour la table `webB1_Role`
--
ALTER TABLE `webB1_Role`
  ADD PRIMARY KEY (`rid`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `web1_tblMembersNewsletter`
--
ALTER TABLE `web1_tblMembersNewsletter`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `webB1_Actualite`
--
ALTER TABLE `webB1_Actualite`
  MODIFY `aid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `webB1_Departement`
--
ALTER TABLE `webB1_Departement`
  MODIFY `did` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `webB1_Fonction`
--
ALTER TABLE `webB1_Fonction`
  MODIFY `fid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `webB1_Personnel`
--
ALTER TABLE `webB1_Personnel`
  MODIFY `pid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `webB1_Role`
--
ALTER TABLE `webB1_Role`
  MODIFY `rid` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `webB1_Actualite`
--
ALTER TABLE `webB1_Actualite`
  ADD CONSTRAINT `webB1_Actualite_ibfk_1` FOREIGN KEY (`did`) REFERENCES `webB1_Departement` (`did`);

--
-- Contraintes pour la table `webB1_Fonction`
--
ALTER TABLE `webB1_Fonction`
  ADD CONSTRAINT `webB1_Fonction_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `webB1_Personnel` (`pid`),
  ADD CONSTRAINT `webB1_Fonction_ibfk_2` FOREIGN KEY (`rid`) REFERENCES `webB1_Role` (`rid`),
  ADD CONSTRAINT `webB1_Fonction_ibfk_3` FOREIGN KEY (`did`) REFERENCES `webB1_Departement` (`did`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
