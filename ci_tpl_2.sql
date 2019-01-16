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

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `ci_tpl` /*!40100 DEFAULT CHARACTER SET utf8 */;

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
  PRIMARY KEY (`Id`) USING BTREE,
  KEY `Name` (`Name`) USING BTREE,
  KEY `Url` (`Url`) USING BTREE,
  KEY `EventId` (`EventId`) USING BTREE
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
  `Infos` varchar(255) DEFAULT NULL COMMENT '描述',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  KEY `Group` (`Group`) USING BTREE,
  KEY `Value` (`Value`) USING BTREE,
  KEY `Name` (`Name`) USING BTREE,
  KEY `Constant` (`Constant`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='分类';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Cate`
--

LOCK TABLES `Cate` WRITE;
/*!40000 ALTER TABLE `Cate` DISABLE KEYS */;
INSERT INTO `Cate` VALUES (1,'默认配置项','15','默认分页条数','DEFAULT_PERPAGE',0,1,'','2018-09-11 16:41:11',NULL),(2,'默认配置项','10','默认分页跳转页码个数','DEFAULT_PAGEBAR_NUM',0,1,'','2018-09-11 16:48:05',NULL),(3,'默认配置项','36000','后台操作等待最长时间（秒）','OPERAT_WAIT_TIME',0,1,'','2018-09-11 16:52:35',NULL),(4, '默认配置项', '', '接口地址', 'API_BASE_URL', 0, 1, '', '2019-01-15 11:38:26', NULL);
/*!40000 ALTER TABLE `Cate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Config`
--

DROP TABLE IF EXISTS `Config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8 ;
CREATE TABLE `Config` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Table` varchar(255) NOT NULL COMMENT '表名',
  `Name` varchar(255) DEFAULT NULL COMMENT '名称',
  `DBConf` text COMMENT '数据库配置',
  `MainDB` varchar(255) NOT NULL COMMENT '主数据库',
  `BackDB` varchar(255) NOT NULL COMMENT '备数据库',
  `Single` tinyint(1) DEFAULT '0' COMMENT '单列配置，0否，1是',
  `Columns` text COMMENT '字段详情',
  `Infos` varchar(255) DEFAULT NULL COMMENT '说明',
  `State` tinyint(1) DEFAULT '1' COMMENT '状态，0弃用，1开启',
  `Created` varchar(20) DEFAULT NULL,
  `Updated` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT COMMENT='快捷配置';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Config`
--

LOCK TABLES `Config` WRITE;
/*!40000 ALTER TABLE `Config` DISABLE KEYS */;
/*!40000 ALTER TABLE `Config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Master`
--

DROP TABLE IF EXISTS `Master`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8 ;
CREATE TABLE `Master` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Realname` varchar(255) NOT NULL COMMENT '真实姓名',
  `RoleId` int(10) unsigned NOT NULL COMMENT '角色ID',
  `Account` varchar(255) NOT NULL COMMENT '账号',
  `Password` varchar(255) NOT NULL COMMENT '密码',
  `Token` varchar(255) DEFAULT NULL COMMENT '登录令牌',
  `State` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态，0禁用，1启用',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  KEY `Realname` (`Realname`) USING BTREE,
  KEY `RoleId` (`RoleId`) USING BTREE,
  KEY `Account` (`Account`) USING BTREE,
  KEY `Token` (`Token`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='后台-管理员';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Master`
--

LOCK TABLES `Master` WRITE;
/*!40000 ALTER TABLE `Master` DISABLE KEYS */;
INSERT INTO `Master` VALUES (1,'开v',1,'dev','$2y$10$n/kYlaZU7uleY.p0zWHaZ.8ipV68Yoy0vkLtL2sEm.UG3NzD/0j1S','D90889FD-1890-92B6-9A5F-EEA47485FB7B',1,'2018-09-09 18:42:11','2018-09-17 17:27:35');
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
  `Url` varchar(255) NOT NULL COMMENT ' 路由地址',
  `UrlAlias` varchar(255) DEFAULT NULL COMMENT ' 路由别名',
  `Name` varchar(255) NOT NULL COMMENT ' 菜单名称',
  `Icon` varchar(255) DEFAULT NULL COMMENT ' 菜单图标',
  `Ctrl` tinyint(1) NOT NULL DEFAULT '1' COMMENT ' 是否限制， 0否，1是',
  `Display` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 是否显示，0隐藏，1显示',
  `State` tinyint(1) NOT NULL DEFAULT '0' COMMENT ' 是否开启，0禁用，1开启，',
  `Sort` int(10) unsigned NOT NULL DEFAULT '0' COMMENT ' 排序',
  `Infos` varchar(255) DEFAULT NULL COMMENT ' 功能说明 ',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  KEY `ParentId` (`ParentId`) USING BTREE,
  KEY `Url` (`Url`) USING BTREE,
  KEY `UrlAlias` (`UrlAlias`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=65 DEFAULT CHARSET=utf8 COMMENT='后台-菜单';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Menu`
--

LOCK TABLES `Menu` WRITE;
/*!40000 ALTER TABLE `Menu` DISABLE KEYS */;
INSERT INTO `Menu` VALUES (1,0,'/admin/menu#','','后台管理','<i class=\"layui-icon layui-icon-set-fill\"></i>',1,1,1,0,'','2018-09-08 23:45:50','2018-09-09 00:18:33'),(2,1,'/admin/menu','','菜单管理','<i class=\"layui-icon layui-icon-find-fill\"></i>',1,1,1,0,'','2018-09-08 23:51:02','2018-09-09 00:15:51'),(3,2,'/admin/menu/add','','添加菜单','',1,0,1,0,'','2018-09-09 10:21:40','0000-00-00 00:00:00'),(4,2,'/admin/menu/edit','','修改菜单','',1,0,1,0,'','2018-09-09 10:22:07','0000-00-00 00:00:00'),(5,2,'/admin/menu/info','','菜单详情','',1,0,1,0,'','2018-09-09 10:23:05','0000-00-00 00:00:00'),(6,2,'/admin/menu/all','','所有菜单','',1,0,1,0,'','2018-09-09 10:23:33','0000-00-00 00:00:00'),(7,1,'/admin/role','','角色管理','<i class=\"layui-icon layui-icon-group\"></i>',1,1,1,0,'','2018-09-09 10:24:34','0000-00-00 00:00:00'),(8,7,'/admin/role/add','','添加角色','',1,0,1,0,'','2018-09-09 11:02:19','0000-00-00 00:00:00'),(9,7,'/admin/role/edit','','修改角色','',1,0,1,0,'','2018-09-09 11:04:55','0000-00-00 00:00:00'),(10,7,'/admin/role/info','','角色详情','',1,0,1,0,'','2018-09-09 11:14:33','0000-00-00 00:00:00'),(11,7,'/admin/role/all','','所有角色','',1,0,1,0,'','2018-09-09 11:14:58','0000-00-00 00:00:00'),(12,1,'/admin/master','','用户管理','<i class=\"layui-icon layui-icon-friends\"></i>',1,1,1,0,'','2018-09-09 17:16:04','0000-00-00 00:00:00'),(13,12,'/admin/master/add','','添加用户','',1,0,1,0,'','2018-09-09 17:16:35','2018-09-09 17:17:22'),(14,12,'/admin/master/edit','','修改用户','',1,0,1,0,'','2018-09-09 17:17:11','0000-00-00 00:00:00'),(15,0,'/admin/home#','','公共','<i class=\"layui-icon layui-icon-tips\"></i>',0,0,1,0,'','2018-09-09 23:48:57','0000-00-00 00:00:00'),(16,15,'/admin/home','','控制台','<i class=\"layui-icon layui-icon-console\"></i>',0,0,1,0,'','2018-09-09 23:49:32','0000-00-00 00:00:00'),(17,15,'/admin/home/nav','','导航菜单','',0,0,1,0,'','2018-09-09 23:50:36','0000-00-00 00:00:00'),(18,15,'/admin/home/upload','','文件上传','',0,0,1,0,'','2018-09-09 23:51:17','0000-00-00 00:00:00'),(19,0,'/admin/api#','','系统资料','<i class=\"layui-icon layui-icon-tabs\"></i>',1,1,1,0,'','2018-09-09 23:59:00','0000-00-00 00:00:00'),(20,19,'/admin/api','','接口文档','<i class=\"layui-icon layui-icon-link\"></i>',1,1,1,0,'','2018-09-10 00:04:29','0000-00-00 00:00:00'),(21,20,'/admin/api/add','','添加接口','',1,0,1,0,'','2018-09-10 00:05:02','0000-00-00 00:00:00'),(22,20,'/admin/api/edit','','修改接口','',1,0,1,0,'','2018-09-10 00:05:27','0000-00-00 00:00:00'),(23,20,'/admin/api/info','','接口信息','',1,0,1,0,'','2018-09-10 00:06:10','0000-00-00 00:00:00'),(24,19,'/admin/rsync','','数据同步','<i class=\"layui-icon layui-icon-senior\"></i>',1,1,1,0,'','2018-09-10 23:42:03','2018-09-17 16:14:03'),(25,20,'/admin/api/all','','所有接口','',1,0,1,0,'','2018-09-14 22:45:08','0000-00-00 00:00:00'),(26,19,'/admin/cate','','分类管理','<i class=\"layui-icon layui-icon-template-1\"></i>',1,1,1,0,'','2018-09-14 22:47:00','0000-00-00 00:00:00'),(27,26,'/admin/cate/add','','添加分类','',1,0,1,0,'','2018-09-14 22:47:28','0000-00-00 00:00:00'),(28,26,'/admin/cate/edit','','修改分类','',1,0,1,0,'','2018-09-14 22:47:43','0000-00-00 00:00:00'),(29,26,'/admin/cate/info','','分类信息','',1,0,1,0,'','2018-09-14 22:48:04','0000-00-00 00:00:00'),(30,26,'/admin/cate/update','','更新配置文件','',1,0,1,0,'','2018-09-14 22:48:20','0000-00-00 00:00:00'),(31,19,'/admin/source','','图片资源','<i class=\"layui-icon layui-icon-picture\"></i>',1,1,1,0,'','2018-09-14 22:48:55','0000-00-00 00:00:00'),(32,31,'/admin/source/add','','添加资源','',1,0,1,0,'','2018-09-14 22:49:14','0000-00-00 00:00:00'),(33,31,'/admin/source/edit','','修改资源','',1,0,1,0,'','2018-09-14 22:49:37','0000-00-00 00:00:00'),(34,31,'/admin/source/all','','所有资源','',1,0,1,0,'','2018-09-14 22:49:54','0000-00-00 00:00:00'),(35,31,'/admin/source/info','','资源信息','',1,0,1,0,'','2018-09-14 22:50:09','0000-00-00 00:00:00'),(36,19,'/admin/config','','快速配置','<i class=\"layui-icon layui-icon-util\"></i>',1,1,1,0,'','2018-09-14 22:50:50','0000-00-00 00:00:00'),(37,15,'/admin/master/modify','','修改账号','',0,0,1,0,'','2018-09-15 00:25:49','2018-09-15 00:27:04'),(38,15,'/admin/master/editPasswd','','修改密码','',0,0,1,0,'','2018-09-15 00:27:33','0000-00-00 00:00:00'),(39,36,'/admin/config/file','','配置文件','',1,0,1,0,'','2018-09-15 15:21:01','0000-00-00 00:00:00'),(40,36,'/admin/config/edit','','编辑配置','',1,0,1,0,'','2018-09-15 15:22:44','0000-00-00 00:00:00'),(41,36,'/admin/config/data','','查看数据','',1,0,1,0,'','2018-09-15 15:23:06','0000-00-00 00:00:00'),(42,36,'/admin/config/download','','下载配置','',1,0,1,0,'','2018-09-15 15:23:39','0000-00-00 00:00:00'),(43,36,'/admin/config/del','','删除配置','',1,0,1,0,'','2018-09-15 15:24:15','0000-00-00 00:00:00'),(44,36,'/admin/config/update','','更新配置','',1,0,1,0,'','2018-09-15 15:24:52','0000-00-00 00:00:00'),(45,36,'/admin/config/remove','','删除文件','',1,0,1,0,'','2018-09-15 15:26:13','0000-00-00 00:00:00'),(46,19,'/admin/file','','文件管理','<i class=\"layui-icon layui-icon-upload\"></i>',1,1,1,0,'','2018-09-16 19:50:50','2018-09-16 21:50:21'),(47,46,'/admin/file/del','','删除文件','',1,0,1,0,'','2018-09-16 19:51:17','0000-00-00 00:00:00'),(48,19,'/admin/log','','日志管理','<i class=\"layui-icon layui-icon-survey\"></i>',1,1,1,0,'','2018-09-16 19:52:29','0000-00-00 00:00:00'),(49,48,'/admin/log/info','','日志详情','',1,0,1,0,'','2018-09-16 19:53:32','0000-00-00 00:00:00'),(50,48,'/admin/log/del','','删除日志','',1,0,1,0,'','2018-09-16 19:54:13','0000-00-00 00:00:00'),(51,48,'/admin/log/download','','下载日志','',1,0,1,0,'','2018-09-16 20:47:00','0000-00-00 00:00:00'),(52,20,'/admin/api/makeTs','','生成TS文件','',1,0,1,0,'','2018-09-17 10:07:11','0000-00-00 00:00:00'),(53,0,'/admin/user#','','玩家管理','<i class=\"layui-icon layui-icon-user\"></i>',1,1,1,0,'','2018-09-17 10:07:28','0000-00-00 00:00:00'),(54,53,'/admin/user','','玩家列表','<i class=\"layui-icon layui-icon-username\"></i>',1,1,1,0,'','2018-09-17 10:07:42','0000-00-00 00:00:00'),(55,54,'/admin/user/addRobot','','添加机器人','',1,0,1,0,'','2018-09-17 10:07:54','0000-00-00 00:00:00'),(56,54,'/admin/user/info','','玩家信息','',1,0,1,0,'','2018-09-17 10:08:06','0000-00-00 00:00:00'),(57,24,'/admin/rsync/add','','添加同步','',1,0,1,0,'','2018-09-17 16:16:34','2018-09-17 16:16:34'),(58,24,'/admin/rsync/edit','','修改同步','',1,0,1,0,'','2018-09-17 16:17:04','2018-09-17 16:17:04'),(59,24,'/admin/rsync/info','','同步信息','',1,0,1,0,'','2018-09-17 16:17:38','2018-09-17 16:17:38'),(60,24,'/admin/rsync/act','','数据同步','',1,0,1,0,'','2018-09-17 16:18:09','2018-09-21 22:41:45'),(61,36,'/admin/config/add','','添加配置','',1,0,1,0,'','2018-09-23 12:03:34','2018-09-23 12:03:34'),(62,36,'/admin/config/insert','','添加数据','',1,0,1,0,'','2018-09-23 17:11:45','2018-09-23 17:11:45'),(63,36,'/admin/config/modify','','修改数据','',1,0,1,0,'','2018-09-23 17:12:01','2018-09-23 17:12:01'),(64,36,'/admin/config/delete','','删除数据','',1,0,1,0,'','2018-09-23 17:12:28','2018-09-23 17:12:28'),(65,26,'/admin/cate/file','','配置文件','',1,0,1,0,'','2018-09-23 17:12:28','2018-09-23 17:12:28'),(66,12,'/admin/master/unsetPasswd','','重置密码','',1,0,1,0,'','2018-09-23 17:12:28','2018-09-23 17:12:28');
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
  `Name` varchar(255) NOT NULL COMMENT '名称',
  `Admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否超管，0否 ，1是',
  `MenuIds` text COMMENT '权限菜单',
  `Infos` varchar(255) DEFAULT NULL COMMENT '描述 ',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  KEY `ParentId` (`ParentId`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='后台-角色';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Role`
--

LOCK TABLES `Role` WRITE;
/*!40000 ALTER TABLE `Role` DISABLE KEYS */;
INSERT INTO `Role` VALUES (1,0,'开发者',1,'','','2018-09-09 11:42:15','2018-09-09 15:42:33'),(2,0,'系统管理员',0,'','','2018-09-09 11:43:02','2018-09-11 14:34:24');
/*!40000 ALTER TABLE `Role` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Rsync`
--

DROP TABLE IF EXISTS `Rsync`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8 ;
CREATE TABLE `Rsync` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL COMMENT '名称',
  `Instance` varchar(255) NOT NULL COMMENT '实例',
  `Method` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '操作方法',
  `Infos` varchar(255) DEFAULT NULL COMMENT '说明',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COMMENT='同步模块';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Rsync`
--

LOCK TABLES `Rsync` WRITE;
/*!40000 ALTER TABLE `Rsync` DISABLE KEYS */;
INSERT INTO `Rsync` VALUES (1,'系统菜单','\\models\\logic\\MenuLogic::instance()','rsync','','2018-09-17 16:21:29','2018-09-21 22:39:57'),(2,'系统角色','\\models\\logic\\RoleLogic::instance()','rsync','','2018-09-17 16:44:04','2018-09-21 22:40:05'),(3,'系统管理员','\\models\\logic\\MasterLogic::instance()','rsync','','2018-09-17 16:44:24','2018-09-21 22:40:13'),(4,'接口文档','\\models\\logic\\ApiLogic::instance()','rsync','','2018-09-17 16:44:51','2018-09-21 22:40:21'),(5,'分类管理','\\models\\logic\\CateLogic::instance()','rsync','','2018-09-17 16:45:08','2018-09-21 22:40:29'),(6,'快捷配置表','\\models\\logic\\ConfigLogic::instance()','rsync','','2018-09-17 16:45:49','2018-09-21 22:45:33'),(7,'所有快捷配置','\\models\\logic\\ConfigLogic::instance()','rsyncAll','','2018-09-17 16:46:25','2018-09-21 22:40:45'),(8,'同步模块','\\models\\logic\\RsyncLogic::instance()','rsync','','2018-09-17 16:47:14','2018-09-21 22:40:53'),(9,'图片资源','\\models\\logic\\SourceLogic::instance()','rsync','','2018-09-17 16:47:47','2018-09-21 22:41:01'),(10,'玩家列表','\\models\\logic\\UserLogic::instance()','rsync','','2018-09-17 16:48:05','2018-09-21 22:41:09');
/*!40000 ALTER TABLE `Rsync` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Source`
--

DROP TABLE IF EXISTS `Source`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8 ;
CREATE TABLE `Source` (
  `Id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `Name` varchar(255) NOT NULL COMMENT '名称',
  `Url` varchar(255) NOT NULL COMMENT '地址',
  `Cloud` varchar(255) DEFAULT NULL COMMENT '云存储地址',
  `Infos` varchar(255) DEFAULT NULL COMMENT '说明',
  `Created` datetime DEFAULT NULL,
  `Updated` datetime DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='图片资源';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Source`
--

LOCK TABLES `Source` WRITE;
/*!40000 ALTER TABLE `Source` DISABLE KEYS */;
/*!40000 ALTER TABLE `Source` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-23 18:16:39
