-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 27, 2018 at 07:49 AM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

DROP SCHEMA IF EXISTS webprojekti;
CREATE SCHEMA IF NOT EXISTS webprojekti DEFAULT CHARACTER SET latin1;
USE webprojekti;



SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `webprojekti`
--


-- --------------------------------------------------------

--
-- Table structure for table `kayttaja`
--

DROP TABLE IF EXISTS `kayttaja`;
CREATE TABLE IF NOT EXISTS `kayttaja` (
  `KAYTTAJA_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TUNNUS` varchar(50) CHARACTER SET latin1 NOT NULL UNIQUE,
  `SALASANA` varchar(50) CHARACTER SET latin1 NOT NULL,
  `NIMI` varchar(50) CHARACTER SET latin1 NOT NULL,
  `OSOITE` varchar(50) CHARACTER SET latin1 NOT NULL,
  `POSTINRO` varchar(5) CHARACTER SET latin1 NOT NULL,
  `POSTITMP` varchar(50) CHARACTER SET latin1 NOT NULL,
  `ASTY` int(11) NOT NULL,
  PRIMARY KEY (`KAYTTAJA_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `kayttaja`
--

INSERT INTO `kayttaja` (`KAYTTAJA_ID`, `TUNNUS`, `SALASANA`, `NIMI`, `OSOITE`, `POSTINRO`, `POSTITMP`,`ASTY`) VALUES
(1, 'Kalle', 'kalle','KALLE TAPPINEN', 'OPISTOTIE 2', '70100', 'KUOPIO',1),
(2, 'Ville', 'ville','VILLE VALLATON', 'MICROKATU 2', '70100', 'KUOPIO',1),
(3, 'Kalle2', 'kalle','Kalle Östilä', 'Teku', '70100', 'Kuopio',1),
(4, 'admin', 'admin','Keke Amstrong', 'Viasat', '00010', 'Tsadi',0),
(5, 'Pasi', 'pasi','Pasi Rautiainen', 'Viaplay', '89100', 'Rovaniemi',1),
(6, 'Mauri', 'mauri','mauri', 'Toivalantie 25', '7100', 'Siili',1);

-- --------------------------------------------------------

--
-- Table structure for table `laite`
--


DROP TABLE IF EXISTS `laite`;
CREATE TABLE IF NOT EXISTS `laite` (
  `LAITE_ID` int(11) NOT NULL AUTO_INCREMENT,
  `TILA` int(1)  NOT NULL,
  `NIMI` varchar(50) CHARACTER SET latin1 NOT NULL,
  `MALLI` varchar(50) CHARACTER SET latin1 NOT NULL,
  `MERKKI` varchar(50) CHARACTER SET latin1 NOT NULL,
  `SARJANUMERO` varchar(50) CHARACTER SET latin1 NOT NULL,
  `KATEGORIA` varchar(50) CHARACTER SET latin1 NOT NULL,
  `OMISTAJA` varchar(50) CHARACTER SET latin1 NOT NULL,
  `OSOITE` varchar(50) CHARACTER SET latin1 NOT NULL,
  `POSTINRO` varchar(5) CHARACTER SET latin1 NOT NULL,
  `POSTITMP` varchar(50) CHARACTER SET latin1 NOT NULL,
  `KUVAUS` varchar(250) CHARACTER SET latin1 NOT NULL,
  
  PRIMARY KEY (`LAITE_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;



--
-- Dumping data for table `laite`
--
INSERT INTO `laite` (`LAITE_ID`, `TILA`, `NIMI`, `MALLI`, `MERKKI`, `SARJANUMERO`, `KATEGORIA`, `OMISTAJA`, `OSOITE`, `POSTINRO`, `POSTITMP`, `KUVAUS`) VALUES
(1, 1, 'AKKUPORAKONE','DDF484RTJ', 'MAKITA ', '501767523','Porakone', 'Pena', 'Saharakaari 5','62300', 'Alahärmä', 'Makita DDF484RTJ -akkuporakone kuluttaa hiiliharjattoman moottorinsa ansiosta vähemmän virtaa kuin tavallisella moottorilla varustettu kone, joten yhdellä akun latauksella voi työskennellä pidempään.'),
(2, 1, 'PAKKISAHA',' PROFCUT PC-15-TBX', 'BAHCO', '500031110','Saha', 'Make', 'Vitikantie 4','85800', 'Haapajärvi', 'Katkaisu- ja halkaisusaha keskikarkeiden materiaalien, kuten lankkujen, vanerien, kuitu- ja lastulevyjen sahaamiseen.'),
(3, 1, 'VESIVAAKA','TORPEDO', 'IRONSIDE', '500213109 ','Vatupassi', 'Juhani', 'Nimismiehenpelto 3','02770', 'Espoo', 'Ammattilaatu, takuu 25 vuotta.'),
(4, 1, 'VASARA','GRIP TAOTTU 20OZ', 'PROF', '501512057','Vasara', 'Olavi', 'Kiitokatu 1','15210', 'Lahti', 'Taottu PROF-vasara ainutlaatuisella GRIP-kädensijalla.'),
(5, 1, 'KOURUTALTTA','20MM 422P-20', 'BAHCO', '500344748','Taltta', 'Liisa', 'Mäkikuumolantie 5','05800', 'Hyvinkää', 'Muotoiltu kaksikomponenttinen kädensija. Karkaistu, tarkkuushiottu terä. Suojakotelo.');


-- --------------------------------------------------------



--
-- Table structure for table `varaus`
--

DROP TABLE IF EXISTS `varaus`;
CREATE TABLE IF NOT EXISTS `varaus` (
  `VARAUS_ID` int(11) NOT NULL AUTO_INCREMENT,
  `KAYTTAJA_ID` int(11) NOT NULL,
  `LAITE_ID` int(11) NOT NULL,
  `VARAUS_TILA` int(1)  NOT NULL,
  `ALOITUSPVM` date NOT NULL,
  `LOPETUSPVM` date NOT NULL,
  PRIMARY KEY (`VARAUS_ID`),
  FOREIGN KEY (`KAYTTAJA_ID`) REFERENCES kayttaja(`KAYTTAJA_ID`),
  FOREIGN KEY (`LAITE_ID`) REFERENCES laite(`LAITE_ID`)

) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_swedish_ci;

--
-- Dumping data for table `varaus`
--


INSERT INTO `varaus` (`VARAUS_ID`,`KAYTTAJA_ID`,`LAITE_ID`, `VARAUS_TILA`, `ALOITUSPVM`, `LOPETUSPVM`) VALUES
(1, 5, 3, 1, '2012-01-05', '2012-01-23'),
(2, 2, 5, 1, '2013-03-12', '2013-04-01'),
(3, 4, 1, 1, '2013-05-07', '2013-06-15'),
(4, 2, 2, 1, '2015-10-16', '2015-12-23'),
(5, 1, 1, 1, '2015-12-31', '2016-02-09'),
(6, 2, 4, 1, '2016-02-11', '2016-03-12'),
(7, 6, 1, 1, '2018-06-17', '2018-09-01');





COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;