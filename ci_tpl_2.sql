-- MySQL dump 10.13  Distrib 8.0.11, for Linux (x86_64)
--
-- Host: localhost    Database: ci_tpl
-- ------------------------------------------------------
-- Server version	8.0.11

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
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
 SET character_set_client = utf8 ;
CREATE TABLE `Api` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL COMMENT '名称',
  `Url` varchar(255) NOT NULL COMMENT '接口URL',
  `EventId` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '事件ID',
  `Request` text NOT NULL COMMENT '请求参数',
  `Response` text NOT NULL COMMENT '响应参数',
  `Example` text COMMENT '响应示例',
  `State` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0禁用，1启用',
  `Infos` text COMMENT ' 说明 ',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`),
  KEY `Name` (`Name`),
  KEY `Url` (`Url`),
  KEY `EventId` (`EventId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='接口文档 ';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Api`
--

LOCK TABLES `Api` WRITE;
/*!40000 ALTER TABLE `Api` DISABLE KEYS */;
/*!40000 ALTER TABLE `Api` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Cate`
--

DROP TABLE IF EXISTS `Cate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8 ;
CREATE TABLE `Cate` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Group` varchar(255) NOT NULL COMMENT '分组',
  `Value` varchar(255) NOT NULL COMMENT '值',
  `Name` varchar(255) NOT NULL COMMENT '名称',
  `Constant` varchar(255) DEFAULT NULL COMMENT '常量名',
  `Sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
  `Display` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否显示，0隐藏，1显示',
  `Infos` varchar(255) NOT NULL COMMENT '描述',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  KEY `Group` (`Group`) USING BTREE,
  KEY `Value` (`Value`) USING BTREE,
  KEY `Name` (`Name`) USING BTREE,
  KEY `Constant` (`Constant`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cate`
--

LOCK TABLES `Cate` WRITE;
/*!40000 ALTER TABLE `Cate` DISABLE KEYS */;
/*!40000 ALTER TABLE `Cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Master`
--

DROP TABLE IF EXISTS `Master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8 ;
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台-管理员';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Master`
--

LOCK TABLES `Master` WRITE;
/*!40000 ALTER TABLE `Master` DISABLE KEYS */;
INSERT INTO `Master` VALUES (1,'开v',1,'dev','$2y$10$n/kYlaZU7uleY.p0zWHaZ.8ipV68Yoy0vkLtL2sEm.UG3NzD/0j1S','6712FEDB-2A39-1273-473D-5F3347ADA0D3',1,'2018-09-09 18:42:11','2018-09-10 23:38:52');
/*!40000 ALTER TABLE `Master` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Menu`
--

DROP TABLE IF EXISTS `Menu`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8 ;
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
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台-菜单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Menu`
--

LOCK TABLES `Menu` WRITE;
/*!40000 ALTER TABLE `Menu` DISABLE KEYS */;
INSERT INTO `Menu` VALUES (1,0,'/admin/menu#','','后台管理','<i class=\"layui-icon layui-icon-set-fill\"></i>',1,1,1,0,'','2018-09-08 23:45:50','2018-09-09 00:18:33'),(2,1,'/admin/menu','','菜单管理','<i class=\"layui-icon layui-icon-find-fill\"></i>',1,1,1,0,'','2018-09-08 23:51:02','2018-09-09 00:15:51'),(3,2,'/admin/menu/add','','添加菜单','',1,0,1,0,'','2018-09-09 10:21:40','0000-00-00 00:00:00'),(4,2,'/admin/menuedit','','修改菜单','',1,0,1,0,'','2018-09-09 10:22:07','0000-00-00 00:00:00'),(5,2,'/admin/menu/info','','菜单详情','',1,0,1,0,'','2018-09-09 10:23:05','0000-00-00 00:00:00'),(6,2,'/admin/menu/all','','所有菜单','',1,0,1,0,'','2018-09-09 10:23:33','0000-00-00 00:00:00'),(7,1,'/admin/role','','角色管理','<i class=\"layui-icon layui-icon-group\"></i>',1,1,1,0,'','2018-09-09 10:24:34','0000-00-00 00:00:00'),(8,7,'/admin/role/add','','添加角色','',1,0,1,0,'','2018-09-09 11:02:19','0000-00-00 00:00:00'),(9,7,'/admin/role/edit','','修改角色','',1,0,1,0,'','2018-09-09 11:04:55','0000-00-00 00:00:00'),(10,7,'/admin/role/info','','角色详情','',1,0,1,0,'','2018-09-09 11:14:33','0000-00-00 00:00:00'),(11,7,'/admin/role/all','','所有角色','',1,0,1,0,'','2018-09-09 11:14:58','0000-00-00 00:00:00'),(12,1,'/admin/master','','用户管理','<i class=\"layui-icon layui-icon-friends\"></i>',1,1,1,0,'','2018-09-09 17:16:04','0000-00-00 00:00:00'),(13,12,'/admin/master/add','','添加用户','',1,0,1,0,'','2018-09-09 17:16:35','2018-09-09 17:17:22'),(14,12,'/admin/master/edit','','修改用户','',1,0,1,0,'','2018-09-09 17:17:11','0000-00-00 00:00:00'),(15,0,'/admin/home#','','公共','<i class=\"layui-icon layui-icon-tips\"></i>',0,0,1,0,'','2018-09-09 23:48:57','0000-00-00 00:00:00'),(16,15,'/admin/home','','控制台','<i class=\"layui-icon layui-icon-console\"></i>',0,0,1,0,'','2018-09-09 23:49:32','0000-00-00 00:00:00'),(17,15,'/admin/home/nav','','导航菜单','',0,0,1,0,'','2018-09-09 23:50:36','0000-00-00 00:00:00'),(18,15,'/admin/home/upload','','文件上传','',0,0,1,0,'','2018-09-09 23:51:17','0000-00-00 00:00:00'),(19,0,'/admin/api#','','系统资料','<i class=\"layui-icon layui-icon-tabs\"></i>',1,1,1,0,'','2018-09-09 23:59:00','0000-00-00 00:00:00'),(20,19,'/admin/api','','接口文档','<i class=\"layui-icon layui-icon-link\"></i>',1,1,1,0,'','2018-09-10 00:04:29','0000-00-00 00:00:00'),(21,20,'/admin/api/add','','添加接口','',1,0,1,0,'','2018-09-10 00:05:02','0000-00-00 00:00:00'),(22,20,'/admin/api/edit','','修改接口','',1,0,1,0,'','2018-09-10 00:05:27','0000-00-00 00:00:00'),(23,20,'/admin/api/info','','接口信息','',1,0,1,0,'','2018-09-10 00:06:10','0000-00-00 00:00:00'),(24,15,'/admin/home/rsync','','数据同步','<i class=\"layui-icon layui-icon-senior\"></i>',1,0,1,0,'','2018-09-10 23:42:03','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `Menu` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Role`
--

DROP TABLE IF EXISTS `Role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8 ;
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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='后台-角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Role`
--

LOCK TABLES `Role` WRITE;
/*!40000 ALTER TABLE `Role` DISABLE KEYS */;
INSERT INTO `Role` VALUES (1,0,'开发者',1,'','','2018-09-09 11:42:15','2018-09-09 15:42:33'),(2,0,'超级管理员',1,'','','2018-09-09 11:43:02','2018-09-09 15:29:33');
/*!40000 ALTER TABLE `Role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

DROP TABLE IF EXISTS `Source`;

CREATE TABLE `Source` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL COMMENT '名称',
  `Url` varchar(255) NOT NULL COMMENT '地址',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片资源';

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-11  0:03:23
