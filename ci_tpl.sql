/*
 Navicat Premium Data Transfer

 Source Server         : 192.168.56.101
 Source Server Type    : MySQL
 Source Server Version : 80011
 Source Host           : 192.168.56.101:3306
 Source Schema         : ci_tpl

 Target Server Type    : MySQL
 Target Server Version : 80011
 File Encoding         : 65001

 Date: 08/09/2018 19:10:27
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for Master
-- ----------------------------
DROP TABLE IF EXISTS `Master`;
CREATE TABLE `Master`  (
  `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Realname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '真实姓名',
  `RoleId` int(10) UNSIGNED NOT NULL COMMENT '角色ID',
  `Account` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '账号',
  `Password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '密码',
  `Token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '登录令牌',
  `State` tinyint(1) NOT NULL DEFAULT 0 COMMENT '状态，0禁用，1启用',
  `Created` datetime(0) NULL DEFAULT NULL,
  `Updated` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  INDEX `Realname`(`Realname`) USING BTREE,
  INDEX `RoleId`(`RoleId`) USING BTREE,
  INDEX `Account`(`Account`) USING BTREE,
  INDEX `Token`(`Token`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 100000 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台-管理员' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for Menu
-- ----------------------------
DROP TABLE IF EXISTS `Menu`;
CREATE TABLE `Menu`  (
  `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ParentId` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级菜单 ID',
  `Url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT ' 路由地址',
  `UrlAlias` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT ' 路由别名',
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT ' 菜单名称',
  `Icon` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT ' 菜单图标',
  `Ctrl` tinyint(1) NOT NULL DEFAULT 1 COMMENT ' 是否限制， 0否，1是',
  `Display` tinyint(1) NOT NULL DEFAULT 0 COMMENT ' 是否显示，0隐藏，1显示',
  `State` tinyint(1) NOT NULL DEFAULT 0 COMMENT ' 是否开启，0禁用，1开启，',
  `Sort` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT ' 排序',
  `Infos` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT ' 功能说明 ',
  `Created` datetime(0) NULL DEFAULT NULL,
  `Updated` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  INDEX `ParentId`(`ParentId`) USING BTREE,
  INDEX `Url`(`Url`) USING BTREE,
  INDEX `UrlAlias`(`UrlAlias`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 300000 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台-菜单' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for Role
-- ----------------------------
DROP TABLE IF EXISTS `Role`;
CREATE TABLE `Role`  (
  `Id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ParentId` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '上级ID',
  `Name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '名称',
  `Admin` tinyint(1) NOT NULL DEFAULT 0 COMMENT '是否超管，0否 ，1是',
  `MenuIds` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT '权限菜单',
  `Infos` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT '描述 ',
  `Created` datetime(0) NULL DEFAULT NULL,
  `Updated` datetime(0) NULL DEFAULT NULL,
  PRIMARY KEY (`Id`) USING BTREE,
  INDEX `ParentId`(`ParentId`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 200000 CHARACTER SET = utf8 COLLATE = utf8_general_ci COMMENT = '后台-角色' ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
