-- MySQL dump 10.13  Distrib 8.0.11, for Linux (x86_64)
--
-- Host: localhost    Database: ci_tpl
-- ------------------------------------------------------
-- Server version	8.0.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8mb4 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `ci_tpl`
--

CREATE DATABASE IF NOT EXISTS `ci_tpl` DEFAULT CHARACTER SET utf8 ;

USE `ci_tpl`;

--
-- Table structure for table `Api`
--

DROP TABLE IF EXISTS `Api`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Api` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL COMMENT '名称',
  `Url` varchar(255) NOT NULL COMMENT '接口URL',
  `EventId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '事件ID',
  `Request` text NOT NULL COMMENT '请求参数',
  `Response` text NOT NULL COMMENT '响应参数',
  `Example` text COMMENT '响应示例',
  `State` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0禁用，1启用',
  `Infos` text DEFAULT NULL COMMENT ' 说明 ',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='接口文档 ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Api`
--

LOCK TABLES `Api` WRITE;
/*!40000 ALTER TABLE `Api` DISABLE KEYS */;
/*!40000 ALTER TABLE `Api` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Master`
--

DROP TABLE IF EXISTS `Master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Master` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Realname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '真实姓名',
  `RoleId` int(10) unsigned NOT NULL COMMENT '角色ID',
  `Account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '账号',
  `Password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `Token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '登录令牌',
  `State` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0禁用，1启用',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  KEY `Realname` (`Realname`) USING BTREE,
  KEY `RoleId` (`RoleId`) USING BTREE,
  KEY `Account` (`Account`) USING BTREE,
  KEY `Token` (`Token`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台-管理员';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Master`
--

LOCK TABLES `Master` WRITE;
/*!40000 ALTER TABLE `Master` DISABLE KEYS */;
/*!40000 ALTER TABLE `Master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Menu`
--

DROP TABLE IF EXISTS `Menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Menu` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ParentId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级菜单 ID',
  `Url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT ' 路由地址',
  `UrlAlias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT ' 路由别名',
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT ' 菜单名称',
  `Icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT ' 菜单图标',
  `Ctrl` tinyint(1) NOT NULL DEFAULT '1' COMMENT ' 是否限制， 0否，1是',
  `Display` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 是否显示，0隐藏，1显示',
  `State` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 是否开启，0禁用，1开启，',
  `Sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT ' 排序',
  `Infos` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT ' 功能说明 ',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  KEY `ParentId` (`ParentId`) USING BTREE,
  KEY `Url` (`Url`) USING BTREE,
  KEY `UrlAlias` (`UrlAlias`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台-菜单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Menu`
--

LOCK TABLES `Menu` WRITE;
/*!40000 ALTER TABLE `Menu` DISABLE KEYS */;
/*!40000 ALTER TABLE `Menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Role`
--

DROP TABLE IF EXISTS `Role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `Role` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ParentId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级ID',
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `Admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否超管，0否 ，1是',
  `MenuIds` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '权限菜单',
  `Infos` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '描述 ',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  KEY `ParentId` (`ParentId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台-角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Role`
--

LOCK TABLES `Role` WRITE;
/*!40000 ALTER TABLE `Role` DISABLE KEYS */;
/*!40000 ALTER TABLE `Role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-09 22:57:45
