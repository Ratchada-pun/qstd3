/*
 Navicat Premium Data Transfer

 Source Server         : localhost_wamp
 Source Server Type    : MariaDB
 Source Server Version : 100309
 Source Host           : localhost:3307
 Source Schema         : db_trang

 Target Server Type    : MariaDB
 Target Server Version : 100309
 File Encoding         : 65001

 Date: 23/09/2019 16:20:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_service_profile
-- ----------------------------
DROP TABLE IF EXISTS `tb_service_profile`;
CREATE TABLE `tb_service_profile`  (
  `service_profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `counterservice_typeid` int(11) NOT NULL,
  `service_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `service_profile_status` int(11) NOT NULL,
  `counter_service_ids` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`service_profile_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_service_profile
-- ----------------------------
INSERT INTO `tb_service_profile` VALUES (1, 'ซักประวัติ', 10, '1,11,12', 1, '39,40');
INSERT INTO `tb_service_profile` VALUES (2, 'ห้องตรวจ', 11, '1,11,12', 1, NULL);
INSERT INTO `tb_service_profile` VALUES (3, 'จุดรับยา', 12, '1,11,12', 1, '');

SET FOREIGN_KEY_CHECKS = 1;
