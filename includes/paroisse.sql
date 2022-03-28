-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le : Mer 22 Mars 2017 à 15:46
-- Version du serveur: 5.5.16
-- Version de PHP: 5.3.8

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `paroisse2017`
--

-- --------------------------------------------------------

--
-- Structure de la table `activite`
--

CREATE TABLE IF NOT EXISTS `activite` (
  `idactivite` int(11) NOT NULL AUTO_INCREMENT,
  `nomActivite` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date NOT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idactivite`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

-- --------------------------------------------------------

--
-- Structure de la table `arrondissement`
--

CREATE TABLE IF NOT EXISTS `arrondissement` (
  `idarrondissement` int(11) NOT NULL AUTO_INCREMENT,
  `arrondissement` varchar(100) NOT NULL,
  `departement_iddepartement` int(100) NOT NULL,
  `lisible` tinyint(1) NOT NULL,
  PRIMARY KEY (`idarrondissement`),
  KEY `departement_iddepartement` (`departement_iddepartement`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=312 ;

-- --------------------------------------------------------

--
-- Structure de la table `bapteme`
--

CREATE TABLE IF NOT EXISTS `bapteme` (
  `idbapteme` int(11) NOT NULL AUTO_INCREMENT,
  `dateBaptise` date DEFAULT NULL,
  `lieu_baptise` varchar(100) NOT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  `fidele_idfidele` int(11) NOT NULL,
  `date_enregistrement` date NOT NULL,
  PRIMARY KEY (`idbapteme`),
  KEY `fidele_idfidele` (`fidele_idfidele`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2525 ;

-- --------------------------------------------------------

--
-- Structure de la table `confirmation`
--

CREATE TABLE IF NOT EXISTS `confirmation` (
  `idconfirmation` int(11) NOT NULL AUTO_INCREMENT,
  `date_confirmation` date NOT NULL,
  `lieu_confirmation` varchar(100) NOT NULL,
  `fidele_idfidele` int(11) NOT NULL,
  `lisible` tinyint(1) NOT NULL,
  `date_enregistrement` date NOT NULL,
  PRIMARY KEY (`idconfirmation`),
  KEY `fidele_idfidele` (`fidele_idfidele`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1644 ;

-- --------------------------------------------------------

--
-- Structure de la table `connexion`
--

CREATE TABLE IF NOT EXISTS `connexion` (
  `idconnexion` int(11) NOT NULL AUTO_INCREMENT,
  `dateConnexion` date DEFAULT NULL,
  `heureDebut` time DEFAULT NULL,
  `heureFin` time DEFAULT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  `utilisateur_idutilisateur` int(11) NOT NULL,
  PRIMARY KEY (`idconnexion`),
  KEY `fk_connexion_utilisateur1_idx` (`utilisateur_idutilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=727 ;

-- --------------------------------------------------------

--
-- Structure de la table `conseil`
--

CREATE TABLE IF NOT EXISTS `conseil` (
  `idconseil` int(11) NOT NULL AUTO_INCREMENT,
  `lisible` tinyint(1) NOT NULL DEFAULT '1',
  `date_conseil` date NOT NULL,
  PRIMARY KEY (`idconseil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `contribution`
--

CREATE TABLE IF NOT EXISTS `contribution` (
  `idcontribution` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(60) NOT NULL,
  `lisible` tinyint(1) NOT NULL,
  PRIMARY KEY (`idcontribution`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Structure de la table `contributionfidele`
--

CREATE TABLE IF NOT EXISTS `contributionfidele` (
  `idcontributionfidele` int(11) NOT NULL AUTO_INCREMENT,
  `fidele_idfidele` int(11) NOT NULL,
  `contribution_idcontribution` int(11) NOT NULL,
  `typecontribution` varchar(50) NOT NULL,
  `montant` float DEFAULT NULL,
  `date` date NOT NULL,
  `lisible` tinyint(1) NOT NULL DEFAULT '1',
  `saintescene_idsaintescene` int(11) NOT NULL,
  `recu` tinyint(1) NOT NULL DEFAULT '0',
  `utilisateur_idutilisateur` int(11) NOT NULL,
  `heure` time NOT NULL,
  PRIMARY KEY (`idcontributionfidele`),
  KEY `fidele_idfidele` (`fidele_idfidele`),
  KEY `contribution_idcontribution` (`contribution_idcontribution`),
  KEY `saintescene_idsaintescene` (`saintescene_idsaintescene`),
  KEY `utilisateur_idutilisateur` (`utilisateur_idutilisateur`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6271 ;

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

CREATE TABLE IF NOT EXISTS `departement` (
  `iddepartement` int(11) NOT NULL AUTO_INCREMENT,
  `departement` varchar(100) NOT NULL,
  `region_idregion` int(11) NOT NULL,
  `lisible` tinyint(1) NOT NULL,
  PRIMARY KEY (`iddepartement`),
  KEY `region_idregion` (`region_idregion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=61 ;

-- --------------------------------------------------------

--
-- Structure de la table `droit`
--

CREATE TABLE IF NOT EXISTS `droit` (
  `iddroit` int(11) NOT NULL AUTO_INCREMENT,
  `nomDroit` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  `modules_idmodule` int(11) NOT NULL,
  PRIMARY KEY (`iddroit`),
  KEY `modules_idmodule` (`modules_idmodule`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Structure de la table `fidele`
--

CREATE TABLE IF NOT EXISTS `fidele` (
  `idfidele` int(11) NOT NULL AUTO_INCREMENT,
  `codeFidele` varchar(45) NOT NULL,
  `statut` varchar(45) DEFAULT NULL,
  `etat` tinyint(1) NOT NULL DEFAULT '0',
  `lisible` tinyint(1) DEFAULT '1',
  `estgroupe` tinyint(1) NOT NULL DEFAULT '0',
  `personne_idpersonne` int(11) NOT NULL,
  `date_inscription` varchar(4) NOT NULL,
  PRIMARY KEY (`idfidele`,`personne_idpersonne`),
  UNIQUE KEY `codeFidele_UNIQUE` (`codeFidele`),
  KEY `fk_fidele_personne1_idx` (`personne_idpersonne`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2981 ;

-- --------------------------------------------------------

--
-- Structure de la table `fideleconseil`
--

CREATE TABLE IF NOT EXISTS `fideleconseil` (
  `idfideleconseil` int(11) NOT NULL AUTO_INCREMENT,
  `fidele_idfidele` int(11) NOT NULL,
  `conseil_idconseil` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `date` date NOT NULL,
  `rapport` text NOT NULL,
  `lisible` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`idfideleconseil`),
  KEY `fidelepersonne_idpersonne` (`fidele_idfidele`),
  KEY `conseil_idconseil` (`conseil_idconseil`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

-- --------------------------------------------------------

--
-- Structure de la table `fidelegroupe`
--

CREATE TABLE IF NOT EXISTS `fidelegroupe` (
  `idfideleGroupe` int(11) NOT NULL AUTO_INCREMENT,
  `lisible` tinyint(1) DEFAULT NULL,
  `fidele_idfidele` int(11) NOT NULL,
  `groupe_idgroupe` int(11) NOT NULL,
  `date_inscription` varchar(4) NOT NULL,
  PRIMARY KEY (`idfideleGroupe`),
  KEY `fk_fideleGroupe_fidele1_idx` (`fidele_idfidele`),
  KEY `groupe_idgroupe` (`groupe_idgroupe`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

-- --------------------------------------------------------

--
-- Structure de la table `fidelesaintescene`
--

CREATE TABLE IF NOT EXISTS `fidelesaintescene` (
  `idfidelesintescene` int(11) NOT NULL AUTO_INCREMENT,
  `lisible` tinyint(1) NOT NULL DEFAULT '1',
  `fidele_idfidele` int(11) NOT NULL,
  `saintescene_idsaintescene` int(11) NOT NULL,
  `contribution` float NOT NULL,
  `remarque` text NOT NULL,
  `date_contribution` date NOT NULL,
  `recu` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`idfidelesintescene`),
  KEY `contribution` (`contribution`),
  KEY `saintescene_idsaintescene` (`saintescene_idsaintescene`),
  KEY `fidele_idfidele` (`fidele_idfidele`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

-- --------------------------------------------------------

--
-- Structure de la table `grade`
--

CREATE TABLE IF NOT EXISTS `grade` (
  `idgrade` int(11) NOT NULL AUTO_INCREMENT,
  `nomgrade` varchar(20) NOT NULL,
  `estPris` tinyint(1) NOT NULL DEFAULT '0',
  `lisible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idgrade`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `groupe`
--

CREATE TABLE IF NOT EXISTS `groupe` (
  `idgroupe` int(11) NOT NULL AUTO_INCREMENT,
  `nomGroupe` varchar(45) DEFAULT NULL,
  `typeGroupe` varchar(45) NOT NULL,
  `dateCreation` date DEFAULT NULL,
  `lisible` varchar(45) DEFAULT 'true',
  PRIMARY KEY (`idgroupe`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=101 ;

-- --------------------------------------------------------

--
-- Structure de la table `groupeactivite`
--

CREATE TABLE IF NOT EXISTS `groupeactivite` (
  `idgroupeActivite` int(11) NOT NULL AUTO_INCREMENT,
  `groupe_idgroupe` int(11) NOT NULL,
  `activite_idactivite` int(11) NOT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idgroupeActivite`),
  KEY `fk_groupeActivite_groupe1_idx` (`groupe_idgroupe`),
  KEY `fk_groupeActivite_activite1_idx` (`activite_idactivite`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Structure de la table `malade`
--

CREATE TABLE IF NOT EXISTS `malade` (
  `idmalade` int(11) NOT NULL AUTO_INCREMENT,
  `guide` varchar(100) DEFAULT NULL,
  `dateEnregistrementMaladie` date DEFAULT NULL,
  `dateDebutMaladie` date DEFAULT NULL,
  `dateEnregistrementGuerison` date DEFAULT NULL,
  `dateGuerison` date DEFAULT NULL,
  `dateEnregistrementDeces` date DEFAULT NULL,
  `dateDeces` date DEFAULT NULL,
  `est_retabli` tinyint(1) NOT NULL,
  `est_decede` tinyint(1) NOT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  `fidele_idfidele` int(11) NOT NULL,
  `residence` varchar(255) NOT NULL,
  PRIMARY KEY (`idmalade`,`fidele_idfidele`),
  KEY `fk_malade_fidele1_idx` (`fidele_idfidele`),
  KEY `guide` (`guide`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=63 ;

-- --------------------------------------------------------

--
-- Structure de la table `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `idmodule` int(11) NOT NULL AUTO_INCREMENT,
  `nomModule` varchar(45) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idmodule`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=12 ;

-- --------------------------------------------------------

--
-- Structure de la table `occurrence`
--

CREATE TABLE IF NOT EXISTS `occurrence` (
  `idoccurrence` int(11) NOT NULL AUTO_INCREMENT,
  `mensuel` int(11) NOT NULL DEFAULT '0',
  `extraordinaire` int(11) NOT NULL DEFAULT '0',
  `elargi` int(11) NOT NULL DEFAULT '0',
  `consistoire` int(11) NOT NULL DEFAULT '0',
  `cinode` int(11) NOT NULL DEFAULT '0',
  `retraite` int(11) NOT NULL DEFAULT '0',
  `nombrehomme` int(11) NOT NULL DEFAULT '1',
  `nombrefemme` int(11) NOT NULL DEFAULT '1',
  `nombregarcon` int(11) NOT NULL DEFAULT '1',
  `nombrefille` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idoccurrence`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `parametre`
--

CREATE TABLE IF NOT EXISTS `parametre` (
  `idparametre` int(11) NOT NULL AUTO_INCREMENT,
  `sigle` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `siege` varchar(100) NOT NULL,
  `bp` int(11) NOT NULL,
  `ville` varchar(255) NOT NULL,
  `email_paroisse` varchar(255) NOT NULL,
  `site_web` varchar(255) NOT NULL,
  `telephone` varchar(20) NOT NULL,
  `date_paroisse` date NOT NULL,
  `logo` varchar(255) NOT NULL,
  PRIMARY KEY (`idparametre`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Structure de la table `pasteur`
--

CREATE TABLE IF NOT EXISTS `pasteur` (
  `idpasteur` int(11) NOT NULL AUTO_INCREMENT,
  `grade` int(11) NOT NULL,
  `adresse` varchar(100) NOT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  `personne_idpersonne` int(11) NOT NULL,
  PRIMARY KEY (`idpasteur`,`personne_idpersonne`),
  KEY `fk_pasteur_personne1_idx` (`personne_idpersonne`),
  KEY `grade` (`grade`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Structure de la table `personne`
--

CREATE TABLE IF NOT EXISTS `personne` (
  `idpersonne` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(45) NOT NULL,
  `prenom` varchar(45) DEFAULT NULL,
  `datenaiss` date NOT NULL,
  `lieunaiss` varchar(60) NOT NULL,
  `sexe` varchar(10) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `profession` varchar(45) DEFAULT NULL,
  `zone_idzone` int(11) NOT NULL,
  `telephone` varchar(40) NOT NULL,
  `pere` varchar(100) NOT NULL,
  `pere_vivant` tinyint(1) NOT NULL,
  `mere` varchar(100) NOT NULL,
  `mere_vivant` tinyint(1) NOT NULL,
  `photo` varchar(45) NOT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  `domaine` varchar(100) NOT NULL,
  `diplome` varchar(100) NOT NULL,
  `annee_enregistrement` year(4) NOT NULL,
  `statut_pro` varchar(100) NOT NULL,
  `employeur` varchar(100) NOT NULL,
  `village` varchar(100) NOT NULL,
  `arrondissement` int(100) NOT NULL,
  `etablissement` varchar(100) NOT NULL,
  `niveau` varchar(100) NOT NULL,
  `serie` varchar(100) NOT NULL,
  `situation_matri` varchar(60) NOT NULL,
  `conjoint` varchar(255) NOT NULL,
  `nombre_enfant` int(11) NOT NULL,
  `religion_conjoint` varchar(100) NOT NULL,
  `date_enregistrement` date NOT NULL,
  PRIMARY KEY (`idpersonne`),
  KEY `arrondissement` (`arrondissement`),
  KEY `zone_idzone` (`zone_idzone`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3019 ;

-- --------------------------------------------------------

--
-- Structure de la table `region`
--

CREATE TABLE IF NOT EXISTS `region` (
  `idregion` int(11) NOT NULL AUTO_INCREMENT,
  `region` varchar(100) NOT NULL,
  `lisible` tinyint(1) NOT NULL,
  PRIMARY KEY (`idregion`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Structure de la table `role`
--

CREATE TABLE IF NOT EXISTS `role` (
  `idrole` int(11) NOT NULL AUTO_INCREMENT,
  `nomRole` varchar(45) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idrole`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Structure de la table `roledroit`
--

CREATE TABLE IF NOT EXISTS `roledroit` (
  `idroleDroit` int(11) NOT NULL AUTO_INCREMENT,
  `hasDroit` tinyint(1) DEFAULT '0',
  `droit_iddroit` int(11) NOT NULL,
  `role_idrole` int(11) NOT NULL,
  PRIMARY KEY (`idroleDroit`),
  KEY `fk_roleDroit_droit1_idx` (`droit_iddroit`),
  KEY `fk_roleDroit_role1_idx` (`role_idrole`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=310 ;

-- --------------------------------------------------------

--
-- Structure de la table `saintescene`
--

CREATE TABLE IF NOT EXISTS `saintescene` (
  `idsaintescene` int(11) NOT NULL AUTO_INCREMENT,
  `valide` tinyint(1) DEFAULT '1',
  `mois` varchar(10) NOT NULL,
  `annee` int(11) NOT NULL,
  `lisible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idsaintescene`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Structure de la table `userrole`
--

CREATE TABLE IF NOT EXISTS `userrole` (
  `iduserRole` int(11) NOT NULL AUTO_INCREMENT,
  `has_role` tinyint(1) DEFAULT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  `utilisateur_idutilisateur` int(11) NOT NULL,
  `role_idrole` int(11) NOT NULL,
  PRIMARY KEY (`iduserRole`),
  KEY `fk_userRole_utilisateur1_idx` (`utilisateur_idutilisateur`),
  KEY `fk_userRole_role1_idx` (`role_idrole`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE IF NOT EXISTS `utilisateur` (
  `idutilisateur` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  `lisible` tinyint(1) DEFAULT '1',
  `personne_idpersonne` int(11) NOT NULL,
  PRIMARY KEY (`idutilisateur`),
  KEY `fk_utilisateur_personne1_idx` (`personne_idpersonne`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Structure de la table `zone`
--

CREATE TABLE IF NOT EXISTS `zone` (
  `idzone` int(11) NOT NULL AUTO_INCREMENT,
  `nomzone` varchar(50) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `lisible` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`idzone`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `arrondissement`
--
ALTER TABLE `arrondissement`
  ADD CONSTRAINT `arrondissement_ibfk_1` FOREIGN KEY (`departement_iddepartement`) REFERENCES `departement` (`iddepartement`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `bapteme`
--
ALTER TABLE `bapteme`
  ADD CONSTRAINT `bapteme_ibfk_1` FOREIGN KEY (`fidele_idfidele`) REFERENCES `fidele` (`idfidele`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `confirmation`
--
ALTER TABLE `confirmation`
  ADD CONSTRAINT `confirmation_ibfk_1` FOREIGN KEY (`fidele_idfidele`) REFERENCES `fidele` (`idfidele`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `connexion`
--
ALTER TABLE `connexion`
  ADD CONSTRAINT `connexion_ibfk_1` FOREIGN KEY (`utilisateur_idutilisateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `contributionfidele`
--
ALTER TABLE `contributionfidele`
  ADD CONSTRAINT `contributionfidele_ibfk_1` FOREIGN KEY (`contribution_idcontribution`) REFERENCES `contribution` (`idcontribution`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contributionfidele_ibfk_2` FOREIGN KEY (`fidele_idfidele`) REFERENCES `fidele` (`idfidele`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contributionfidele_ibfk_3` FOREIGN KEY (`saintescene_idsaintescene`) REFERENCES `saintescene` (`idsaintescene`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `contributionfidele_ibfk_4` FOREIGN KEY (`utilisateur_idutilisateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `departement`
--
ALTER TABLE `departement`
  ADD CONSTRAINT `departement_ibfk_1` FOREIGN KEY (`region_idregion`) REFERENCES `region` (`idregion`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `droit`
--
ALTER TABLE `droit`
  ADD CONSTRAINT `droit_ibfk_1` FOREIGN KEY (`modules_idmodule`) REFERENCES `modules` (`idmodule`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fidele`
--
ALTER TABLE `fidele`
  ADD CONSTRAINT `fidele_ibfk_1` FOREIGN KEY (`personne_idpersonne`) REFERENCES `personne` (`idpersonne`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fideleconseil`
--
ALTER TABLE `fideleconseil`
  ADD CONSTRAINT `fideleconseil_ibfk_2` FOREIGN KEY (`conseil_idconseil`) REFERENCES `conseil` (`idconseil`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fideleconseil_ibfk_3` FOREIGN KEY (`fidele_idfidele`) REFERENCES `fidele` (`idfidele`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fidelegroupe`
--
ALTER TABLE `fidelegroupe`
  ADD CONSTRAINT `fidelegroupe_ibfk_1` FOREIGN KEY (`fidele_idfidele`) REFERENCES `fidele` (`idfidele`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fidelegroupe_ibfk_2` FOREIGN KEY (`groupe_idgroupe`) REFERENCES `groupe` (`idgroupe`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `fidelesaintescene`
--
ALTER TABLE `fidelesaintescene`
  ADD CONSTRAINT `fidelesaintescene_ibfk_1` FOREIGN KEY (`saintescene_idsaintescene`) REFERENCES `saintescene` (`idsaintescene`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fidelesaintescene_ibfk_2` FOREIGN KEY (`fidele_idfidele`) REFERENCES `fidele` (`idfidele`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `groupeactivite`
--
ALTER TABLE `groupeactivite`
  ADD CONSTRAINT `groupeactivite_ibfk_1` FOREIGN KEY (`groupe_idgroupe`) REFERENCES `groupe` (`idgroupe`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `groupeactivite_ibfk_2` FOREIGN KEY (`activite_idactivite`) REFERENCES `activite` (`idactivite`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `malade`
--
ALTER TABLE `malade`
  ADD CONSTRAINT `malade_ibfk_1` FOREIGN KEY (`fidele_idfidele`) REFERENCES `fidele` (`idfidele`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `pasteur`
--
ALTER TABLE `pasteur`
  ADD CONSTRAINT `pasteur_ibfk_1` FOREIGN KEY (`personne_idpersonne`) REFERENCES `personne` (`idpersonne`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pasteur_ibfk_2` FOREIGN KEY (`grade`) REFERENCES `grade` (`idgrade`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `personne`
--
ALTER TABLE `personne`
  ADD CONSTRAINT `personne_ibfk_2` FOREIGN KEY (`arrondissement`) REFERENCES `arrondissement` (`idarrondissement`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `personne_ibfk_3` FOREIGN KEY (`zone_idzone`) REFERENCES `zone` (`idzone`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `roledroit`
--
ALTER TABLE `roledroit`
  ADD CONSTRAINT `roledroit_ibfk_1` FOREIGN KEY (`droit_iddroit`) REFERENCES `droit` (`iddroit`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `roledroit_ibfk_2` FOREIGN KEY (`role_idrole`) REFERENCES `role` (`idrole`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `userrole`
--
ALTER TABLE `userrole`
  ADD CONSTRAINT `userrole_ibfk_1` FOREIGN KEY (`utilisateur_idutilisateur`) REFERENCES `utilisateur` (`idutilisateur`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `userrole_ibfk_2` FOREIGN KEY (`role_idrole`) REFERENCES `role` (`idrole`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `utilisateur_ibfk_1` FOREIGN KEY (`personne_idpersonne`) REFERENCES `personne` (`idpersonne`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


/* INSERTION DES DONNEES */

-- Les regions
INSERT INTO `region` (`idregion`, `region`, `lisible`) VALUES
(1, 'ADAMAOUA', 1),
(2, 'CENTRE', 1),
(3, 'EST', 1),
(4, 'EXTREME-NORD', 1),
(5, 'LITTORAL', 1),
(6, 'NORD', 1),
(7, 'NORD-OUEST', 1),
(8, 'OUEST', 1),
(9, 'SUD', 1),
(10, 'SUD-OUEST', 1);

-- les départements

INSERT INTO `departement` (`iddepartement`, `departement`, `region_idregion`, `lisible`) VALUES
(3, 'DJEREM', 1, 1),
(4, 'FARO ET DÉO', 1, 1),
(5, 'MAYO BANYO', 1, 1),
(6, 'MBERE', 1, 1),
(7, 'VINA', 1, 1),
(8, 'HAUTE SANAGA', 2, 1),
(9, 'LEKIE', 2, 1),
(10, 'MBAM ET INOUBOU', 2, 1),
(11, 'MBAM ET KIM', 2, 1),
(12, 'MEFOU ET AFAMBA', 2, 1),
(13, 'MEFOU ET AKONO', 2, 1),
(14, 'MFOUNDI', 2, 1),
(15, 'NYONG ET KELLE', 2, 1),
(16, 'NYONG ET MFOUMOU', 2, 1),
(17, 'NYONG ET SO''O', 2, 1),
(18, 'BOUMBA ET NGOKO', 3, 1),
(19, 'HAUT NYONG ', 3, 1),
(20, 'KADEY', 3, 1),
(21, 'LOM ET DJEREM', 3, 1),
(22, 'DIAMARE', 4, 1),
(23, 'LOGONE ET CHARI', 4, 1),
(24, 'MAYO DANAY', 4, 1),
(25, 'MAYO KANI', 4, 1),
(26, 'MAYO SAVA', 4, 1),
(27, 'MAYO TSANAGA', 4, 1),
(28, 'MOUNGO', 5, 1),
(29, 'NKAM', 5, 1),
(30, 'SANAGA MARITIME', 5, 1),
(31, 'WOURI', 5, 1),
(32, 'BENOUE', 6, 1),
(33, 'FARO', 6, 1),
(34, 'MAYO LOUTI', 6, 1),
(35, 'MAYO REY', 6, 1),
(36, 'BUI', 7, 1),
(37, 'BOYO', 7, 1),
(38, 'DONGA-MANTUNG', 7, 1),
(39, 'MENCHUM', 7, 1),
(40, 'MEZAM', 7, 1),
(41, 'MOMO', 7, 1),
(42, 'NGO-KENTUNJIA', 7, 1),
(43, 'BAMBOUTOS', 8, 1),
(44, 'HAUT NKAM', 8, 1),
(45, 'HAUTS PLATEAUX', 8, 1),
(46, 'KOUNG KHI', 8, 1),
(47, 'MENOUA ', 8, 1),
(48, 'MIFI', 8, 1),
(49, 'NDE', 8, 1),
(50, 'NOUN', 8, 1),
(51, 'DJA ET LOBO', 9, 1),
(52, 'VALLEE DU NTEM', 9, 1),
(53, 'MVILA', 9, 1),
(54, 'OCEAN', 9, 1),
(55, 'FAKO', 10, 1),
(56, 'KUPE MANENGUBA ', 10, 1),
(57, 'LEBIALEM', 10, 1),
(58, 'MANYU', 10, 1),
(59, 'MEME', 10, 1),
(60, 'NDIAN', 10, 1);

   -- les arrondissements --
INSERT INTO `arrondissement` (`idarrondissement`, `arrondissement`, `departement_iddepartement`, `lisible`) VALUES
(1, 'NGAOUNDAL', 3, 1),
(2, 'BAMENDJOU', 45, 1),
(3, 'TIBATI', 3, 1),
(4, 'MAYO-BALEO', 4, 1),
(5, 'GALIM-TIGNERE', 4, 1),
(6, 'TIGNERE', 4, 1),
(7, 'BANYO ', 5, 1),
(8, 'MEIGANGA', 6, 1),
(9, 'BANKIM', 5, 1),
(10, 'DJOHONG', 6, 1),
(11, 'DIR', 6, 1),
(12, 'NGAOUNDERE 1er', 7, 1),
(13, 'NGAOUNDERE 2e', 7, 1),
(14, 'NGAOUNDERE 3e', 7, 1),
(15, 'BELEL', 7, 1),
(16, 'MBE', 7, 1),
(17, 'NGANHA', 7, 1),
(18, 'NYAMBAKA', 7, 1),
(19, 'MARTAP', 7, 1),
(20, '     MBANDJOCK    ', 8, 1),
(21, 'MINTA', 8, 1),
(22, 'NANGA-EBOKO', 8, 1),
(23, 'NKOTENG', 8, 1),
(24, 'EVODOULA', 9, 1),
(25, 'MONATELE', 9, 1),
(27, 'OBALA  ', 9, 1),
(28, 'OKOLA  ', 9, 1),
(29, 'SA''A', 9, 1),
(30, 'ELIG-MFOMO', 9, 1),
(31, 'EBEBDA', 9, 1),
(32, 'BAFIA', 10, 1),
(33, 'BOKITO', 10, 1),
(34, 'DEUK', 10, 1),
(35, 'MAKENENE', 10, 1),
(36, 'NDIKINIMEKI  ', 10, 1),
(37, 'OMBESSA', 10, 1),
(38, 'KIIKI', 10, 1),
(39, 'KON-YAMBETTA', 10, 1),
(40, 'NTUI', 11, 1),
(41, 'NGAMBE', 11, 1),
(42, 'NGORO', 11, 1),
(43, 'YOKO', 11, 1),
(44, 'MBANGASSINA', 11, 1),
(45, 'MFOU', 12, 1),
(46, 'ESSE', 12, 1),
(47, 'AWAE', 12, 1),
(48, 'SOA', 12, 1),
(49, 'NGOUMOU', 13, 1),
(50, 'AKONO', 13, 1),
(51, 'MBANKOMO', 13, 1),
(52, 'BIKOK', 13, 1),
(53, 'YAOUNDE I', 14, 1),
(54, 'YAOUNDE II', 14, 1),
(55, 'YAOUNDE III', 14, 1),
(56, 'YAOUNDE IV', 14, 1),
(57, 'YAOUNDE V', 14, 1),
(58, 'YAOUNDE VI', 14, 1),
(59, 'YAOUNDE VII', 14, 1),
(60, 'BOT-MAKAK ', 15, 1),
(61, 'ESEKA', 15, 1),
(62, 'MAKAK', 15, 1),
(63, 'MESSONDO', 15, 1),
(64, 'NGOG-MAPUBI', 15, 1),
(65, 'MATOMB', 15, 1),
(66, 'DIBANG', 15, 1),
(67, 'AKONOLINGA  ', 16, 1),
(68, 'AYOS  ', 16, 1),
(69, 'ENDOM', 16, 1),
(70, 'DZENG', 17, 1),
(71, 'MBALMAYO', 17, 1),
(72, 'NGOMEDZAP', 17, 1),
(73, 'MOLOUNDOU    ', 18, 1),
(74, 'SALAPOUMBE', 18, 1),
(75, 'GARI-GOMBO', 18, 1),
(76, 'ABONG-MBANG    ', 19, 1),
(77, 'DOUME', 19, 1),
(78, 'LOMIE ', 19, 1),
(79, 'MESSAMENA', 19, 1),
(80, 'NGUELEMENDOUKA ', 19, 1),
(81, 'DIMAKO', 19, 1),
(82, 'NGOYLA', 19, 1),
(83, 'BATOURI  ', 20, 1),
(84, 'NDELELE  ', 20, 1),
(85, 'KETTE  ', 20, 1),
(86, 'MBANG', 20, 1),
(87, 'BERTOUA 1er', 21, 1),
(88, 'BERTOUA 2e', 21, 1),
(89, ' BETARE-OYA ', 21, 1),
(90, 'BELABO', 21, 1),
(91, 'GAROUA-BOULAÏ', 21, 1),
(92, 'DIANG', 21, 1),
(93, 'MANDJOU', 21, 1),
(94, 'BOGO ', 22, 1),
(95, 'MAROUA 1er', 22, 1),
(96, 'MAROUA 2e', 22, 1),
(97, 'MAROUA 3e', 22, 1),
(98, 'MERI', 22, 1),
(99, 'GAZAWA', 22, 1),
(100, 'PETTE', 22, 1),
(101, 'KOUSSERI', 23, 1),
(102, 'MAKARY', 23, 1),
(103, 'LOGONE-BIRNI ', 23, 1),
(104, 'GOULFEY', 23, 1),
(105, 'WAZA', 23, 1),
(106, 'FOTOKOL', 23, 1),
(107, 'HILE-HALIFA', 23, 1),
(108, 'BLANGOUA', 23, 1),
(109, 'DARAK', 23, 1),
(110, 'KAR-HAY', 24, 1),
(111, 'DATCHEKA', 24, 1),
(112, 'YAGOUA', 24, 1),
(113, 'GUERE', 24, 1),
(114, 'MAGA', 24, 1),
(115, 'KALFOU', 24, 1),
(116, 'WINA', 24, 1),
(117, 'VELE', 24, 1),
(118, 'TCHATIBALI', 24, 1),
(119, 'GOBO', 24, 1),
(120, 'KAÏ-KAÏ', 24, 1),
(121, 'KAELE', 25, 1),
(122, 'GUIDIGUIS', 25, 1),
(123, 'MINDIF', 25, 1),
(124, 'MOUTOURWA', 25, 1),
(125, 'MOULVOUDAYE', 25, 1),
(126, 'PORHI', 25, 1),
(127, 'TAIBONG', 25, 1),
(128, 'MORA', 26, 1),
(129, 'TOKOMBERE', 26, 1),
(130, 'KOLOFATA', 26, 1),
(131, 'MOKOLO', 27, 1),
(132, 'BOURRHA', 27, 1),
(133, 'KOZA', 27, 1),
(134, 'HINA', 27, 1),
(135, 'MOGODE', 27, 1),
(136, 'MAYO-MASKOTA', 27, 1),
(137, 'DIBOMBARI ', 28, 1),
(138, 'LOUM', 28, 1),
(139, 'MANJO', 28, 1),
(140, 'MBANGA', 28, 1),
(141, 'MELONG', 28, 1),
(142, 'NKONGSAMBA 1er', 28, 1),
(143, 'NKONGSAMBA 2e', 28, 1),
(144, 'NKONGSAMBA 3e', 28, 1),
(145, 'NLONAKO', 28, 1),
(146, 'BARE-BAKEM', 28, 1),
(147, 'NJOMBE-PENJA', 28, 1),
(148, 'NKONDJOCK', 29, 1),
(149, 'YABASSI', 29, 1),
(150, 'YINDI', 29, 1),
(151, 'DIZANGE', 30, 1),
(152, 'EDEA 1er', 30, 1),
(153, 'EDEA 2e', 30, 1),
(154, 'NDOM ', 30, 1),
(155, 'NGAMBE ', 30, 1),
(156, 'POUMA', 30, 1),
(157, 'MOUANKO', 30, 1),
(158, 'DIBAMBA', 30, 1),
(159, 'NGWEI', 30, 1),
(160, 'DOUALA 1er', 31, 1),
(161, 'DOUALA 2e', 31, 1),
(162, 'DOUALA 3e', 31, 1),
(163, 'DOUALA 4e', 31, 1),
(164, 'DOUALA 5e', 31, 1),
(165, 'DOUALA 6e', 31, 1),
(166, 'MANOKA', 31, 1),
(167, 'GAROUA 1er', 32, 1),
(168, 'GAROUA 2e', 32, 1),
(169, 'GAROUA 3e', 32, 1),
(170, 'BIBEMI', 32, 1),
(171, 'PITOA', 32, 1),
(172, 'LAGDO', 32, 1),
(173, 'DEMBO', 32, 1),
(174, 'TCHEBOA', 32, 1),
(175, 'MAYO HOURNA', 32, 1),
(176, 'POLI', 33, 1),
(177, 'BEKA', 33, 1),
(178, 'GUIDER', 34, 1),
(179, 'PAYO-OULO', 34, 1),
(180, 'FIGUIL', 34, 1),
(181, 'REY-BOUBA', 35, 1),
(182, 'TCHOLLIRE  ', 35, 1),
(183, 'TOUBORO', 35, 1),
(184, 'JAKIRI', 36, 1),
(185, 'KUMBO', 36, 1),
(186, 'OKU', 36, 1),
(187, 'MBVEN', 36, 1),
(188, 'NONI', 36, 1),
(189, 'NKUM', 36, 1),
(190, 'FUNDONG', 37, 1),
(191, 'BELO', 37, 1),
(192, 'BUM', 37, 1),
(193, 'NJINIKOM', 37, 1),
(194, 'NKAMBE', 38, 1),
(195, 'NWA', 38, 1),
(196, 'AKO', 38, 1),
(197, 'MISAJE', 38, 1),
(198, 'NDU', 38, 1),
(199, 'WUM', 39, 1),
(200, 'FURU-AWA', 39, 1),
(201, 'MENCHUM VALLEY', 39, 1),
(202, 'FUNGOM', 39, 1),
(203, 'BAMENDA 1er', 40, 1),
(204, 'BAMENDA 2e', 40, 1),
(205, 'BAMENDA 3e', 40, 1),
(206, 'BALI', 40, 1),
(207, 'TUBAH', 40, 1),
(208, 'BAFUT', 40, 1),
(209, 'SANTA', 40, 1),
(210, 'BATIBO', 41, 1),
(211, 'MBENGWI', 41, 1),
(212, 'NJIKWA', 41, 1),
(213, 'NGIE', 41, 1),
(214, 'WIDIKUM-MENKA', 41, 1),
(215, 'NDOP', 42, 1),
(216, 'BABESSI', 42, 1),
(217, 'BALIKUMBAT', 42, 1),
(218, 'MBOUDA', 43, 1),
(219, 'GALIM', 43, 1),
(220, 'BATCHAM', 43, 1),
(221, 'BABADJOU', 43, 1),
(222, 'BAFANG', 44, 1),
(223, 'BANA', 44, 1),
(224, 'BANDJA', 44, 1),
(225, 'KEKEM', 44, 1),
(226, 'BAKOU', 44, 1),
(227, 'BANKA', 44, 1),
(228, 'BAHAM', 45, 1),
(230, 'BANGOU', 45, 1),
(231, 'POUMOUGNE', 46, 1),
(232, 'BAYANGAM', 46, 1),
(233, 'DSCHANG', 47, 1),
(234, 'PENKA-MICHEL', 47, 1),
(235, 'FOKOUE', 47, 1),
(236, 'NKONG-NI', 47, 1),
(237, 'SANTCHOU', 47, 1),
(238, 'FONGO TONGO', 47, 1),
(239, 'BAFOUSSAM 1er', 48, 1),
(240, 'BAFOUSSAM 2e', 48, 1),
(241, 'BAFOUSSAM 3e', 48, 1),
(242, 'BANGANGTE', 49, 1),
(245, 'BAZOU', 49, 1),
(246, 'TONGA', 49, 1),
(247, 'FOUMBAN', 50, 1),
(248, 'FOUMBOT', 50, 1),
(249, 'MALENTOUEN', 50, 1),
(250, 'MASSANGAM', 50, 1),
(251, 'MAGBA', 50, 1),
(252, 'KOUTABA', 50, 1),
(253, 'BANGOURAIN', 50, 1),
(254, 'KOUOPTAMO', 50, 1),
(255, 'NJIMON', 50, 1),
(256, 'BENGBIS', 51, 1),
(257, 'DJOUM', 51, 1),
(258, 'SANGMELIMA', 51, 1),
(259, 'ZOETELE', 51, 1),
(260, 'OVENG', 51, 1),
(261, 'MINTOM ', 51, 1),
(262, 'MEYOMESSALA', 51, 1),
(263, 'MEYOMESSI', 51, 1),
(264, 'AMBAM', 52, 1),
(265, 'MA''AN', 52, 1),
(266, 'OLAMZE', 52, 1),
(267, 'KYE OSSI', 52, 1),
(268, 'EBOLOWA 1er', 53, 1),
(269, 'EBOLOWA 2e', 53, 1),
(270, 'BIWONG-BANE', 53, 1),
(271, 'MVANGAN', 53, 1),
(272, 'MENGONG', 53, 1),
(273, 'NGOULEMAKONG', 53, 1),
(274, 'EFOULAN', 53, 1),
(275, 'BIWONG BULU', 53, 1),
(276, 'AKOM II', 54, 1),
(277, 'CAMPO ', 54, 1),
(278, 'KRIBI 1er', 54, 1),
(279, 'KRIBI 2e', 54, 1),
(280, 'LOLODORF', 54, 1),
(281, 'MVENGUE', 54, 1),
(282, 'BIPINDI', 54, 1),
(283, 'LOKOUNDJE', 54, 1),
(284, 'MUYUKA', 55, 1),
(285, 'TIKO', 55, 1),
(286, 'LIMBE 1er  ', 55, 1),
(287, 'LIMBE 2e', 55, 1),
(288, 'LIMBE 3e', 55, 1),
(289, 'BUEA', 55, 1),
(290, 'BANGEM', 56, 1),
(291, 'NGUTI', 56, 1),
(292, 'TOMBEL', 56, 1),
(293, 'FONTEM', 57, 1),
(294, 'ALOU', 57, 1),
(295, 'WABANE', 57, 1),
(296, 'AKWAYA', 58, 1),
(297, 'MAMFE', 58, 1),
(298, 'EYUMODJOCK', 58, 1),
(299, 'UPPER-BAYANG', 58, 1),
(300, 'KUMBA 1er', 59, 1),
(301, 'KUMBA 2e', 59, 1),
(302, 'KUMBA 3e', 59, 1),
(303, 'KONYE', 59, 1),
(304, 'BONGE', 59, 1),
(305, 'BAMUSSO', 60, 1),
(306, 'EKONDO-TITI ', 60, 1),
(307, 'ISANGUELE', 60, 1),
(308, 'MUNDEMBA', 60, 1),
(309, 'KOMBO ABEDIMO', 60, 1),
(310, 'KOMBO IDINTI', 60, 1),
(311, 'IDABATO', 60, 1);

-- les parametres du Système --
INSERT INTO `parametre` (`idparametre`, `sigle`, `nom`, `siege`, `bp`, `ville`, `email_paroisse`, `site_web`, `telephone`, `date_paroisse`, `logo`) VALUES
(1, 'EEC', 'EGLISE EVANGELIQUE DU CAMEROUN', 'BIYEM-ASSI', 13501, 'YAOUNDE', 'info@eec-biyemassi.net', 'www.eec-biyemassi.net', '222 31 67 58', '1987-01-01', 'C:\\wamp\\www\\Church/report/Ch9f.jpg');

-- les modules --
INSERT INTO `modules` (`idmodule`, `nomModule`, `description`) VALUES
(1, 'Gestion des utlisateurs', 'Module de gestion des utilisateurs'),
(2, 'Gestion des fideles', 'Module de gestion des fideles'),
(3, 'Gestion des malades', 'Module de gestion des malades'),
(4, 'Gestion des groupes', 'Module de gestion des groupes'),
(5, 'Gestion du conseil d''anciens', 'Module de gestion du conseils des anciens'),
(6, 'Gestion des activites', 'Module de gestions des activites de la paroisse'),
(7, 'Gestion sainte cene', 'Module de gestion de la sainte cene'),
(8, 'Gestion des pasteurs', 'Module de gestion du college pastoral'),
(9, 'Newsletters', 'Module de gestion des newsletters'),
(10, 'Gestion des collectes', 'Module de gestion de toutes les operations financieres'),
(11, 'Parametres', 'Modules de configuration des parametres du systeme');

-- les roles --
INSERT INTO `role` (`idrole`, `nomRole`, `description`, `lisible`) VALUES
(1, 'Administrateur', 'Administrateur du système', 1),
(2, 'Secretaire', 'Secrétaire de la paroisse', 1),
(3, 'Pasteur', 'Pasteur de la paroisse', 1),
(4, 'Autre', 'Autre role que ceux cités plus haut', 1),
(5, 'Ancien', 'Ancien de la paroisse', 1);

-- les droits --
INSERT INTO `droit` (`iddroit`, `nomDroit`, `description`, `lisible`, `modules_idmodule`) VALUES
(1, 'Creer utilisateur', 'Droit de creer un utilisateur', 1, 1),
(2, 'Modifier user', 'Droit de modifier un utilisateur', 1, 1),
(3, 'Supprimer user', 'Droit de supprimer un utilisateur', 1, 1),
(4, 'Editer role', 'Droit d''editer les roles', 1, 1),
(5, 'Consulter user', 'Droit de consulter la liste des users', 1, 1),
(8, 'Creer un fidele', 'Droit de creer un nouveau fidele', 1, 2),
(9, 'Modifier un fidele', 'Droit de modifier un fidele', 1, 2),
(10, 'Supprimer fidele', 'Droit de supprimer un fidele', 1, 2),
(11, 'Lister fidele', 'Droit de consulter la liste des fideles', 1, 2),
(12, 'Afficher fidele', 'Droit de vivualiser les informations sur un fidele', 1, 2),
(13, 'Creer un zone', 'Droit de creer une nouvelle zone', 1, 2),
(14, 'Lister les zones', 'Droit de lister toutes les zone', 1, 2),
(15, 'Modifier zone', 'Droit de modifier une zone', 1, 2),
(16, 'Supprimer zone', 'Droit de supprimer une zone', 1, 2),
(17, 'Creer un malade', 'Droit de creer un nouveau malade', 1, 3),
(18, 'Modifier un malade', 'Droit de modifier un malade', 1, 3),
(19, 'Lister malade', 'Droit de consulter la liste des malades', 1, 3),
(20, 'Supprimer un malade', 'Droit de supprimer un malade', 1, 3),
(21, 'Enregistrer guerison', 'Droit d''enregistrer un nouveau malade', 1, 3),
(22, 'Supprimer guerison', 'Droit de supprimer une guerison', 1, 3),
(23, 'Lister guerison', 'Droit de consulter la liste des malades gueris', 1, 3),
(26, 'Creer un groupe', 'Droit de creer un nouveau groupe', 1, 4),
(27, 'Modifier un groupe', 'Droit de modifier un groupe', 1, 4),
(28, 'Supprimer un groupe', 'Droit de supprimer un groupe', 1, 4),
(29, 'Lister groupe', 'Droit de visualiser la liste des groupes', 1, 4),
(30, 'Inscrire a un groupe', 'Droit d''inscrire un fidele a un groupe', 1, 4),
(31, 'Enregistrer un conseil', 'Droit d''enregistrer un conseil avec les decisions et anciens presents', 1, 5),
(32, 'Assiduite a un conseil', 'Droit de consulter la liste des anciens presents aux differents conseils', 1, 5),
(33, 'Planification', 'Droit d''etablir un chronogramme des activites des anciens', 1, 5),
(34, 'Creer activite', 'Droit de creer une nouvelle activite', 1, 6),
(35, 'Modifier activite', 'Droit de modifier une activite', 1, 6),
(36, 'Afficher activite', 'Droit d''afficher une activite', 1, 6),
(37, 'Supprimer activite', 'Droit de supprimer une activite', 1, 6),
(38, 'Lister activites', 'Droit de visualiser la liste des activites', 1, 6),
(39, 'Enregistrer bapteme', 'Droit d''enregistrer un nouveau bapteme', 1, 6),
(40, 'Modifier bapteme', 'Droit de modifier un bapteme', 1, 6),
(41, 'Lister baptises', 'Droit de previsualiser la liste des baptises', 1, 6),
(42, 'Creer sainte cene', 'Droit de creer une nouvelle sainte cene', 1, 7),
(43, 'Enregistrer une participation', 'Droit d''enregistrer une nouvelle partition a une sainte cene', 1, 7),
(44, 'Creer un pasteur', 'Droit de creer un nouveau pasteur', 1, 8),
(45, 'Modifier un pasteur', 'Droit de modifier un pasteur', 1, 8),
(46, 'Afficher le college', 'Droit de visualiser le college pastoral', 1, 8),
(47, 'Lister pasteurs', 'Droit de visualiser la liste des pasteurs', 1, 8),
(48, 'Enregistrer deces', 'Droit d''enregistrer un nouveau deces', 1, 3),
(49, 'Supprimer deces', 'Droit de supprimer un deces', 1, 3),
(50, 'Lister deces', 'Droit de visualiser la liste des malades decedes', 1, 3),
(51, 'Envoyer newsletter', 'Droit d''envoyer des newsletter', 1, 9),
(52, 'Afficher contribution', 'Droit d''afficher les differentes contributions', 1, 7),
(53, 'Enregistrer contribution', 'Droit d''enregistrer les contributions financieres', 1, 7),
(54, 'Editer parametres', 'Droit de modifier les parametres', 1, 11),
(55, 'Supprimer pasteur', 'Droit de supprimer un pasteur', 1, 8),
(56, 'Afficher groupe', 'Droit de visualiser les membres d''un groupe', 1, 4),
(57, 'Consulter participation', 'Droit de consulter les participations des fideles', 1, 7),
(58, 'Supprimer bapteme', 'Droit de supprimer un baptise', 1, 6),
(59, 'Modifier contribution', 'Droit de mofidier une contribution', 1, 7),
(60, 'Supprimer contribution', 'Droit de supprimer une contribution', 1, 7),
(61, 'Visualiser liste attente', 'Droit de visualiser la liste d Attente', 1, 7),
(62, 'Creer Collecte', 'Droit de creer une collecte', 1, 10),
(63, 'Modifier Collecte', 'Droit de modifier une collecte', 1, 10),
(64, 'Supprimer Collecte', 'Droit de supprimer une collecte', 1, 10),
(65, 'Afficher Collecte', 'Droit de visualiser une collecte', 1, 10),
(66, 'Lister Collectes', 'Droit de lister les collectes', 1, 10);

/*droit de chaque role*/
INSERT INTO `roledroit` (`idroleDroit`, `hasDroit`, `droit_iddroit`, `role_idrole`) VALUES
(25, 1, 1, 1),
(26, 1, 2, 1),
(27, 1, 3, 1),
(28, 1, 4, 1),
(29, 1, 5, 1),
(30, 1, 1, 2),
(31, 0, 2, 2),
(32, 0, 3, 2),
(33, 0, 4, 2),
(34, 0, 5, 2),
(35, 1, 8, 1),
(36, 1, 9, 1),
(37, 1, 10, 1),
(38, 1, 11, 1),
(39, 1, 8, 2),
(40, 1, 9, 2),
(41, 0, 10, 2),
(42, 1, 11, 2),
(43, 1, 12, 1),
(44, 1, 12, 2),
(45, 1, 13, 1),
(46, 1, 13, 2),
(47, 1, 14, 1),
(48, 1, 14, 2),
(49, 1, 15, 1),
(50, 1, 15, 2),
(51, 1, 16, 1),
(52, 0, 16, 2),
(53, 1, 17, 1),
(54, 1, 17, 2),
(55, 1, 18, 1),
(56, 1, 18, 2),
(57, 1, 19, 1),
(58, 1, 19, 2),
(59, 1, 20, 1),
(60, 0, 20, 2),
(61, 1, 21, 1),
(62, 1, 22, 1),
(63, 1, 23, 1),
(64, 0, 21, 2),
(65, 0, 22, 2),
(66, 1, 23, 2),
(67, 1, 26, 1),
(68, 1, 27, 1),
(69, 1, 28, 1),
(70, 1, 29, 1),
(71, 1, 30, 1),
(72, 1, 26, 2),
(73, 1, 27, 2),
(74, 1, 28, 2),
(75, 1, 29, 2),
(76, 1, 30, 2),
(77, 1, 31, 1),
(78, 1, 32, 1),
(79, 1, 33, 1),
(80, 1, 31, 2),
(81, 1, 32, 2),
(82, 1, 33, 2),
(83, 1, 34, 1),
(84, 1, 35, 1),
(85, 1, 36, 1),
(86, 1, 37, 1),
(87, 1, 38, 1),
(88, 1, 39, 1),
(89, 1, 40, 1),
(90, 1, 41, 1),
(91, 0, 34, 2),
(92, 0, 35, 2),
(93, 1, 36, 2),
(94, 0, 37, 2),
(95, 1, 38, 2),
(96, 1, 39, 2),
(97, 1, 40, 2),
(98, 1, 41, 2),
(99, 1, 42, 1),
(100, 1, 43, 1),
(101, 0, 42, 2),
(102, 0, 43, 2),
(103, 1, 44, 1),
(104, 1, 45, 1),
(105, 1, 46, 1),
(106, 1, 47, 1),
(107, 0, 44, 2),
(108, 0, 45, 2),
(109, 0, 46, 2),
(110, 0, 47, 2),
(111, 1, 48, 1),
(112, 1, 49, 1),
(113, 1, 50, 1),
(114, 1, 48, 2),
(115, 0, 49, 2),
(116, 1, 50, 2),
(117, 1, 51, 1),
(118, 0, 51, 2),
(119, 1, 52, 1),
(120, 1, 52, 2),
(121, 1, 53, 1),
(122, 1, 53, 2),
(123, 1, 54, 1),
(124, 1, 54, 2),
(125, 1, 55, 1),
(126, 0, 55, 2),
(127, 1, 56, 1),
(128, 1, 56, 2),
(129, 1, 57, 1),
(130, 1, 57, 2),
(131, 1, 58, 1),
(132, 0, 58, 2),
(133, 1, 1, 3),
(134, 1, 2, 3),
(135, 1, 3, 3),
(136, 0, 4, 3),
(137, 1, 5, 3),
(138, 1, 8, 3),
(139, 1, 9, 3),
(140, 1, 10, 3),
(141, 1, 11, 3),
(142, 1, 12, 3),
(143, 1, 13, 3),
(144, 1, 14, 3),
(145, 1, 15, 3),
(146, 1, 16, 3),
(147, 1, 17, 3),
(148, 1, 18, 3),
(149, 1, 19, 3),
(150, 1, 20, 3),
(151, 1, 21, 3),
(152, 1, 22, 3),
(153, 1, 23, 3),
(154, 1, 26, 3),
(155, 1, 27, 3),
(156, 1, 28, 3),
(157, 1, 29, 3),
(158, 1, 30, 3),
(159, 1, 31, 3),
(160, 1, 32, 3),
(161, 1, 33, 3),
(162, 1, 34, 3),
(163, 1, 35, 3),
(164, 1, 36, 3),
(165, 1, 37, 3),
(166, 1, 38, 3),
(167, 1, 39, 3),
(168, 1, 40, 3),
(169, 1, 41, 3),
(170, 1, 42, 3),
(171, 1, 43, 3),
(172, 1, 44, 3),
(173, 1, 45, 3),
(174, 1, 46, 3),
(175, 1, 47, 3),
(176, 1, 48, 3),
(177, 1, 49, 3),
(178, 1, 50, 3),
(179, 1, 51, 3),
(180, 1, 52, 3),
(181, 1, 53, 3),
(182, 1, 54, 3),
(183, 1, 55, 3),
(184, 1, 56, 3),
(185, 1, 57, 3),
(186, 1, 58, 3),
(187, 0, 1, 4),
(188, 0, 2, 4),
(189, 0, 3, 4),
(190, 0, 4, 4),
(191, 0, 5, 4),
(192, 0, 8, 4),
(193, 0, 9, 4),
(194, 0, 10, 4),
(195, 1, 11, 4),
(196, 1, 12, 4),
(197, 0, 13, 4),
(198, 1, 14, 4),
(199, 0, 15, 4),
(200, 0, 16, 4),
(201, 1, 17, 4),
(202, 0, 18, 4),
(203, 1, 19, 4),
(204, 0, 20, 4),
(205, 1, 21, 4),
(206, 0, 22, 4),
(207, 1, 23, 4),
(208, 1, 26, 4),
(209, 0, 27, 4),
(210, 0, 28, 4),
(211, 1, 29, 4),
(212, 1, 30, 4),
(213, 1, 31, 4),
(214, 1, 32, 4),
(215, 1, 33, 4),
(216, 1, 34, 4),
(217, 0, 35, 4),
(218, 1, 36, 4),
(219, 0, 37, 4),
(220, 1, 38, 4),
(221, 1, 39, 4),
(222, 0, 40, 4),
(223, 1, 41, 4),
(224, 1, 42, 4),
(225, 1, 43, 4),
(226, 0, 44, 4),
(227, 0, 45, 4),
(228, 1, 46, 4),
(229, 1, 47, 4),
(230, 1, 48, 4),
(231, 0, 49, 4),
(232, 1, 50, 4),
(233, 0, 51, 4),
(234, 1, 52, 4),
(235, 1, 53, 4),
(236, 1, 54, 4),
(237, 0, 55, 4),
(238, 1, 56, 4),
(239, 1, 57, 4),
(240, 0, 58, 4),
(241, 1, 1, 5),
(242, 1, 2, 5),
(243, 1, 3, 5),
(244, 1, 4, 5),
(245, 1, 5, 5),
(246, 1, 8, 5),
(247, 1, 9, 5),
(248, 1, 10, 5),
(249, 1, 11, 5),
(250, 1, 12, 5),
(251, 1, 13, 5),
(252, 1, 14, 5),
(253, 1, 15, 5),
(254, 1, 16, 5),
(255, 1, 17, 5),
(256, 1, 18, 5),
(257, 1, 19, 5),
(258, 1, 20, 5),
(259, 1, 21, 5),
(260, 1, 22, 5),
(261, 1, 23, 5),
(262, 1, 26, 5),
(263, 1, 27, 5),
(264, 1, 28, 5),
(265, 1, 29, 5),
(266, 1, 30, 5),
(267, 1, 31, 5),
(268, 1, 32, 5),
(269, 1, 33, 5),
(270, 1, 34, 5),
(271, 1, 35, 5),
(272, 1, 36, 5),
(273, 1, 37, 5),
(274, 1, 38, 5),
(275, 1, 39, 5),
(276, 1, 40, 5),
(277, 1, 41, 5),
(278, 1, 42, 5),
(279, 1, 43, 5),
(280, 0, 44, 5),
(281, 0, 45, 5),
(282, 1, 46, 5),
(283, 1, 47, 5),
(284, 1, 48, 5),
(285, 1, 49, 5),
(286, 1, 50, 5),
(287, 1, 51, 5),
(288, 1, 52, 5),
(289, 1, 53, 5),
(290, 1, 54, 5),
(291, 0, 55, 5),
(292, 1, 56, 5),
(293, 1, 57, 5),
(294, 1, 58, 5),
(295, 0, 59, 5),
(296, 0, 60, 5),
(297, 0, 59, 4),
(298, 0, 60, 4),
(299, 1, 59, 3),
(300, 1, 60, 3),
(301, 0, 59, 2),
(302, 0, 60, 2),
(303, 1, 59, 1),
(304, 1, 60, 1),
(305, 1, 61, 1),
(306, 0, 61, 2),
(307, 1, 61, 3),
(308, 0, 61, 4),
(309, 0, 61, 5),
(310, 1, 62, 1),
(311, 1, 63, 1),
(312, 1, 64, 1),
(313, 1, 65, 1),
(314, 1, 66, 1),
(315, 1, 62, 2),
(316, 0, 63, 2),
(317, 0, 64, 2),
(318, 1, 65, 2),
(319, 1, 66, 2),
(320, 0, 62, 3),
(321, 0, 63, 3),
(322, 0, 64, 3),
(323, 0, 65, 3),
(324, 0, 66, 3),
(325, 0, 62, 4),
(326, 0, 63, 4),
(327, 0, 64, 4),
(328, 0, 65, 4),
(329, 0, 66, 4),
(330, 0, 62, 5),
(331, 0, 63, 5),
(332, 0, 64, 5),
(333, 0, 65, 5),
(334, 0, 66, 5);

-- Zone par defaut --
INSERT INTO `zone` (`idzone`, `nomzone`, `description`, `lisible`) VALUES
(1, 'ZONE I', 'MontÃ©e jouvence; Quartier centre Medical d''Arrondissement de Mendong;  la zone de recasement; Rive complexe Scolaire Mario; Zone MAETUR', 1);

-- Administrateur --
INSERT INTO `personne` (`idpersonne`, `nom`, `prenom`, `datenaiss`, `lieunaiss`, `sexe`, `email`, `profession`, `zone_idzone`, `telephone`, `pere`, `pere_vivant`, `mere`, `mere_vivant`, `photo`, `lisible`, `domaine`, `diplome`, `annee_enregistrement`, `statut_pro`, `employeur`, `village`, `arrondissement`, `etablissement`, `niveau`, `serie`, `situation_matri`, `conjoint`, `nombre_enfant`, `religion_conjoint`, `date_enregistrement`) VALUES
(2, 'Admin', 'Admin', '2016-09-06', 'Yaoundé', 'MASCULIN', 'info@kamer-center.net', 'Développeur', 1, '676544214', 'KTC-CENTER', 1, 'KTC-CENTER', 1, 'photo2.jpg', 1, '', '', 2016, '', '', 'BAMEKA', 3, '', '', '', '', '', 3, '', '2016-10-25');

INSERT INTO `utilisateur` (`idutilisateur`, `login`, `password`, `lisible`, `personne_idpersonne`) VALUES
(34, 'admin', 'e376311d2d4b2292b2b5c500c7553d2e', 1, 2);

INSERT INTO `userrole` (`iduserRole`, `has_role`, `lisible`, `utilisateur_idutilisateur`, `role_idrole`) VALUES
(34, 1, 1, 34, 1);

-- les occurences pour les conseils --
INSERT INTO `occurrence` (`idoccurrence`, `mensuel`, `extraordinaire`, `elargi`, `consistoire`, `cinode`, `retraite`, `nombrehomme`, `nombrefemme`, `nombregarcon`, `nombrefille`) VALUES
(1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- les types de contributions --
INSERT INTO `contribution` (`idcontribution`, `type`, `lisible`) VALUES
(1, 'Conciergerie', 1),
(2, 'Don', 1),
(3, 'Offrandes', 1),
(4, 'Travaux', 1);

-- les differentes saintes cènes --
INSERT INTO `saintescene` (`idsaintescene`, `valide`, `mois`, `annee`, `lisible`) VALUES
(1, 1, 'Janvier', 2017, 1),
(2, 1, 'Fevrier', 2017, 1),
(3, 1, 'Mars', 2017, 1),
(4, 1, 'Avril', 2017, 1),
(5, 1, 'Mai', 2017, 1),
(6, 1, 'Juin', 2017, 1),
(7, 1, 'Juillet', 2017, 1),
(8, 1, 'Aout', 2017, 1),
(9, 1, 'Septembre', 2017, 1),
(10, 1, 'Octobre', 2017, 1),
(11, 1, 'Novembre', 2017, 1),
(12, 1, 'Decembre', 2017, 1);

-- les grades des pasteurs --
INSERT INTO `grade` (`idgrade`, `nomgrade`, `estPris`, `lisible`) VALUES
(1, 'Pasteur chef', 1, 1),
(2, 'Pasteur numero 2', 1, 1),
(3, 'Pasteur numero 3', 1, 1),
(4, 'Pasteur numero 4', 1, 1),
(5, 'Pasteur numero 5', 0, 1);

/* Creation de la table Base */
CREATE DATABASE `base` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `base`;

-- --------------------------------------------------------

--
-- Structure de la table `base`
--

CREATE TABLE IF NOT EXISTS `base` (
  `idbase` int(11) NOT NULL AUTO_INCREMENT,
  `annee` year(4) NOT NULL,
  `etat` tinyint(1) NOT NULL,
  PRIMARY KEY (`idbase`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Contenu de la table `base`
--

INSERT INTO `base` (`idbase`, `annee`, `etat`) VALUES
(1, 2017, 1),

