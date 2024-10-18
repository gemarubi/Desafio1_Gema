-- MariaDB dump 10.19  Distrib 10.4.24-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: risk
-- ------------------------------------------------------
-- Server version	10.4.24-MariaDB

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
-- Table structure for table `partida`
--

DROP TABLE IF EXISTS `partida`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `partida` (
  `ID_PARTIDA` int(5) NOT NULL AUTO_INCREMENT,
  `RESULTADO` int(1) DEFAULT NULL,
  `ID_USUARIO` int(5) NOT NULL,
  PRIMARY KEY (`ID_PARTIDA`),
  KEY `FK_PARTIDA` (`ID_USUARIO`),
  CONSTRAINT `FK_PARTIDA` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partida`
--

LOCK TABLES `partida` WRITE;
/*!40000 ALTER TABLE `partida` DISABLE KEYS */;
INSERT INTO `partida` VALUES (72,0,1),(73,0,1);
/*!40000 ALTER TABLE `partida` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol` (
  `ID_ROL` int(5) NOT NULL AUTO_INCREMENT,
  `DESCRIPCION` varchar(20) NOT NULL,
  PRIMARY KEY (`ID_ROL`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'Administrador'),(2,'Usuario');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol_usuario`
--

DROP TABLE IF EXISTS `rol_usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol_usuario` (
  `ID_USUARIO` int(5) NOT NULL,
  `ID_ROL` int(5) NOT NULL,
  PRIMARY KEY (`ID_USUARIO`,`ID_ROL`),
  KEY `FK_ROL_USUARIO_ID_ROL` (`ID_ROL`),
  CONSTRAINT `FK_ROL_USUARIO_ID_ROL` FOREIGN KEY (`ID_ROL`) REFERENCES `rol` (`ID_ROL`),
  CONSTRAINT `FK_ROL_USUARIO_ID_USUARIO` FOREIGN KEY (`ID_USUARIO`) REFERENCES `usuario` (`ID_USUARIO`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol_usuario`
--

LOCK TABLES `rol_usuario` WRITE;
/*!40000 ALTER TABLE `rol_usuario` DISABLE KEYS */;
INSERT INTO `rol_usuario` VALUES (25,2);
/*!40000 ALTER TABLE `rol_usuario` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `territorio`
--

DROP TABLE IF EXISTS `territorio`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `territorio` (
  `ID_TERRITORIO` int(20) NOT NULL AUTO_INCREMENT,
  `POSICION` int(4) NOT NULL,
  `TROPA` varchar(3) NOT NULL,
  `CANTIDAD` int(3) NOT NULL,
  `ID_PARTIDA` int(5) NOT NULL,
  PRIMARY KEY (`ID_TERRITORIO`),
  KEY `FK_TERRITORIO_ID_PARTIDA` (`ID_PARTIDA`),
  CONSTRAINT `FK_TERRITORIO_ID_PARTIDA` FOREIGN KEY (`ID_PARTIDA`) REFERENCES `partida` (`ID_PARTIDA`)
) ENGINE=InnoDB AUTO_INCREMENT=549 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `territorio`
--

LOCK TABLES `territorio` WRITE;
/*!40000 ALTER TABLE `territorio` DISABLE KEYS */;
INSERT INTO `territorio` VALUES (529,0,'M',6,72),(530,1,'J',2,72),(531,2,'M',1,72),(532,3,'J',2,72),(533,4,'M',1,72),(534,5,'J',2,72),(535,6,'M',1,72),(536,7,'J',2,72),(537,8,'M',1,72),(538,9,'J',2,72),(539,0,'M',5,73),(540,1,'J',2,73),(541,2,'M',2,73),(542,3,'J',2,73),(543,4,'M',1,73),(544,5,'J',2,73),(545,6,'M',1,73),(546,7,'J',2,73),(547,8,'M',1,73),(548,9,'J',2,73);
/*!40000 ALTER TABLE `territorio` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuario`
--

DROP TABLE IF EXISTS `usuario`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuario` (
  `ID_USUARIO` int(5) NOT NULL AUTO_INCREMENT,
  `CORREO` varchar(200) NOT NULL,
  `PASS` varchar(200) NOT NULL,
  PRIMARY KEY (`ID_USUARIO`),
  UNIQUE KEY `CORREO` (`CORREO`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuario`
--

LOCK TABLES `usuario` WRITE;
/*!40000 ALTER TABLE `usuario` DISABLE KEYS */;
INSERT INTO `usuario` VALUES (1,'gemarubio@correo.com','1234'),(25,'luka@polo.com ','81dc9bdb52d04dc20036dbd8313ed055');
/*!40000 ALTER TABLE `usuario` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-10-15  8:54:38
