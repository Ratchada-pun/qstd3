/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MariaDB
 Source Server Version : 100130
 Source Host           : localhost:3306
 Source Schema         : db_banbung_prod

 Target Server Type    : MariaDB
 Target Server Version : 100130
 File Encoding         : 65001

 Date: 02/05/2018 16:11:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_caller_data
-- ----------------------------
DROP TABLE IF EXISTS `tb_caller_data`;
CREATE TABLE `tb_caller_data`  (
  `ids` int(11) NOT NULL AUTO_INCREMENT,
  `caller_ids` int(11) NULL DEFAULT NULL COMMENT 'running',
  `q_ids` int(11) NULL DEFAULT NULL,
  `qtran_ids` int(11) NULL DEFAULT NULL,
  `servicegroupid` int(11) NULL DEFAULT NULL,
  `counter_service_id` int(11) NULL DEFAULT NULL COMMENT 'ชื่อช่องบริการ',
  `call_timestp` datetime(0) NULL DEFAULT NULL COMMENT 'เวลาที่เรียก',
  `created_by` int(11) NULL DEFAULT NULL COMMENT 'ผู้เรียก',
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `call_status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'รอเรียก/เรียกแล้ว/Hold',
  PRIMARY KEY (`ids`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
