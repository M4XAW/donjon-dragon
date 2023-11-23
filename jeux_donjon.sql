-- MariaDB dump 10.19-11.1.2-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: jeux_donjon
-- ------------------------------------------------------
-- Server version	11.1.2-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `armes`
--

DROP TABLE IF EXISTS `armes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `armes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `niveau_requis` int(11) NOT NULL,
  `degats` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `armes`
--

LOCK TABLES `armes` WRITE;
/*!40000 ALTER TABLE `armes` DISABLE KEYS */;
/*!40000 ALTER TABLE `armes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `butin`
--

DROP TABLE IF EXISTS `butin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `butin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `bonus` int(11) DEFAULT NULL,
  `malus` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `butin`
--

LOCK TABLES `butin` WRITE;
/*!40000 ALTER TABLE `butin` DISABLE KEYS */;
/*!40000 ALTER TABLE `butin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `monstres`
--

DROP TABLE IF EXISTS `monstres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `monstres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `points_de_vie` int(11) NOT NULL,
  `points_d_attaque` int(11) NOT NULL,
  `points_de_defense` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `salle_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `salle_id` (`salle_id`),
  CONSTRAINT `monstres_ibfk_1` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monstres`
--

LOCK TABLES `monstres` WRITE;
/*!40000 ALTER TABLE `monstres` DISABLE KEYS */;
/*!40000 ALTER TABLE `monstres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personnages`
--

DROP TABLE IF EXISTS `personnages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `personnages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `points_de_vie` int(11) NOT NULL,
  `points_d_attaque` int(11) NOT NULL,
  `points_de_defense` int(11) NOT NULL,
  `experience` int(11) NOT NULL,
  `niveau` int(11) NOT NULL,
  `nom_arme` varchar(255) DEFAULT NULL,
  `niveau_arme_requis` int(11) DEFAULT NULL,
  `cree_le` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personnages`
--

LOCK TABLES `personnages` WRITE;
/*!40000 ALTER TABLE `personnages` DISABLE KEYS */;
/*!40000 ALTER TABLE `personnages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `salles`
--

DROP TABLE IF EXISTS `salles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `salles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salles`
--

LOCK TABLES `salles` WRITE;
/*!40000 ALTER TABLE `salles` DISABLE KEYS */;
/*!40000 ALTER TABLE `salles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-23 11:11:54
