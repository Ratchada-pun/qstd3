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

 Date: 02/05/2018 16:11:13
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_qtrans_data
-- ----------------------------
DROP TABLE IF EXISTS `tb_qtrans_data`;
CREATE TABLE `tb_qtrans_data`  (
  `trans_ids` int(11) NOT NULL AUTO_INCREMENT,
  `ids` int(11) NULL DEFAULT NULL,
  `q_ids` int(11) NULL DEFAULT NULL COMMENT 'คิวไอดี',
  `servicegroupid` int(11) NULL DEFAULT NULL COMMENT 'ชื่อบริการ',
  `counter_service_id` int(11) NULL DEFAULT NULL COMMENT 'ช่องบริการ/ห้อง',
  `doctor_id` int(11) NULL DEFAULT NULL COMMENT 'แพทย์',
  `checkin_date` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'เวลาลงทะเบียนแผนก',
  `checkout_date` datetime(0) NULL DEFAULT NULL COMMENT 'เวลาออกแผนก',
  `service_status_id` int(11) NULL DEFAULT NULL COMMENT 'สถานะ',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่สร้าง',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่แก้ไข',
  `created_by` int(11) NULL DEFAULT NULL COMMENT 'ผู้บันทึก',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'ผู้แก้ไข',
  PRIMARY KEY (`trans_ids`) USING BTREE,
  INDEX `q_ids`(`q_ids`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

SET FOREIGN_KEY_CHECKS = 1;
