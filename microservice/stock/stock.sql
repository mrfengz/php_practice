/*
 Navicat Premium Data Transfer

 Source Server         : ubuntu
 Source Server Type    : MariaDB
 Source Server Version : 100325
 Source Host           : 192.168.1.247:3306
 Source Schema         : stock

 Target Server Type    : MariaDB
 Target Server Version : 100325
 File Encoding         : 65001

 Date: 20/02/2021 14:49:53
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for push_setting
-- ----------------------------
DROP TABLE IF EXISTS `push_setting`;
CREATE TABLE `push_setting`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `token` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `secret` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `user_id` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `at_mobiles` varchar(512) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' COMMENT '多个手机号用‘,’分割',
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1: 启用;  2:暂停 9:删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `update_time` int(10) UNSIGNED NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for stock
-- ----------------------------
DROP TABLE IF EXISTS `stock`;
CREATE TABLE `stock`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `stock_type` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'sh:上证 sz：深证',
  `stock_code` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `stock_name` varchar(12) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `is_warning` tinyint(1) UNSIGNED NOT NULL DEFAULT 0 COMMENT '1:预警 2：不预警',
  `day_warning_min` smallint(6) NOT NULL DEFAULT 0,
  `day_warning_max` smallint(6) NOT NULL DEFAULT 0,
  `cost` int(10) NOT NULL DEFAULT 0,
  `cost_warning_min` smallint(6) NOT NULL DEFAULT 0,
  `cost_warning_max` smallint(5) UNSIGNED NOT NULL DEFAULT 0,
  `user_id` int(11) NOT NULL,
  `status` tinyint(1) UNSIGNED NOT NULL DEFAULT 1 COMMENT '1:正常 9：删除',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(64) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL COMMENT '0:未激活，1:激活, 2:禁用，9:删除',
  `create_time` int(10) UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建时间',
  `token` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uni_token`(`token`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;
