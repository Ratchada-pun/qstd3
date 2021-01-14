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

 Date: 02/05/2018 16:11:20
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for tb_quequ_data
-- ----------------------------
DROP TABLE IF EXISTS `tb_quequ_data`;
CREATE TABLE `tb_quequ_data`  (
  `ids` int(11) NOT NULL AUTO_INCREMENT,
  `q_ids` int(11) NULL DEFAULT NULL COMMENT 'running',
  `q_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'หมายเลขคิว',
  `q_timestp` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่ออกคิว',
  `pt_id` int(11) NULL DEFAULT NULL,
  `q_vn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Visit number ของผู้ป่วย',
  `q_hn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'หมายเลข HN ผู้ป่วย',
  `pt_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อผู้ป่วย',
  `pt_visit_type_id` int(11) NULL DEFAULT NULL COMMENT 'ประเภท',
  `pt_appoint_sec_id` int(11) NULL DEFAULT NULL COMMENT 'แผนกที่นัดหมาย',
  `serviceid` int(11) NULL DEFAULT NULL COMMENT 'ประเภทบริการ',
  `servicegroupid` int(11) NULL DEFAULT NULL,
  `q_status_id` int(11) NULL DEFAULT NULL COMMENT 'สถานะ',
  `doctor_id` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'แพทย์',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่บันทึก',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่แก้ไข',
  PRIMARY KEY (`ids`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 21 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

SET FOREIGN_KEY_CHECKS = 1;
