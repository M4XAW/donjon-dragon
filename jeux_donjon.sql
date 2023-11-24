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
-- Table structure for table `enigmes`
--

DROP TABLE IF EXISTS `enigmes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `enigmes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `question` text NOT NULL,
  `reponse` varchar(255) NOT NULL,
  `salle_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `enigmes`
--

LOCK TABLES `enigmes` WRITE;
/*!40000 ALTER TABLE `enigmes` DISABLE KEYS */;
INSERT INTO `enigmes` VALUES
(1,'Je suis pris la nuit, mais je disparais le matin. Qui suis-je ?','Lune',3),
(2,'Plus on en donne, plus on en a. Qu\'est-ce que c\'est ?','Connaissance',3);
/*!40000 ALTER TABLE `enigmes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `inventaire_personnage`
--

DROP TABLE IF EXISTS `inventaire_personnage`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventaire_personnage` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `personnage_id` int(11) DEFAULT NULL,
  `objet_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `personnage_id` (`personnage_id`),
  KEY `objet_id` (`objet_id`),
  CONSTRAINT `inventaire_personnage_ibfk_1` FOREIGN KEY (`personnage_id`) REFERENCES `personnages` (`id`),
  CONSTRAINT `inventaire_personnage_ibfk_2` FOREIGN KEY (`objet_id`) REFERENCES `objet` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `inventaire_personnage`
--

LOCK TABLES `inventaire_personnage` WRITE;
/*!40000 ALTER TABLE `inventaire_personnage` DISABLE KEYS */;
INSERT INTO `inventaire_personnage` VALUES
(4,1,3),
(36,1,7),
(41,1,8);
/*!40000 ALTER TABLE `inventaire_personnage` ENABLE KEYS */;
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
  `experience` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `salle_id` (`salle_id`),
  CONSTRAINT `monstres_ibfk_1` FOREIGN KEY (`salle_id`) REFERENCES `salles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `monstres`
--

LOCK TABLES `monstres` WRITE;
/*!40000 ALTER TABLE `monstres` DISABLE KEYS */;
INSERT INTO `monstres` VALUES
(1,'Gobelin',60,10,5,3,'La salle du roi des Gobelins, ce monstre féroce vous attend.',4,20),
(2,'Squelette',180,12,6,2,'Un squelette redoutable hante cette salle.',5,15);
/*!40000 ALTER TABLE `monstres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objet`
--

DROP TABLE IF EXISTS `objet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `objet` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `bonus` int(11) DEFAULT NULL,
  `malus` int(11) DEFAULT NULL,
  `degats` int(11) DEFAULT NULL,
  `defense` int(11) DEFAULT NULL,
  `taux_soin` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objet`
--

LOCK TABLES `objet` WRITE;
/*!40000 ALTER TABLE `objet` DISABLE KEYS */;
INSERT INTO `objet` VALUES
(1,'Hache','Arme','Une puissante hache tranchante',NULL,NULL,10,NULL,NULL),
(2,'Épée','Arme','Une épée élégante',NULL,NULL,8,NULL,NULL),
(3,'Armure en Fer','Armure','Une solide armure en fer',NULL,NULL,NULL,10,NULL),
(4,'Dague','Arme','Une dague d\'assassin',NULL,NULL,6,NULL,NULL),
(5,'Armure en cuire','Armure','Armure peu resistant ',NULL,NULL,NULL,6,NULL),
(6,'Armure en diamant','Armure','Armure de bonne qualitée ',NULL,NULL,NULL,15,NULL),
(7,'Lance','Arme','Une bonne arme de combat',NULL,NULL,11,NULL,NULL),
(8,'Potion de soin','Potion','Sert à recupere des hp',NULL,NULL,NULL,0,15);
/*!40000 ALTER TABLE `objet` ENABLE KEYS */;
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
  `niveau_arme_requis` int(11) DEFAULT NULL,
  `salle_actuelle` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personnages`
--

LOCK TABLES `personnages` WRITE;
/*!40000 ALTER TABLE `personnages` DISABLE KEYS */;
INSERT INTO `personnages` VALUES
(1,'Draven',143,20,10,20,1,1,1),
(2,'Darius',80,18,12,0,1,1,4),
(3,'Garen',90,15,15,0,1,1,NULL);
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
  `monstre_id` int(11) DEFAULT NULL,
  `enigme_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_enigme` (`enigme_id`),
  CONSTRAINT `fk_enigme` FOREIGN KEY (`enigme_id`) REFERENCES `enigmes` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `salles`
--

LOCK TABLES `salles` WRITE;
/*!40000 ALTER TABLE `salles` DISABLE KEYS */;
INSERT INTO `salles` VALUES
(1,'Salle du Marchand','Marchand','Un marchand amical vous propose ses marchandises à des prix raisonnables.',NULL,NULL),
(2,'Salle du Piège','Piège','Cette salle semble calme, mais méfiez-vous des pièges qui pourraient être dissimulés.',NULL,NULL),
(3,'Salle des Questions','Questions','Testez vos connaissances avec des questions difficiles dans cette salle énigmatique.',NULL,2),
(4,'Salle du Gobelin','Monstre','La salle du roi des Gobelin, ce monstre féroce vous attend.',1,NULL),
(5,'Salle du Squelette','Monstre','Un squelette redoutable hante cette salle.',2,NULL);
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

-- Dump completed on 2023-11-24 16:28:24
