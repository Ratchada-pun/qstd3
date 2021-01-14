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

 Date: 26/04/2018 10:21:33
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for auth_assignment
-- ----------------------------
DROP TABLE IF EXISTS `auth_assignment`;
CREATE TABLE `auth_assignment`  (
  `item_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `user_id` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`item_name`, `user_id`) USING BTREE,
  INDEX `auth_assignment_user_id_idx`(`user_id`) USING BTREE,
  CONSTRAINT `auth_assignment_ibfk_1` FOREIGN KEY (`item_name`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_assignment
-- ----------------------------
INSERT INTO `auth_assignment` VALUES ('Admin', '2', 1517981033);

-- ----------------------------
-- Table structure for auth_item
-- ----------------------------
DROP TABLE IF EXISTS `auth_item`;
CREATE TABLE `auth_item`  (
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `type` smallint(6) NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `rule_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `data` blob NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`) USING BTREE,
  INDEX `rule_name`(`rule_name`) USING BTREE,
  INDEX `idx-auth_item-type`(`type`) USING BTREE,
  CONSTRAINT `auth_item_ibfk_1` FOREIGN KEY (`rule_name`) REFERENCES `auth_rule` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_item
-- ----------------------------
INSERT INTO `auth_item` VALUES ('/*', 2, NULL, NULL, NULL, 1517978963, 1517978963);
INSERT INTO `auth_item` VALUES ('/admin-manager/*', 2, NULL, NULL, NULL, 1517978956, 1517978956);
INSERT INTO `auth_item` VALUES ('/kiosk/*', 2, NULL, NULL, NULL, 1520584597, 1520584597);
INSERT INTO `auth_item` VALUES ('/site/*', 2, NULL, NULL, NULL, 1517978961, 1517978961);
INSERT INTO `auth_item` VALUES ('/user/*', 2, NULL, NULL, NULL, 1517978947, 1517978947);
INSERT INTO `auth_item` VALUES ('/user/settings/*', 2, NULL, NULL, NULL, 1517979207, 1517979207);
INSERT INTO `auth_item` VALUES ('Admin', 1, 'ผู้ดูแลระบบ', NULL, NULL, 1517981019, 1517981019);
INSERT INTO `auth_item` VALUES ('App', 2, NULL, NULL, NULL, 1523348008, 1523348008);
INSERT INTO `auth_item` VALUES ('User', 1, 'ผู้ใช้งาน', NULL, NULL, 1517978934, 1517978934);
INSERT INTO `auth_item` VALUES ('ห้องตรวจโรค', 2, NULL, NULL, NULL, 1520584617, 1523347993);
INSERT INTO `auth_item` VALUES ('เวชระเบียน', 2, NULL, NULL, NULL, 1520584559, 1523347976);

-- ----------------------------
-- Table structure for auth_item_child
-- ----------------------------
DROP TABLE IF EXISTS `auth_item_child`;
CREATE TABLE `auth_item_child`  (
  `parent` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `child` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`parent`, `child`) USING BTREE,
  INDEX `child`(`child`) USING BTREE,
  CONSTRAINT `auth_item_child_ibfk_1` FOREIGN KEY (`parent`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `auth_item_child_ibfk_2` FOREIGN KEY (`child`) REFERENCES `auth_item` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of auth_item_child
-- ----------------------------
INSERT INTO `auth_item_child` VALUES ('Admin', '/*');
INSERT INTO `auth_item_child` VALUES ('Admin', 'App');
INSERT INTO `auth_item_child` VALUES ('Admin', 'ห้องตรวจโรค');
INSERT INTO `auth_item_child` VALUES ('Admin', 'เวชระเบียน');
INSERT INTO `auth_item_child` VALUES ('App', '/site/*');
INSERT INTO `auth_item_child` VALUES ('User', '/site/*');
INSERT INTO `auth_item_child` VALUES ('User', '/user/settings/*');
INSERT INTO `auth_item_child` VALUES ('User', 'App');
INSERT INTO `auth_item_child` VALUES ('User', 'ห้องตรวจโรค');
INSERT INTO `auth_item_child` VALUES ('User', 'เวชระเบียน');
INSERT INTO `auth_item_child` VALUES ('ห้องตรวจโรค', '/kiosk/*');
INSERT INTO `auth_item_child` VALUES ('เวชระเบียน', '/kiosk/*');

-- ----------------------------
-- Table structure for auth_rule
-- ----------------------------
DROP TABLE IF EXISTS `auth_rule`;
CREATE TABLE `auth_rule`  (
  `name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data` blob NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`name`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for file_storage_item
-- ----------------------------
DROP TABLE IF EXISTS `file_storage_item`;
CREATE TABLE `file_storage_item`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `component` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `base_url` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `path` varchar(1024) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `size` int(11) NULL DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `upload_ip` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 18 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of file_storage_item
-- ----------------------------
INSERT INTO `file_storage_item` VALUES (4, 'fileStorage', '/uploads', '1/HqOmC4NQtOBWkm3nPTb8SZkAulHeJB5C.png', 'image/png', 131577, 'HqOmC4NQtOBWkm3nPTb8SZkAulHeJB5C', '127.0.0.1', 1518077491);
INSERT INTO `file_storage_item` VALUES (6, 'fileStorage', '/uploads', '1/wztTX4WnG9CJmJm8qCu373LZktLyB_ZB.png', 'image/png', 131577, 'wztTX4WnG9CJmJm8qCu373LZktLyB_ZB', '127.0.0.1', 1518354952);
INSERT INTO `file_storage_item` VALUES (11, 'fileStorage', '/uploads', '1/LhB7uf0Y_-YMshBRI7JND4PcskgScK9_.png', 'image/png', 131577, 'LhB7uf0Y_-YMshBRI7JND4PcskgScK9_', '127.0.0.1', 1518356171);
INSERT INTO `file_storage_item` VALUES (13, 'fileStorage', '/uploads', '1/toCzqUFuEC9ZULBVuZdydEy2fvXQaMfS.jpg', 'image/jpeg', 112864, 'toCzqUFuEC9ZULBVuZdydEy2fvXQaMfS', '127.0.0.1', 1518497803);
INSERT INTO `file_storage_item` VALUES (14, 'fileStorage', '/uploads', '1/85Gt_w6b3RPYeBFZqdLfB2Q49UoOPejg.png', 'image/png', 131577, '85Gt_w6b3RPYeBFZqdLfB2Q49UoOPejg', '127.0.0.1', 1519360037);
INSERT INTO `file_storage_item` VALUES (15, 'fileStorage', '/uploads', '1/lvCa3PFqJzOASW145okm6zG0UDzx9Qcy.png', 'image/png', 131577, 'lvCa3PFqJzOASW145okm6zG0UDzx9Qcy', '127.0.0.1', 1520569421);
INSERT INTO `file_storage_item` VALUES (16, 'fileStorage', '/uploads', '1/bYqPMMJ3pXUQ2RO-QtdIhqwVkv_Tlpp5.png', 'image/png', 131577, 'bYqPMMJ3pXUQ2RO-QtdIhqwVkv_Tlpp5', '127.0.0.1', 1523424119);
INSERT INTO `file_storage_item` VALUES (17, 'fileStorage', '/uploads', '1/ktOk2ipqA3sX7_yvjNmqANML1goEdfm7.png', 'image/png', 131577, 'ktOk2ipqA3sX7_yvjNmqANML1goEdfm7', '127.0.0.1', 1524631707);

-- ----------------------------
-- Table structure for icons
-- ----------------------------
DROP TABLE IF EXISTS `icons`;
CREATE TABLE `icons`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 676 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of icons
-- ----------------------------
INSERT INTO `icons` VALUES (1, 'glass', 'fa');
INSERT INTO `icons` VALUES (2, 'music', 'fa');
INSERT INTO `icons` VALUES (3, 'search', 'fa');
INSERT INTO `icons` VALUES (4, 'envelope-o', 'fa');
INSERT INTO `icons` VALUES (5, 'heart', 'fa');
INSERT INTO `icons` VALUES (6, 'star', 'fa');
INSERT INTO `icons` VALUES (7, 'star-o', 'fa');
INSERT INTO `icons` VALUES (8, 'user', 'fa');
INSERT INTO `icons` VALUES (9, 'film', 'fa');
INSERT INTO `icons` VALUES (10, 'th-large', 'fa');
INSERT INTO `icons` VALUES (11, 'th', 'fa');
INSERT INTO `icons` VALUES (12, 'th-list', 'fa');
INSERT INTO `icons` VALUES (13, 'check', 'fa');
INSERT INTO `icons` VALUES (14, 'times', 'fa');
INSERT INTO `icons` VALUES (15, 'search-plus', 'fa');
INSERT INTO `icons` VALUES (16, 'search-minus', 'fa');
INSERT INTO `icons` VALUES (17, 'power-off', 'fa');
INSERT INTO `icons` VALUES (18, 'signal', 'fa');
INSERT INTO `icons` VALUES (19, 'cog', 'fa');
INSERT INTO `icons` VALUES (20, 'trash-o', 'fa');
INSERT INTO `icons` VALUES (21, 'home', 'fa');
INSERT INTO `icons` VALUES (22, 'file-o', 'fa');
INSERT INTO `icons` VALUES (23, 'clock-o', 'fa');
INSERT INTO `icons` VALUES (24, 'road', 'fa');
INSERT INTO `icons` VALUES (25, 'download', 'fa');
INSERT INTO `icons` VALUES (26, 'arrow-circle-o-down', 'fa');
INSERT INTO `icons` VALUES (27, 'arrow-circle-o-up', 'fa');
INSERT INTO `icons` VALUES (28, 'inbox', 'fa');
INSERT INTO `icons` VALUES (29, 'play-circle-o', 'fa');
INSERT INTO `icons` VALUES (30, 'repeat', 'fa');
INSERT INTO `icons` VALUES (31, 'refresh', 'fa');
INSERT INTO `icons` VALUES (32, 'list-alt', 'fa');
INSERT INTO `icons` VALUES (33, 'lock', 'fa');
INSERT INTO `icons` VALUES (34, 'flag', 'fa');
INSERT INTO `icons` VALUES (35, 'headphones', 'fa');
INSERT INTO `icons` VALUES (36, 'volume-off', 'fa');
INSERT INTO `icons` VALUES (37, 'volume-down', 'fa');
INSERT INTO `icons` VALUES (38, 'volume-up', 'fa');
INSERT INTO `icons` VALUES (39, 'qrcode', 'fa');
INSERT INTO `icons` VALUES (40, 'barcode', 'fa');
INSERT INTO `icons` VALUES (41, 'tag', 'fa');
INSERT INTO `icons` VALUES (42, 'tags', 'fa');
INSERT INTO `icons` VALUES (43, 'book', 'fa');
INSERT INTO `icons` VALUES (44, 'bookmark', 'fa');
INSERT INTO `icons` VALUES (45, 'print', 'fa');
INSERT INTO `icons` VALUES (46, 'camera', 'fa');
INSERT INTO `icons` VALUES (47, 'font', 'fa');
INSERT INTO `icons` VALUES (48, 'bold', 'fa');
INSERT INTO `icons` VALUES (49, 'italic', 'fa');
INSERT INTO `icons` VALUES (50, 'text-height', 'fa');
INSERT INTO `icons` VALUES (51, 'text-width', 'fa');
INSERT INTO `icons` VALUES (52, 'align-left', 'fa');
INSERT INTO `icons` VALUES (53, 'align-center', 'fa');
INSERT INTO `icons` VALUES (54, 'align-right', 'fa');
INSERT INTO `icons` VALUES (55, 'align-justify', 'fa');
INSERT INTO `icons` VALUES (56, 'list', 'fa');
INSERT INTO `icons` VALUES (57, 'outdent', 'fa');
INSERT INTO `icons` VALUES (58, 'indent', 'fa');
INSERT INTO `icons` VALUES (59, 'video-camera', 'fa');
INSERT INTO `icons` VALUES (60, 'picture-o', 'fa');
INSERT INTO `icons` VALUES (61, 'pencil', 'fa');
INSERT INTO `icons` VALUES (62, 'map-marker', 'fa');
INSERT INTO `icons` VALUES (63, 'adjust', 'fa');
INSERT INTO `icons` VALUES (64, 'tint', 'fa');
INSERT INTO `icons` VALUES (65, 'pencil-square-o', 'fa');
INSERT INTO `icons` VALUES (66, 'share-square-o', 'fa');
INSERT INTO `icons` VALUES (67, 'check-square-o', 'fa');
INSERT INTO `icons` VALUES (68, 'arrows', 'fa');
INSERT INTO `icons` VALUES (69, 'step-backward', 'fa');
INSERT INTO `icons` VALUES (70, 'fast-backward', 'fa');
INSERT INTO `icons` VALUES (71, 'backward', 'fa');
INSERT INTO `icons` VALUES (72, 'play', 'fa');
INSERT INTO `icons` VALUES (73, 'pause', 'fa');
INSERT INTO `icons` VALUES (74, 'stop', 'fa');
INSERT INTO `icons` VALUES (75, 'forward', 'fa');
INSERT INTO `icons` VALUES (76, 'fast-forward', 'fa');
INSERT INTO `icons` VALUES (77, 'step-forward', 'fa');
INSERT INTO `icons` VALUES (78, 'eject', 'fa');
INSERT INTO `icons` VALUES (79, 'chevron-left', 'fa');
INSERT INTO `icons` VALUES (80, 'chevron-right', 'fa');
INSERT INTO `icons` VALUES (81, 'plus-circle', 'fa');
INSERT INTO `icons` VALUES (82, 'minus-circle', 'fa');
INSERT INTO `icons` VALUES (83, 'times-circle', 'fa');
INSERT INTO `icons` VALUES (84, 'check-circle', 'fa');
INSERT INTO `icons` VALUES (85, 'question-circle', 'fa');
INSERT INTO `icons` VALUES (86, 'info-circle', 'fa');
INSERT INTO `icons` VALUES (87, 'crosshairs', 'fa');
INSERT INTO `icons` VALUES (88, 'times-circle-o', 'fa');
INSERT INTO `icons` VALUES (89, 'check-circle-o', 'fa');
INSERT INTO `icons` VALUES (90, 'ban', 'fa');
INSERT INTO `icons` VALUES (91, 'arrow-left', 'fa');
INSERT INTO `icons` VALUES (92, 'arrow-right', 'fa');
INSERT INTO `icons` VALUES (93, 'arrow-up', 'fa');
INSERT INTO `icons` VALUES (94, 'arrow-down', 'fa');
INSERT INTO `icons` VALUES (95, 'share', 'fa');
INSERT INTO `icons` VALUES (96, 'expand', 'fa');
INSERT INTO `icons` VALUES (97, 'compress', 'fa');
INSERT INTO `icons` VALUES (98, 'plus', 'fa');
INSERT INTO `icons` VALUES (99, 'minus', 'fa');
INSERT INTO `icons` VALUES (100, 'asterisk', 'fa');
INSERT INTO `icons` VALUES (101, 'exclamation-circle', 'fa');
INSERT INTO `icons` VALUES (102, 'gift', 'fa');
INSERT INTO `icons` VALUES (103, 'leaf', 'fa');
INSERT INTO `icons` VALUES (104, 'fire', 'fa');
INSERT INTO `icons` VALUES (105, 'eye', 'fa');
INSERT INTO `icons` VALUES (106, 'eye-slash', 'fa');
INSERT INTO `icons` VALUES (107, 'exclamation-triangle', 'fa');
INSERT INTO `icons` VALUES (108, 'plane', 'fa');
INSERT INTO `icons` VALUES (109, 'calendar', 'fa');
INSERT INTO `icons` VALUES (110, 'random', 'fa');
INSERT INTO `icons` VALUES (111, 'comment', 'fa');
INSERT INTO `icons` VALUES (112, 'magnet', 'fa');
INSERT INTO `icons` VALUES (113, 'chevron-up', 'fa');
INSERT INTO `icons` VALUES (114, 'chevron-down', 'fa');
INSERT INTO `icons` VALUES (115, 'retweet', 'fa');
INSERT INTO `icons` VALUES (116, 'shopping-cart', 'fa');
INSERT INTO `icons` VALUES (117, 'folder', 'fa');
INSERT INTO `icons` VALUES (118, 'folder-open', 'fa');
INSERT INTO `icons` VALUES (119, 'arrows-v', 'fa');
INSERT INTO `icons` VALUES (120, 'arrows-h', 'fa');
INSERT INTO `icons` VALUES (121, 'bar-chart', 'fa');
INSERT INTO `icons` VALUES (122, 'twitter-square', 'fa');
INSERT INTO `icons` VALUES (123, 'facebook-square', 'fa');
INSERT INTO `icons` VALUES (124, 'camera-retro', 'fa');
INSERT INTO `icons` VALUES (125, 'key', 'fa');
INSERT INTO `icons` VALUES (126, 'cogs', 'fa');
INSERT INTO `icons` VALUES (127, 'comments', 'fa');
INSERT INTO `icons` VALUES (128, 'thumbs-o-up', 'fa');
INSERT INTO `icons` VALUES (129, 'thumbs-o-down', 'fa');
INSERT INTO `icons` VALUES (130, 'star-half', 'fa');
INSERT INTO `icons` VALUES (131, 'heart-o', 'fa');
INSERT INTO `icons` VALUES (132, 'sign-out', 'fa');
INSERT INTO `icons` VALUES (133, 'linkedin-square', 'fa');
INSERT INTO `icons` VALUES (134, 'thumb-tack', 'fa');
INSERT INTO `icons` VALUES (135, 'external-link', 'fa');
INSERT INTO `icons` VALUES (136, 'sign-in', 'fa');
INSERT INTO `icons` VALUES (137, 'trophy', 'fa');
INSERT INTO `icons` VALUES (138, 'github-square', 'fa');
INSERT INTO `icons` VALUES (139, 'upload', 'fa');
INSERT INTO `icons` VALUES (140, 'lemon-o', 'fa');
INSERT INTO `icons` VALUES (141, 'phone', 'fa');
INSERT INTO `icons` VALUES (142, 'square-o', 'fa');
INSERT INTO `icons` VALUES (143, 'bookmark-o', 'fa');
INSERT INTO `icons` VALUES (144, 'phone-square', 'fa');
INSERT INTO `icons` VALUES (145, 'twitter', 'fa');
INSERT INTO `icons` VALUES (146, 'facebook', 'fa');
INSERT INTO `icons` VALUES (147, 'github', 'fa');
INSERT INTO `icons` VALUES (148, 'unlock', 'fa');
INSERT INTO `icons` VALUES (149, 'credit-card', 'fa');
INSERT INTO `icons` VALUES (150, 'rss', 'fa');
INSERT INTO `icons` VALUES (151, 'hdd-o', 'fa');
INSERT INTO `icons` VALUES (152, 'bullhorn', 'fa');
INSERT INTO `icons` VALUES (153, 'bell', 'fa');
INSERT INTO `icons` VALUES (154, 'certificate', 'fa');
INSERT INTO `icons` VALUES (155, 'hand-o-right', 'fa');
INSERT INTO `icons` VALUES (156, 'hand-o-left', 'fa');
INSERT INTO `icons` VALUES (157, 'hand-o-up', 'fa');
INSERT INTO `icons` VALUES (158, 'hand-o-down', 'fa');
INSERT INTO `icons` VALUES (159, 'arrow-circle-left', 'fa');
INSERT INTO `icons` VALUES (160, 'arrow-circle-right', 'fa');
INSERT INTO `icons` VALUES (161, 'arrow-circle-up', 'fa');
INSERT INTO `icons` VALUES (162, 'arrow-circle-down', 'fa');
INSERT INTO `icons` VALUES (163, 'globe', 'fa');
INSERT INTO `icons` VALUES (164, 'wrench', 'fa');
INSERT INTO `icons` VALUES (165, 'tasks', 'fa');
INSERT INTO `icons` VALUES (166, 'filter', 'fa');
INSERT INTO `icons` VALUES (167, 'briefcase', 'fa');
INSERT INTO `icons` VALUES (168, 'arrows-alt', 'fa');
INSERT INTO `icons` VALUES (169, 'users', 'fa');
INSERT INTO `icons` VALUES (170, 'link', 'fa');
INSERT INTO `icons` VALUES (171, 'cloud', 'fa');
INSERT INTO `icons` VALUES (172, 'flask', 'fa');
INSERT INTO `icons` VALUES (173, 'scissors', 'fa');
INSERT INTO `icons` VALUES (174, 'files-o', 'fa');
INSERT INTO `icons` VALUES (175, 'paperclip', 'fa');
INSERT INTO `icons` VALUES (176, 'floppy-o', 'fa');
INSERT INTO `icons` VALUES (177, 'square', 'fa');
INSERT INTO `icons` VALUES (178, 'bars', 'fa');
INSERT INTO `icons` VALUES (179, 'list-ul', 'fa');
INSERT INTO `icons` VALUES (180, 'list-ol', 'fa');
INSERT INTO `icons` VALUES (181, 'strikethrough', 'fa');
INSERT INTO `icons` VALUES (182, 'underline', 'fa');
INSERT INTO `icons` VALUES (183, 'table', 'fa');
INSERT INTO `icons` VALUES (184, 'magic', 'fa');
INSERT INTO `icons` VALUES (185, 'truck', 'fa');
INSERT INTO `icons` VALUES (186, 'pinterest', 'fa');
INSERT INTO `icons` VALUES (187, 'pinterest-square', 'fa');
INSERT INTO `icons` VALUES (188, 'google-plus-square', 'fa');
INSERT INTO `icons` VALUES (189, 'google-plus', 'fa');
INSERT INTO `icons` VALUES (190, 'money', 'fa');
INSERT INTO `icons` VALUES (191, 'caret-down', 'fa');
INSERT INTO `icons` VALUES (192, 'caret-up', 'fa');
INSERT INTO `icons` VALUES (193, 'caret-left', 'fa');
INSERT INTO `icons` VALUES (194, 'caret-right', 'fa');
INSERT INTO `icons` VALUES (195, 'columns', 'fa');
INSERT INTO `icons` VALUES (196, 'sort', 'fa');
INSERT INTO `icons` VALUES (197, 'sort-desc', 'fa');
INSERT INTO `icons` VALUES (198, 'sort-asc', 'fa');
INSERT INTO `icons` VALUES (199, 'envelope', 'fa');
INSERT INTO `icons` VALUES (200, 'linkedin', 'fa');
INSERT INTO `icons` VALUES (201, 'undo', 'fa');
INSERT INTO `icons` VALUES (202, 'gavel', 'fa');
INSERT INTO `icons` VALUES (203, 'tachometer', 'fa');
INSERT INTO `icons` VALUES (204, 'comment-o', 'fa');
INSERT INTO `icons` VALUES (205, 'comments-o', 'fa');
INSERT INTO `icons` VALUES (206, 'bolt', 'fa');
INSERT INTO `icons` VALUES (207, 'sitemap', 'fa');
INSERT INTO `icons` VALUES (208, 'umbrella', 'fa');
INSERT INTO `icons` VALUES (209, 'clipboard', 'fa');
INSERT INTO `icons` VALUES (210, 'lightbulb-o', 'fa');
INSERT INTO `icons` VALUES (211, 'exchange', 'fa');
INSERT INTO `icons` VALUES (212, 'cloud-download', 'fa');
INSERT INTO `icons` VALUES (213, 'cloud-upload', 'fa');
INSERT INTO `icons` VALUES (214, 'user-md', 'fa');
INSERT INTO `icons` VALUES (215, 'stethoscope', 'fa');
INSERT INTO `icons` VALUES (216, 'suitcase', 'fa');
INSERT INTO `icons` VALUES (217, 'bell-o', 'fa');
INSERT INTO `icons` VALUES (218, 'coffee', 'fa');
INSERT INTO `icons` VALUES (219, 'cutlery', 'fa');
INSERT INTO `icons` VALUES (220, 'file-text-o', 'fa');
INSERT INTO `icons` VALUES (221, 'building-o', 'fa');
INSERT INTO `icons` VALUES (222, 'hospital-o', 'fa');
INSERT INTO `icons` VALUES (223, 'ambulance', 'fa');
INSERT INTO `icons` VALUES (224, 'medkit', 'fa');
INSERT INTO `icons` VALUES (225, 'fighter-jet', 'fa');
INSERT INTO `icons` VALUES (226, 'beer', 'fa');
INSERT INTO `icons` VALUES (227, 'h-square', 'fa');
INSERT INTO `icons` VALUES (228, 'plus-square', 'fa');
INSERT INTO `icons` VALUES (229, 'angle-double-left', 'fa');
INSERT INTO `icons` VALUES (230, 'angle-double-right', 'fa');
INSERT INTO `icons` VALUES (231, 'angle-double-up', 'fa');
INSERT INTO `icons` VALUES (232, 'angle-double-down', 'fa');
INSERT INTO `icons` VALUES (233, 'angle-left', 'fa');
INSERT INTO `icons` VALUES (234, 'angle-right', 'fa');
INSERT INTO `icons` VALUES (235, 'angle-up', 'fa');
INSERT INTO `icons` VALUES (236, 'angle-down', 'fa');
INSERT INTO `icons` VALUES (237, 'desktop', 'fa');
INSERT INTO `icons` VALUES (238, 'laptop', 'fa');
INSERT INTO `icons` VALUES (239, 'tablet', 'fa');
INSERT INTO `icons` VALUES (240, 'mobile', 'fa');
INSERT INTO `icons` VALUES (241, 'circle-o', 'fa');
INSERT INTO `icons` VALUES (242, 'quote-left', 'fa');
INSERT INTO `icons` VALUES (243, 'quote-right', 'fa');
INSERT INTO `icons` VALUES (244, 'spinner', 'fa');
INSERT INTO `icons` VALUES (245, 'circle', 'fa');
INSERT INTO `icons` VALUES (246, 'reply', 'fa');
INSERT INTO `icons` VALUES (247, 'github-alt', 'fa');
INSERT INTO `icons` VALUES (248, 'folder-o', 'fa');
INSERT INTO `icons` VALUES (249, 'folder-open-o', 'fa');
INSERT INTO `icons` VALUES (250, 'smile-o', 'fa');
INSERT INTO `icons` VALUES (251, 'frown-o', 'fa');
INSERT INTO `icons` VALUES (252, 'meh-o', 'fa');
INSERT INTO `icons` VALUES (253, 'gamepad', 'fa');
INSERT INTO `icons` VALUES (254, 'keyboard-o', 'fa');
INSERT INTO `icons` VALUES (255, 'flag-o', 'fa');
INSERT INTO `icons` VALUES (256, 'flag-checkered', 'fa');
INSERT INTO `icons` VALUES (257, 'terminal', 'fa');
INSERT INTO `icons` VALUES (258, 'code', 'fa');
INSERT INTO `icons` VALUES (259, 'reply-all', 'fa');
INSERT INTO `icons` VALUES (260, 'star-half-o', 'fa');
INSERT INTO `icons` VALUES (261, 'location-arrow', 'fa');
INSERT INTO `icons` VALUES (262, 'crop', 'fa');
INSERT INTO `icons` VALUES (263, 'code-fork', 'fa');
INSERT INTO `icons` VALUES (264, 'chain-broken', 'fa');
INSERT INTO `icons` VALUES (265, 'question', 'fa');
INSERT INTO `icons` VALUES (266, 'info', 'fa');
INSERT INTO `icons` VALUES (267, 'exclamation', 'fa');
INSERT INTO `icons` VALUES (268, 'superscript', 'fa');
INSERT INTO `icons` VALUES (269, 'subscript', 'fa');
INSERT INTO `icons` VALUES (270, 'eraser', 'fa');
INSERT INTO `icons` VALUES (271, 'puzzle-piece', 'fa');
INSERT INTO `icons` VALUES (272, 'microphone', 'fa');
INSERT INTO `icons` VALUES (273, 'microphone-slash', 'fa');
INSERT INTO `icons` VALUES (274, 'shield', 'fa');
INSERT INTO `icons` VALUES (275, 'calendar-o', 'fa');
INSERT INTO `icons` VALUES (276, 'fire-extinguisher', 'fa');
INSERT INTO `icons` VALUES (277, 'rocket', 'fa');
INSERT INTO `icons` VALUES (278, 'maxcdn', 'fa');
INSERT INTO `icons` VALUES (279, 'chevron-circle-left', 'fa');
INSERT INTO `icons` VALUES (280, 'chevron-circle-right', 'fa');
INSERT INTO `icons` VALUES (281, 'chevron-circle-up', 'fa');
INSERT INTO `icons` VALUES (282, 'chevron-circle-down', 'fa');
INSERT INTO `icons` VALUES (283, 'html5', 'fa');
INSERT INTO `icons` VALUES (284, 'css3', 'fa');
INSERT INTO `icons` VALUES (285, 'anchor', 'fa');
INSERT INTO `icons` VALUES (286, 'unlock-alt', 'fa');
INSERT INTO `icons` VALUES (287, 'bullseye', 'fa');
INSERT INTO `icons` VALUES (288, 'ellipsis-h', 'fa');
INSERT INTO `icons` VALUES (289, 'ellipsis-v', 'fa');
INSERT INTO `icons` VALUES (290, 'rss-square', 'fa');
INSERT INTO `icons` VALUES (291, 'play-circle', 'fa');
INSERT INTO `icons` VALUES (292, 'ticket', 'fa');
INSERT INTO `icons` VALUES (293, 'minus-square', 'fa');
INSERT INTO `icons` VALUES (294, 'minus-square-o', 'fa');
INSERT INTO `icons` VALUES (295, 'level-up', 'fa');
INSERT INTO `icons` VALUES (296, 'level-down', 'fa');
INSERT INTO `icons` VALUES (297, 'check-square', 'fa');
INSERT INTO `icons` VALUES (298, 'pencil-square', 'fa');
INSERT INTO `icons` VALUES (299, 'external-link-square', 'fa');
INSERT INTO `icons` VALUES (300, 'share-square', 'fa');
INSERT INTO `icons` VALUES (301, 'compass', 'fa');
INSERT INTO `icons` VALUES (302, 'caret-square-o-down', 'fa');
INSERT INTO `icons` VALUES (303, 'caret-square-o-up', 'fa');
INSERT INTO `icons` VALUES (304, 'caret-square-o-right', 'fa');
INSERT INTO `icons` VALUES (305, 'eur', 'fa');
INSERT INTO `icons` VALUES (306, 'gbp', 'fa');
INSERT INTO `icons` VALUES (307, 'usd', 'fa');
INSERT INTO `icons` VALUES (308, 'inr', 'fa');
INSERT INTO `icons` VALUES (309, 'jpy', 'fa');
INSERT INTO `icons` VALUES (310, 'rub', 'fa');
INSERT INTO `icons` VALUES (311, 'krw', 'fa');
INSERT INTO `icons` VALUES (312, 'btc', 'fa');
INSERT INTO `icons` VALUES (313, 'file', 'fa');
INSERT INTO `icons` VALUES (314, 'file-text', 'fa');
INSERT INTO `icons` VALUES (315, 'sort-alpha-asc', 'fa');
INSERT INTO `icons` VALUES (316, 'sort-alpha-desc', 'fa');
INSERT INTO `icons` VALUES (317, 'sort-amount-asc', 'fa');
INSERT INTO `icons` VALUES (318, 'sort-amount-desc', 'fa');
INSERT INTO `icons` VALUES (319, 'sort-numeric-asc', 'fa');
INSERT INTO `icons` VALUES (320, 'sort-numeric-desc', 'fa');
INSERT INTO `icons` VALUES (321, 'thumbs-up', 'fa');
INSERT INTO `icons` VALUES (322, 'thumbs-down', 'fa');
INSERT INTO `icons` VALUES (323, 'youtube-square', 'fa');
INSERT INTO `icons` VALUES (324, 'youtube', 'fa');
INSERT INTO `icons` VALUES (325, 'xing', 'fa');
INSERT INTO `icons` VALUES (326, 'xing-square', 'fa');
INSERT INTO `icons` VALUES (327, 'youtube-play', 'fa');
INSERT INTO `icons` VALUES (328, 'dropbox', 'fa');
INSERT INTO `icons` VALUES (329, 'stack-overflow', 'fa');
INSERT INTO `icons` VALUES (330, 'instagram', 'fa');
INSERT INTO `icons` VALUES (331, 'flickr', 'fa');
INSERT INTO `icons` VALUES (332, 'adn', 'fa');
INSERT INTO `icons` VALUES (333, 'bitbucket', 'fa');
INSERT INTO `icons` VALUES (334, 'bitbucket-square', 'fa');
INSERT INTO `icons` VALUES (335, 'tumblr', 'fa');
INSERT INTO `icons` VALUES (336, 'tumblr-square', 'fa');
INSERT INTO `icons` VALUES (337, 'long-arrow-down', 'fa');
INSERT INTO `icons` VALUES (338, 'long-arrow-up', 'fa');
INSERT INTO `icons` VALUES (339, 'long-arrow-left', 'fa');
INSERT INTO `icons` VALUES (340, 'long-arrow-right', 'fa');
INSERT INTO `icons` VALUES (341, 'apple', 'fa');
INSERT INTO `icons` VALUES (342, 'windows', 'fa');
INSERT INTO `icons` VALUES (343, 'android', 'fa');
INSERT INTO `icons` VALUES (344, 'linux', 'fa');
INSERT INTO `icons` VALUES (345, 'dribbble', 'fa');
INSERT INTO `icons` VALUES (346, 'skype', 'fa');
INSERT INTO `icons` VALUES (347, 'foursquare', 'fa');
INSERT INTO `icons` VALUES (348, 'trello', 'fa');
INSERT INTO `icons` VALUES (349, 'female', 'fa');
INSERT INTO `icons` VALUES (350, 'male', 'fa');
INSERT INTO `icons` VALUES (351, 'gratipay', 'fa');
INSERT INTO `icons` VALUES (352, 'sun-o', 'fa');
INSERT INTO `icons` VALUES (353, 'moon-o', 'fa');
INSERT INTO `icons` VALUES (354, 'archive', 'fa');
INSERT INTO `icons` VALUES (355, 'bug', 'fa');
INSERT INTO `icons` VALUES (356, 'vk', 'fa');
INSERT INTO `icons` VALUES (357, 'weibo', 'fa');
INSERT INTO `icons` VALUES (358, 'renren', 'fa');
INSERT INTO `icons` VALUES (359, 'pagelines', 'fa');
INSERT INTO `icons` VALUES (360, 'stack-exchange', 'fa');
INSERT INTO `icons` VALUES (361, 'arrow-circle-o-right', 'fa');
INSERT INTO `icons` VALUES (362, 'arrow-circle-o-left', 'fa');
INSERT INTO `icons` VALUES (363, 'caret-square-o-left', 'fa');
INSERT INTO `icons` VALUES (364, 'dot-circle-o', 'fa');
INSERT INTO `icons` VALUES (365, 'wheelchair', 'fa');
INSERT INTO `icons` VALUES (366, 'vimeo-square', 'fa');
INSERT INTO `icons` VALUES (367, 'try', 'fa');
INSERT INTO `icons` VALUES (368, 'plus-square-o', 'fa');
INSERT INTO `icons` VALUES (369, 'space-shuttle', 'fa');
INSERT INTO `icons` VALUES (370, 'slack', 'fa');
INSERT INTO `icons` VALUES (371, 'envelope-square', 'fa');
INSERT INTO `icons` VALUES (372, 'wordpress', 'fa');
INSERT INTO `icons` VALUES (373, 'openid', 'fa');
INSERT INTO `icons` VALUES (374, 'university', 'fa');
INSERT INTO `icons` VALUES (375, 'graduation-cap', 'fa');
INSERT INTO `icons` VALUES (376, 'yahoo', 'fa');
INSERT INTO `icons` VALUES (377, 'google', 'fa');
INSERT INTO `icons` VALUES (378, 'reddit', 'fa');
INSERT INTO `icons` VALUES (379, 'reddit-square', 'fa');
INSERT INTO `icons` VALUES (380, 'stumbleupon-circle', 'fa');
INSERT INTO `icons` VALUES (381, 'stumbleupon', 'fa');
INSERT INTO `icons` VALUES (382, 'delicious', 'fa');
INSERT INTO `icons` VALUES (383, 'digg', 'fa');
INSERT INTO `icons` VALUES (384, 'pied-piper-pp', 'fa');
INSERT INTO `icons` VALUES (385, 'pied-piper-alt', 'fa');
INSERT INTO `icons` VALUES (386, 'drupal', 'fa');
INSERT INTO `icons` VALUES (387, 'joomla', 'fa');
INSERT INTO `icons` VALUES (388, 'language', 'fa');
INSERT INTO `icons` VALUES (389, 'fax', 'fa');
INSERT INTO `icons` VALUES (390, 'building', 'fa');
INSERT INTO `icons` VALUES (391, 'child', 'fa');
INSERT INTO `icons` VALUES (392, 'paw', 'fa');
INSERT INTO `icons` VALUES (393, 'spoon', 'fa');
INSERT INTO `icons` VALUES (394, 'cube', 'fa');
INSERT INTO `icons` VALUES (395, 'cubes', 'fa');
INSERT INTO `icons` VALUES (396, 'behance', 'fa');
INSERT INTO `icons` VALUES (397, 'behance-square', 'fa');
INSERT INTO `icons` VALUES (398, 'steam', 'fa');
INSERT INTO `icons` VALUES (399, 'steam-square', 'fa');
INSERT INTO `icons` VALUES (400, 'recycle', 'fa');
INSERT INTO `icons` VALUES (401, 'car', 'fa');
INSERT INTO `icons` VALUES (402, 'taxi', 'fa');
INSERT INTO `icons` VALUES (403, 'tree', 'fa');
INSERT INTO `icons` VALUES (404, 'spotify', 'fa');
INSERT INTO `icons` VALUES (405, 'deviantart', 'fa');
INSERT INTO `icons` VALUES (406, 'soundcloud', 'fa');
INSERT INTO `icons` VALUES (407, 'database', 'fa');
INSERT INTO `icons` VALUES (408, 'file-pdf-o', 'fa');
INSERT INTO `icons` VALUES (409, 'file-word-o', 'fa');
INSERT INTO `icons` VALUES (410, 'file-excel-o', 'fa');
INSERT INTO `icons` VALUES (411, 'file-powerpoint-o', 'fa');
INSERT INTO `icons` VALUES (412, 'file-image-o', 'fa');
INSERT INTO `icons` VALUES (413, 'file-archive-o', 'fa');
INSERT INTO `icons` VALUES (414, 'file-audio-o', 'fa');
INSERT INTO `icons` VALUES (415, 'file-video-o', 'fa');
INSERT INTO `icons` VALUES (416, 'file-code-o', 'fa');
INSERT INTO `icons` VALUES (417, 'vine', 'fa');
INSERT INTO `icons` VALUES (418, 'codepen', 'fa');
INSERT INTO `icons` VALUES (419, 'jsfiddle', 'fa');
INSERT INTO `icons` VALUES (420, 'life-ring', 'fa');
INSERT INTO `icons` VALUES (421, 'circle-o-notch', 'fa');
INSERT INTO `icons` VALUES (422, 'rebel', 'fa');
INSERT INTO `icons` VALUES (423, 'empire', 'fa');
INSERT INTO `icons` VALUES (424, 'git-square', 'fa');
INSERT INTO `icons` VALUES (425, 'git', 'fa');
INSERT INTO `icons` VALUES (426, 'hacker-news', 'fa');
INSERT INTO `icons` VALUES (427, 'tencent-weibo', 'fa');
INSERT INTO `icons` VALUES (428, 'qq', 'fa');
INSERT INTO `icons` VALUES (429, 'weixin', 'fa');
INSERT INTO `icons` VALUES (430, 'paper-plane', 'fa');
INSERT INTO `icons` VALUES (431, 'paper-plane-o', 'fa');
INSERT INTO `icons` VALUES (432, 'history', 'fa');
INSERT INTO `icons` VALUES (433, 'circle-thin', 'fa');
INSERT INTO `icons` VALUES (434, 'header', 'fa');
INSERT INTO `icons` VALUES (435, 'paragraph', 'fa');
INSERT INTO `icons` VALUES (436, 'sliders', 'fa');
INSERT INTO `icons` VALUES (437, 'share-alt', 'fa');
INSERT INTO `icons` VALUES (438, 'share-alt-square', 'fa');
INSERT INTO `icons` VALUES (439, 'bomb', 'fa');
INSERT INTO `icons` VALUES (440, 'futbol-o', 'fa');
INSERT INTO `icons` VALUES (441, 'tty', 'fa');
INSERT INTO `icons` VALUES (442, 'binoculars', 'fa');
INSERT INTO `icons` VALUES (443, 'plug', 'fa');
INSERT INTO `icons` VALUES (444, 'slideshare', 'fa');
INSERT INTO `icons` VALUES (445, 'twitch', 'fa');
INSERT INTO `icons` VALUES (446, 'yelp', 'fa');
INSERT INTO `icons` VALUES (447, 'newspaper-o', 'fa');
INSERT INTO `icons` VALUES (448, 'wifi', 'fa');
INSERT INTO `icons` VALUES (449, 'calculator', 'fa');
INSERT INTO `icons` VALUES (450, 'paypal', 'fa');
INSERT INTO `icons` VALUES (451, 'google-wallet', 'fa');
INSERT INTO `icons` VALUES (452, 'cc-visa', 'fa');
INSERT INTO `icons` VALUES (453, 'cc-mastercard', 'fa');
INSERT INTO `icons` VALUES (454, 'cc-discover', 'fa');
INSERT INTO `icons` VALUES (455, 'cc-amex', 'fa');
INSERT INTO `icons` VALUES (456, 'cc-paypal', 'fa');
INSERT INTO `icons` VALUES (457, 'cc-stripe', 'fa');
INSERT INTO `icons` VALUES (458, 'bell-slash', 'fa');
INSERT INTO `icons` VALUES (459, 'bell-slash-o', 'fa');
INSERT INTO `icons` VALUES (460, 'trash', 'fa');
INSERT INTO `icons` VALUES (461, 'copyright', 'fa');
INSERT INTO `icons` VALUES (462, 'at', 'fa');
INSERT INTO `icons` VALUES (463, 'eyedropper', 'fa');
INSERT INTO `icons` VALUES (464, 'paint-brush', 'fa');
INSERT INTO `icons` VALUES (465, 'birthday-cake', 'fa');
INSERT INTO `icons` VALUES (466, 'area-chart', 'fa');
INSERT INTO `icons` VALUES (467, 'pie-chart', 'fa');
INSERT INTO `icons` VALUES (468, 'line-chart', 'fa');
INSERT INTO `icons` VALUES (469, 'lastfm', 'fa');
INSERT INTO `icons` VALUES (470, 'lastfm-square', 'fa');
INSERT INTO `icons` VALUES (471, 'toggle-off', 'fa');
INSERT INTO `icons` VALUES (472, 'toggle-on', 'fa');
INSERT INTO `icons` VALUES (473, 'bicycle', 'fa');
INSERT INTO `icons` VALUES (474, 'bus', 'fa');
INSERT INTO `icons` VALUES (475, 'ioxhost', 'fa');
INSERT INTO `icons` VALUES (476, 'angellist', 'fa');
INSERT INTO `icons` VALUES (477, 'cc', 'fa');
INSERT INTO `icons` VALUES (478, 'ils', 'fa');
INSERT INTO `icons` VALUES (479, 'meanpath', 'fa');
INSERT INTO `icons` VALUES (480, 'buysellads', 'fa');
INSERT INTO `icons` VALUES (481, 'connectdevelop', 'fa');
INSERT INTO `icons` VALUES (482, 'dashcube', 'fa');
INSERT INTO `icons` VALUES (483, 'forumbee', 'fa');
INSERT INTO `icons` VALUES (484, 'leanpub', 'fa');
INSERT INTO `icons` VALUES (485, 'sellsy', 'fa');
INSERT INTO `icons` VALUES (486, 'shirtsinbulk', 'fa');
INSERT INTO `icons` VALUES (487, 'simplybuilt', 'fa');
INSERT INTO `icons` VALUES (488, 'skyatlas', 'fa');
INSERT INTO `icons` VALUES (489, 'cart-plus', 'fa');
INSERT INTO `icons` VALUES (490, 'cart-arrow-down', 'fa');
INSERT INTO `icons` VALUES (491, 'diamond', 'fa');
INSERT INTO `icons` VALUES (492, 'ship', 'fa');
INSERT INTO `icons` VALUES (493, 'user-secret', 'fa');
INSERT INTO `icons` VALUES (494, 'motorcycle', 'fa');
INSERT INTO `icons` VALUES (495, 'street-view', 'fa');
INSERT INTO `icons` VALUES (496, 'heartbeat', 'fa');
INSERT INTO `icons` VALUES (497, 'venus', 'fa');
INSERT INTO `icons` VALUES (498, 'mars', 'fa');
INSERT INTO `icons` VALUES (499, 'mercury', 'fa');
INSERT INTO `icons` VALUES (500, 'transgender', 'fa');
INSERT INTO `icons` VALUES (501, 'transgender-alt', 'fa');
INSERT INTO `icons` VALUES (502, 'venus-double', 'fa');
INSERT INTO `icons` VALUES (503, 'mars-double', 'fa');
INSERT INTO `icons` VALUES (504, 'venus-mars', 'fa');
INSERT INTO `icons` VALUES (505, 'mars-stroke', 'fa');
INSERT INTO `icons` VALUES (506, 'mars-stroke-v', 'fa');
INSERT INTO `icons` VALUES (507, 'mars-stroke-h', 'fa');
INSERT INTO `icons` VALUES (508, 'neuter', 'fa');
INSERT INTO `icons` VALUES (509, 'genderless', 'fa');
INSERT INTO `icons` VALUES (510, 'facebook-official', 'fa');
INSERT INTO `icons` VALUES (511, 'pinterest-p', 'fa');
INSERT INTO `icons` VALUES (512, 'whatsapp', 'fa');
INSERT INTO `icons` VALUES (513, 'server', 'fa');
INSERT INTO `icons` VALUES (514, 'user-plus', 'fa');
INSERT INTO `icons` VALUES (515, 'user-times', 'fa');
INSERT INTO `icons` VALUES (516, 'bed', 'fa');
INSERT INTO `icons` VALUES (517, 'viacoin', 'fa');
INSERT INTO `icons` VALUES (518, 'train', 'fa');
INSERT INTO `icons` VALUES (519, 'subway', 'fa');
INSERT INTO `icons` VALUES (520, 'medium', 'fa');
INSERT INTO `icons` VALUES (521, 'y-combinator', 'fa');
INSERT INTO `icons` VALUES (522, 'optin-monster', 'fa');
INSERT INTO `icons` VALUES (523, 'opencart', 'fa');
INSERT INTO `icons` VALUES (524, 'expeditedssl', 'fa');
INSERT INTO `icons` VALUES (525, 'battery-full', 'fa');
INSERT INTO `icons` VALUES (526, 'battery-three-quarters', 'fa');
INSERT INTO `icons` VALUES (527, 'battery-half', 'fa');
INSERT INTO `icons` VALUES (528, 'battery-quarter', 'fa');
INSERT INTO `icons` VALUES (529, 'battery-empty', 'fa');
INSERT INTO `icons` VALUES (530, 'mouse-pointer', 'fa');
INSERT INTO `icons` VALUES (531, 'i-cursor', 'fa');
INSERT INTO `icons` VALUES (532, 'object-group', 'fa');
INSERT INTO `icons` VALUES (533, 'object-ungroup', 'fa');
INSERT INTO `icons` VALUES (534, 'sticky-note', 'fa');
INSERT INTO `icons` VALUES (535, 'sticky-note-o', 'fa');
INSERT INTO `icons` VALUES (536, 'cc-jcb', 'fa');
INSERT INTO `icons` VALUES (537, 'cc-diners-club', 'fa');
INSERT INTO `icons` VALUES (538, 'clone', 'fa');
INSERT INTO `icons` VALUES (539, 'balance-scale', 'fa');
INSERT INTO `icons` VALUES (540, 'hourglass-o', 'fa');
INSERT INTO `icons` VALUES (541, 'hourglass-start', 'fa');
INSERT INTO `icons` VALUES (542, 'hourglass-half', 'fa');
INSERT INTO `icons` VALUES (543, 'hourglass-end', 'fa');
INSERT INTO `icons` VALUES (544, 'hourglass', 'fa');
INSERT INTO `icons` VALUES (545, 'hand-rock-o', 'fa');
INSERT INTO `icons` VALUES (546, 'hand-paper-o', 'fa');
INSERT INTO `icons` VALUES (547, 'hand-scissors-o', 'fa');
INSERT INTO `icons` VALUES (548, 'hand-lizard-o', 'fa');
INSERT INTO `icons` VALUES (549, 'hand-spock-o', 'fa');
INSERT INTO `icons` VALUES (550, 'hand-pointer-o', 'fa');
INSERT INTO `icons` VALUES (551, 'hand-peace-o', 'fa');
INSERT INTO `icons` VALUES (552, 'trademark', 'fa');
INSERT INTO `icons` VALUES (553, 'registered', 'fa');
INSERT INTO `icons` VALUES (554, 'creative-commons', 'fa');
INSERT INTO `icons` VALUES (555, 'gg', 'fa');
INSERT INTO `icons` VALUES (556, 'gg-circle', 'fa');
INSERT INTO `icons` VALUES (557, 'tripadvisor', 'fa');
INSERT INTO `icons` VALUES (558, 'odnoklassniki', 'fa');
INSERT INTO `icons` VALUES (559, 'odnoklassniki-square', 'fa');
INSERT INTO `icons` VALUES (560, 'get-pocket', 'fa');
INSERT INTO `icons` VALUES (561, 'wikipedia-w', 'fa');
INSERT INTO `icons` VALUES (562, 'safari', 'fa');
INSERT INTO `icons` VALUES (563, 'chrome', 'fa');
INSERT INTO `icons` VALUES (564, 'firefox', 'fa');
INSERT INTO `icons` VALUES (565, 'opera', 'fa');
INSERT INTO `icons` VALUES (566, 'internet-explorer', 'fa');
INSERT INTO `icons` VALUES (567, 'television', 'fa');
INSERT INTO `icons` VALUES (568, 'contao', 'fa');
INSERT INTO `icons` VALUES (569, '500px', 'fa');
INSERT INTO `icons` VALUES (570, 'amazon', 'fa');
INSERT INTO `icons` VALUES (571, 'calendar-plus-o', 'fa');
INSERT INTO `icons` VALUES (572, 'calendar-minus-o', 'fa');
INSERT INTO `icons` VALUES (573, 'calendar-times-o', 'fa');
INSERT INTO `icons` VALUES (574, 'calendar-check-o', 'fa');
INSERT INTO `icons` VALUES (575, 'industry', 'fa');
INSERT INTO `icons` VALUES (576, 'map-pin', 'fa');
INSERT INTO `icons` VALUES (577, 'map-signs', 'fa');
INSERT INTO `icons` VALUES (578, 'map-o', 'fa');
INSERT INTO `icons` VALUES (579, 'map', 'fa');
INSERT INTO `icons` VALUES (580, 'commenting', 'fa');
INSERT INTO `icons` VALUES (581, 'commenting-o', 'fa');
INSERT INTO `icons` VALUES (582, 'houzz', 'fa');
INSERT INTO `icons` VALUES (583, 'vimeo', 'fa');
INSERT INTO `icons` VALUES (584, 'black-tie', 'fa');
INSERT INTO `icons` VALUES (585, 'fonticons', 'fa');
INSERT INTO `icons` VALUES (586, 'reddit-alien', 'fa');
INSERT INTO `icons` VALUES (587, 'edge', 'fa');
INSERT INTO `icons` VALUES (588, 'credit-card-alt', 'fa');
INSERT INTO `icons` VALUES (589, 'codiepie', 'fa');
INSERT INTO `icons` VALUES (590, 'modx', 'fa');
INSERT INTO `icons` VALUES (591, 'fort-awesome', 'fa');
INSERT INTO `icons` VALUES (592, 'usb', 'fa');
INSERT INTO `icons` VALUES (593, 'product-hunt', 'fa');
INSERT INTO `icons` VALUES (594, 'mixcloud', 'fa');
INSERT INTO `icons` VALUES (595, 'scribd', 'fa');
INSERT INTO `icons` VALUES (596, 'pause-circle', 'fa');
INSERT INTO `icons` VALUES (597, 'pause-circle-o', 'fa');
INSERT INTO `icons` VALUES (598, 'stop-circle', 'fa');
INSERT INTO `icons` VALUES (599, 'stop-circle-o', 'fa');
INSERT INTO `icons` VALUES (600, 'shopping-bag', 'fa');
INSERT INTO `icons` VALUES (601, 'shopping-basket', 'fa');
INSERT INTO `icons` VALUES (602, 'hashtag', 'fa');
INSERT INTO `icons` VALUES (603, 'bluetooth', 'fa');
INSERT INTO `icons` VALUES (604, 'bluetooth-b', 'fa');
INSERT INTO `icons` VALUES (605, 'percent', 'fa');
INSERT INTO `icons` VALUES (606, 'gitlab', 'fa');
INSERT INTO `icons` VALUES (607, 'wpbeginner', 'fa');
INSERT INTO `icons` VALUES (608, 'wpforms', 'fa');
INSERT INTO `icons` VALUES (609, 'envira', 'fa');
INSERT INTO `icons` VALUES (610, 'universal-access', 'fa');
INSERT INTO `icons` VALUES (611, 'wheelchair-alt', 'fa');
INSERT INTO `icons` VALUES (612, 'question-circle-o', 'fa');
INSERT INTO `icons` VALUES (613, 'blind', 'fa');
INSERT INTO `icons` VALUES (614, 'audio-description', 'fa');
INSERT INTO `icons` VALUES (615, 'volume-control-phone', 'fa');
INSERT INTO `icons` VALUES (616, 'braille', 'fa');
INSERT INTO `icons` VALUES (617, 'assistive-listening-systems', 'fa');
INSERT INTO `icons` VALUES (618, 'american-sign-language-interpreting', 'fa');
INSERT INTO `icons` VALUES (619, 'deaf', 'fa');
INSERT INTO `icons` VALUES (620, 'glide', 'fa');
INSERT INTO `icons` VALUES (621, 'glide-g', 'fa');
INSERT INTO `icons` VALUES (622, 'sign-language', 'fa');
INSERT INTO `icons` VALUES (623, 'low-vision', 'fa');
INSERT INTO `icons` VALUES (624, 'viadeo', 'fa');
INSERT INTO `icons` VALUES (625, 'viadeo-square', 'fa');
INSERT INTO `icons` VALUES (626, 'snapchat', 'fa');
INSERT INTO `icons` VALUES (627, 'snapchat-ghost', 'fa');
INSERT INTO `icons` VALUES (628, 'snapchat-square', 'fa');
INSERT INTO `icons` VALUES (629, 'pied-piper', 'fa');
INSERT INTO `icons` VALUES (630, 'first-order', 'fa');
INSERT INTO `icons` VALUES (631, 'yoast', 'fa');
INSERT INTO `icons` VALUES (632, 'themeisle', 'fa');
INSERT INTO `icons` VALUES (633, 'google-plus-official', 'fa');
INSERT INTO `icons` VALUES (634, 'font-awesome', 'fa');
INSERT INTO `icons` VALUES (635, 'handshake-o', 'fa');
INSERT INTO `icons` VALUES (636, 'envelope-open', 'fa');
INSERT INTO `icons` VALUES (637, 'envelope-open-o', 'fa');
INSERT INTO `icons` VALUES (638, 'linode', 'fa');
INSERT INTO `icons` VALUES (639, 'address-book', 'fa');
INSERT INTO `icons` VALUES (640, 'address-book-o', 'fa');
INSERT INTO `icons` VALUES (641, 'address-card', 'fa');
INSERT INTO `icons` VALUES (642, 'address-card-o', 'fa');
INSERT INTO `icons` VALUES (643, 'user-circle', 'fa');
INSERT INTO `icons` VALUES (644, 'user-circle-o', 'fa');
INSERT INTO `icons` VALUES (645, 'user-o', 'fa');
INSERT INTO `icons` VALUES (646, 'id-badge', 'fa');
INSERT INTO `icons` VALUES (647, 'id-card', 'fa');
INSERT INTO `icons` VALUES (648, 'id-card-o', 'fa');
INSERT INTO `icons` VALUES (649, 'quora', 'fa');
INSERT INTO `icons` VALUES (650, 'free-code-camp', 'fa');
INSERT INTO `icons` VALUES (651, 'telegram', 'fa');
INSERT INTO `icons` VALUES (652, 'thermometer-full', 'fa');
INSERT INTO `icons` VALUES (653, 'thermometer-three-quarters', 'fa');
INSERT INTO `icons` VALUES (654, 'thermometer-half', 'fa');
INSERT INTO `icons` VALUES (655, 'thermometer-quarter', 'fa');
INSERT INTO `icons` VALUES (656, 'thermometer-empty', 'fa');
INSERT INTO `icons` VALUES (657, 'shower', 'fa');
INSERT INTO `icons` VALUES (658, 'bath', 'fa');
INSERT INTO `icons` VALUES (659, 'podcast', 'fa');
INSERT INTO `icons` VALUES (660, 'window-maximize', 'fa');
INSERT INTO `icons` VALUES (661, 'window-minimize', 'fa');
INSERT INTO `icons` VALUES (662, 'window-restore', 'fa');
INSERT INTO `icons` VALUES (663, 'window-close', 'fa');
INSERT INTO `icons` VALUES (664, 'window-close-o', 'fa');
INSERT INTO `icons` VALUES (665, 'bandcamp', 'fa');
INSERT INTO `icons` VALUES (666, 'grav', 'fa');
INSERT INTO `icons` VALUES (667, 'etsy', 'fa');
INSERT INTO `icons` VALUES (668, 'imdb', 'fa');
INSERT INTO `icons` VALUES (669, 'ravelry', 'fa');
INSERT INTO `icons` VALUES (670, 'eercast', 'fa');
INSERT INTO `icons` VALUES (671, 'microchip', 'fa');
INSERT INTO `icons` VALUES (672, 'snowflake-o', 'fa');
INSERT INTO `icons` VALUES (673, 'superpowers', 'fa');
INSERT INTO `icons` VALUES (674, 'wpexplorer', 'fa');
INSERT INTO `icons` VALUES (675, 'meetup', 'fa');

-- ----------------------------
-- Table structure for key_storage_item
-- ----------------------------
DROP TABLE IF EXISTS `key_storage_item`;
CREATE TABLE `key_storage_item`  (
  `key` varchar(128) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `comment` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL,
  `updated_at` int(11) NULL DEFAULT NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`key`) USING BTREE,
  UNIQUE INDEX `idx_key_storage_item_key`(`key`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of key_storage_item
-- ----------------------------
INSERT INTO `key_storage_item` VALUES ('app-name', 'ระบบคิวโรงพยาบาลบ้านบึง', '', 1523349883, 1518009214);
INSERT INTO `key_storage_item` VALUES ('dynamic-limit', '20', '', 1519362612, 1519362612);
INSERT INTO `key_storage_item` VALUES ('frontend.body.class', 'fixed-sidebar fixed-navbar', '', 1518010225, NULL);
INSERT INTO `key_storage_item` VALUES ('frontend.navbar', 'navbar-fixed-top', 'fix navbar header', 1515767197, NULL);
INSERT INTO `key_storage_item` VALUES ('frontend.page-breadcrumbs', '0', 'breadcrumbs-fixed', 1515767838, NULL);
INSERT INTO `key_storage_item` VALUES ('frontend.page-header', '0', 'page-header-fixed', 1515767908, NULL);
INSERT INTO `key_storage_item` VALUES ('frontend.page-sidebar', 'sidebar-fixed menu-compact', 'sidebar-fixed , menu-compact', 1516690802, NULL);

-- ----------------------------
-- Table structure for menu
-- ----------------------------
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `menu_category_id` int(11) NOT NULL,
  `parent_id` int(11) NULL DEFAULT NULL,
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `router` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `parameter` varchar(250) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `icon` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` enum('2','1','0') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '0',
  `item_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `target` varchar(30) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `protocol` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `home` enum('1','0') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '0',
  `sort` int(3) NULL DEFAULT NULL,
  `language` varchar(7) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT '*',
  `params` mediumtext CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `assoc` varchar(12) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `name` varchar(128) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `parent` int(11) NULL DEFAULT NULL,
  `route` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `order` int(11) NULL DEFAULT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `auth_items` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_menu_category_id_5207_00`(`menu_category_id`) USING BTREE,
  INDEX `idx_parent_id_5207_01`(`parent_id`) USING BTREE,
  CONSTRAINT `menu_ibfk_1` FOREIGN KEY (`menu_category_id`) REFERENCES `menu_category` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 17 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of menu
-- ----------------------------
INSERT INTO `menu` VALUES (1, 1, NULL, 'หน้าหลัก', '/site/index', '', 'home', '1', NULL, '', '', '1', 1, '*', '', NULL, 1523348061, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (2, 1, NULL, 'Gii', '/gii', '', 'newspaper-o', '1', NULL, '', '', '1', 2, '*', '', NULL, 1523348097, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (3, 1, NULL, 'ข้อมูลส่วนตัว', '/user/settings/profile', '', 'user', '1', NULL, '', '', '1', 16, '*', '', NULL, 1523348152, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (4, 1, NULL, 'ตั้งค่า', '#', '', 'cogs', '1', NULL, '', '', '1', 3, '*', NULL, NULL, 1524041211, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (5, 1, 4, 'ผู้ใช้งาน', '/user/admin/index', '', 'users', '1', NULL, '', '', '1', 5, '*', '', NULL, 1523348294, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (6, 1, 4, 'สิทธิ์การใช้งาน', '/admin-manager/permission', '', 'unlock-alt', '1', NULL, '', '', '1', 6, '*', '', NULL, 1523348401, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (7, 1, 4, 'AppConfig', '/key-storage/index', '', 'circle-thin', '1', NULL, '', '', '1', 7, '*', NULL, NULL, 1523348494, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (8, 1, NULL, 'โปรแกรมเสียง', '/app/calling/play-sound', '', 'bullhorn', '1', NULL, '', '', '1', 13, '*', NULL, NULL, 1524454480, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (9, 1, NULL, 'จอแสดงผล', '/app/display/display-list', '', 'desktop', '1', NULL, '', '', '1', 14, '*', NULL, NULL, 1524454302, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (10, 1, 4, 'เมนู', '/menu/default/menu-order', '', 'list', '1', NULL, '', '', '1', 8, '*', '', NULL, 1523349768, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (11, 1, 4, 'ระบบคิว', '/app/settings/index', '', 'cogs', '1', NULL, '', '', '1', 4, '*', '', NULL, 1523434456, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (12, 1, NULL, 'ออกบัตรคิว', '/app/kiosk/index', '', 'credit-card', '1', NULL, '', '', '1', 15, '*', '', NULL, 1524041393, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (13, 1, 15, 'เวชระเบียน', '/app/calling/medical', '', 'bullhorn', '1', NULL, '', '', '1', 10, '*', NULL, NULL, 1524632198, 2, NULL, NULL, '', NULL, NULL, '[\"App\",\"เวชระเบียน\"]');
INSERT INTO `menu` VALUES (14, 1, 15, 'ห้องตรวจ', '/app/calling/examination-room', '', 'bullhorn', '1', NULL, '', '', '1', 12, '*', NULL, NULL, 1524632206, 2, NULL, NULL, '', NULL, NULL, '[\"App\",\"ห้องตรวจโรค\"]');
INSERT INTO `menu` VALUES (15, 1, NULL, 'เรียกคิว', '#', '', 'bullhorn', '1', NULL, '', '', '1', 9, '*', '', NULL, 1524632181, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');
INSERT INTO `menu` VALUES (16, 1, 15, 'คัดกรอง', '/app/calling/index', '', 'bullhorn', '1', NULL, '', '', '1', 11, '*', '', NULL, 1524632249, 2, NULL, NULL, '', NULL, NULL, '[\"App\"]');

-- ----------------------------
-- Table structure for menu_auth
-- ----------------------------
DROP TABLE IF EXISTS `menu_auth`;
CREATE TABLE `menu_auth`  (
  `menu_id` int(11) NOT NULL,
  `item_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`menu_id`) USING BTREE,
  INDEX `item_name`(`item_name`) USING BTREE,
  CONSTRAINT `menu_auth_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menu` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for menu_category
-- ----------------------------
DROP TABLE IF EXISTS `menu_category`;
CREATE TABLE `menu_category`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `discription` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `status` enum('1','0') CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_id_5487_02`(`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of menu_category
-- ----------------------------
INSERT INTO `menu_category` VALUES (1, 'app-frontend', 'เมนู frontend', '1');
INSERT INTO `menu_category` VALUES (2, 'app-backend', 'backend', '1');

-- ----------------------------
-- Table structure for migration
-- ----------------------------
DROP TABLE IF EXISTS `migration`;
CREATE TABLE `migration`  (
  `version` varchar(180) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `apply_time` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`version`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of migration
-- ----------------------------
INSERT INTO `migration` VALUES ('m000000_000000_base', 1517969408);
INSERT INTO `migration` VALUES ('m140209_132017_init', 1517969416);
INSERT INTO `migration` VALUES ('m140403_174025_create_account_table', 1517969416);
INSERT INTO `migration` VALUES ('m140504_113157_update_tables', 1517969417);
INSERT INTO `migration` VALUES ('m140504_130429_create_token_table', 1517969417);
INSERT INTO `migration` VALUES ('m140506_102106_rbac_init', 1517977513);
INSERT INTO `migration` VALUES ('m140830_171933_fix_ip_field', 1517969417);
INSERT INTO `migration` VALUES ('m140830_172703_change_account_table_name', 1517969417);
INSERT INTO `migration` VALUES ('m141222_110026_update_ip_field', 1517969417);
INSERT INTO `migration` VALUES ('m141222_135246_alter_username_length', 1517969417);
INSERT INTO `migration` VALUES ('m150614_103145_update_social_account_table', 1517969417);
INSERT INTO `migration` VALUES ('m150623_212711_fix_username_notnull', 1517969417);
INSERT INTO `migration` VALUES ('m151218_234654_add_timezone_to_profile', 1517969417);
INSERT INTO `migration` VALUES ('m160929_103127_add_last_login_at_to_user_table', 1517969417);
INSERT INTO `migration` VALUES ('m170907_052038_rbac_add_index_on_auth_assignment_user_id', 1517977513);

-- ----------------------------
-- Table structure for profile
-- ----------------------------
DROP TABLE IF EXISTS `profile`;
CREATE TABLE `profile`  (
  `user_id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `public_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `gravatar_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `gravatar_id` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `location` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `website` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `bio` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `timezone` varchar(40) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `avatar_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `avatar_base_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`) USING BTREE,
  CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of profile
-- ----------------------------
INSERT INTO `profile` VALUES (2, 'Admin Banbung', '', '', 'd41d8cd98f00b204e9800998ecf8427e', '', '', '', 'Asia/Bangkok', NULL, NULL);

-- ----------------------------
-- Table structure for social_account
-- ----------------------------
DROP TABLE IF EXISTS `social_account`;
CREATE TABLE `social_account`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NULL DEFAULT NULL,
  `provider` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `client_id` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `data` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` int(11) NULL DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `account_unique`(`provider`, `client_id`) USING BTREE,
  UNIQUE INDEX `account_unique_code`(`code`) USING BTREE,
  INDEX `fk_user_account`(`user_id`) USING BTREE,
  CONSTRAINT `social_account_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_caller
-- ----------------------------
DROP TABLE IF EXISTS `tb_caller`;
CREATE TABLE `tb_caller`  (
  `caller_ids` int(11) NOT NULL AUTO_INCREMENT COMMENT 'running',
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
  PRIMARY KEY (`caller_ids`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_counterservice
-- ----------------------------
DROP TABLE IF EXISTS `tb_counterservice`;
CREATE TABLE `tb_counterservice`  (
  `counterserviceid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'เลขที่ช่องบริการ',
  `counterservice_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อช่องบริการ',
  `counterservice_callnumber` int(2) NULL DEFAULT NULL COMMENT 'หมายเลข',
  `counterservice_type` int(11) NULL DEFAULT NULL COMMENT 'ประเภท',
  `servicegroupid` int(11) NULL DEFAULT NULL COMMENT 'กลุ่มบริการ',
  `userid` int(20) NULL DEFAULT NULL COMMENT 'ผู้ให้บริการ (1,2,3 หรือ all)',
  `serviceid` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'เรียก serviceid',
  `sound_stationid` int(11) NULL DEFAULT NULL COMMENT 'เครื่องเล่นเสียงที่',
  `sound_id` int(11) NULL DEFAULT NULL COMMENT 'ไฟล์เสียง',
  `counterservice_status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`counterserviceid`) USING BTREE,
  INDEX `counterservice_type`(`counterservice_type`) USING BTREE,
  CONSTRAINT `tb_counterservice_ibfk_1` FOREIGN KEY (`counterservice_type`) REFERENCES `tb_counterservice_type` (`counterservice_typeid`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_counterservice
-- ----------------------------
INSERT INTO `tb_counterservice` VALUES (1, 'ช่องบริการ 1', 1, 1, 1, NULL, '1', 1, 3, '2');
INSERT INTO `tb_counterservice` VALUES (2, 'ช่องบริการ 2', 2, 1, 1, NULL, '1', 1, 15, '2');
INSERT INTO `tb_counterservice` VALUES (3, 'ช่องบริการ 3', 3, 1, 1, NULL, '1', 1, 25, '2');
INSERT INTO `tb_counterservice` VALUES (4, 'ช่องบริการ 4', 4, 1, 1, NULL, '1', 1, 36, '2');
INSERT INTO `tb_counterservice` VALUES (5, 'ช่องบริการ 5', 5, 1, 1, NULL, '1', 1, 38, '2');
INSERT INTO `tb_counterservice` VALUES (6, 'ช่องบริการ 6', 6, 1, 1, NULL, '1', 1, 39, '2');
INSERT INTO `tb_counterservice` VALUES (7, 'ช่องบริการ 7', 7, 1, 1, NULL, '1', 1, 40, '2');
INSERT INTO `tb_counterservice` VALUES (8, 'ช่องบริการ 8', 8, 1, 1, NULL, '1', 1, 41, '2');
INSERT INTO `tb_counterservice` VALUES (9, 'ช่องบริการ 9', 9, 1, 1, NULL, '1', 1, 42, '2');
INSERT INTO `tb_counterservice` VALUES (10, 'ช่องบริการ 10', 10, 1, 1, NULL, '1', 1, 4, '2');
INSERT INTO `tb_counterservice` VALUES (11, 'ห้องตรวจ 1', 1, 2, 2, NULL, '2', 2, 3, '2');
INSERT INTO `tb_counterservice` VALUES (12, 'ห้องตรวจ 2', 2, 2, 2, NULL, '2', 2, 15, '2');
INSERT INTO `tb_counterservice` VALUES (13, 'ห้องตรวจ 3', 3, 2, 2, NULL, '3', 2, 25, '2');
INSERT INTO `tb_counterservice` VALUES (14, 'ห้องตรวจ 4', 4, 2, 2, 1, '3', 2, 36, '2');
INSERT INTO `tb_counterservice` VALUES (15, 'ห้องตรวจ 5', 5, 2, 2, 3, '4', 2, 38, '2');
INSERT INTO `tb_counterservice` VALUES (16, 'ห้องตรวจ 6', 6, 2, 2, 2, '4', 2, 39, '2');
INSERT INTO `tb_counterservice` VALUES (17, 'ห้องตรวจ 7', 7, 2, 2, 1, '5', 2, 40, '2');
INSERT INTO `tb_counterservice` VALUES (18, 'ห้องตรวจ 8', 8, 2, 2, 1, '5', 2, 41, '2');
INSERT INTO `tb_counterservice` VALUES (19, 'ห้องตรวจ 9', 9, 2, 2, 3, '2', 2, 42, '2');
INSERT INTO `tb_counterservice` VALUES (20, 'ห้องตรวจ 10', 10, 2, 2, 4, '2', 2, 4, '2');
INSERT INTO `tb_counterservice` VALUES (25, 'โต๊ะ 1', 1, 5, NULL, NULL, NULL, 1, 3, '2');

-- ----------------------------
-- Table structure for tb_counterservice_type
-- ----------------------------
DROP TABLE IF EXISTS `tb_counterservice_type`;
CREATE TABLE `tb_counterservice_type`  (
  `counterservice_typeid` int(11) NOT NULL AUTO_INCREMENT,
  `counterservice_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sound_id` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`counterservice_typeid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_counterservice_type
-- ----------------------------
INSERT INTO `tb_counterservice_type` VALUES (1, 'เวชระเบียน', 64);
INSERT INTO `tb_counterservice_type` VALUES (2, 'ห้องตรวจ', 66);
INSERT INTO `tb_counterservice_type` VALUES (5, 'โต๊ะคัดกรอง', 70);

-- ----------------------------
-- Table structure for tb_display_config
-- ----------------------------
DROP TABLE IF EXISTS `tb_display_config`;
CREATE TABLE `tb_display_config`  (
  `display_ids` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `counterservice_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title_left` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `title_right` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `table_title_left` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `table_title_right` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `display_limit` int(11) NOT NULL,
  `hold_label` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `header_color` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `column_color` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `background_color` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `font_color` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `border_color` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `title_color` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `display_status` int(11) NULL DEFAULT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`display_ids`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_display_config
-- ----------------------------
INSERT INTO `tb_display_config` VALUES (1, 'คัดกรอง', '25', 'เรียกคิวคัดกรอง', 'โต๊ะคัดกรอง', 'หมายเลข', 'โต๊ะ', 4, 'คิวที่เรียกไปแล้ว', '#000000', '#666666', '#204d74', '#ffffff', '#ffffff', '#62cb31', NULL);
INSERT INTO `tb_display_config` VALUES (2, 'ห้องตรวจ', '11,12,13,14', 'เรียกคิวห้องตรวจ', 'อายุรกรรม', 'หมายเลข', 'ห้อง', 4, 'คิวที่เรียกไปแล้ว', '#000000', '#666666', '#204d74', '#ffffff', '#ffffff', '#62cb31', NULL);
INSERT INTO `tb_display_config` VALUES (3, 'ห้องเจาะเลือด', '3', 'เรียกคิวห้องเจาะเลือด', 'เจาะเลือด', 'หมายเลข', 'ช่อง', 4, 'คิวที่เรียกไปแล้ว', '#000000', '#666666', '#204d74', '#ffffff', '#ffffff', '#62cb31', NULL);

-- ----------------------------
-- Table structure for tb_qtrans
-- ----------------------------
DROP TABLE IF EXISTS `tb_qtrans`;
CREATE TABLE `tb_qtrans`  (
  `ids` int(11) NOT NULL AUTO_INCREMENT,
  `q_ids` int(11) NULL DEFAULT NULL COMMENT 'คิวไอดี',
  `servicegroupid` int(11) NULL DEFAULT NULL COMMENT 'ชื่อบริการ',
  `counter_service_id` int(11) NULL DEFAULT NULL COMMENT 'ช่องบริการ/ห้อง',
  `doctor_id` int(11) NULL DEFAULT NULL COMMENT 'แพทย์',
  `checkin_date` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0) COMMENT 'เวลาลงทะเบียนแผนก',
  `checkout_date` datetime(0) NULL DEFAULT NULL COMMENT 'เวลาออกแผนก',
  `service_status_id` int(11) NULL DEFAULT NULL COMMENT 'สถานะ',
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่สร้าง',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่แก้ไข',
  `created_by` int(11) NULL DEFAULT NULL COMMENT 'ผู้บันทึก',
  `updated_by` int(11) NULL DEFAULT NULL COMMENT 'ผู้แก้ไข',
  PRIMARY KEY (`ids`) USING BTREE,
  INDEX `q_ids`(`q_ids`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for tb_quequ
-- ----------------------------
DROP TABLE IF EXISTS `tb_quequ`;
CREATE TABLE `tb_quequ`  (
  `q_ids` int(11) NOT NULL AUTO_INCREMENT COMMENT 'running',
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
  PRIMARY KEY (`q_ids`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_service
-- ----------------------------
DROP TABLE IF EXISTS `tb_service`;
CREATE TABLE `tb_service`  (
  `serviceid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'เลขที่บริการ',
  `service_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อบริการ',
  `service_groupid` int(11) NULL DEFAULT NULL COMMENT 'เลขที่กลุ่มบริการ',
  `service_route` varchar(11) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ลำดับการบริการ',
  `prn_profileid` int(11) NULL DEFAULT NULL COMMENT 'แบบการพิมพ์บัตรคิว',
  `prn_copyqty` int(2) NULL DEFAULT NULL COMMENT 'จำนวนพิมพ์ต่อครั้ง',
  `service_prefix` varchar(2) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ตัวอักษร/ตัวเลข นำหน้าคิว',
  `service_numdigit` int(2) NULL DEFAULT NULL COMMENT 'จำนวนหลักหมายเลขคิว',
  `service_status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'สถานะคิว',
  `service_md_name_id` int(2) NULL DEFAULT NULL COMMENT 'ชื่อแพทย์',
  PRIMARY KEY (`serviceid`) USING BTREE,
  INDEX `service_groupid`(`service_groupid`) USING BTREE,
  CONSTRAINT `tb_service_ibfk_1` FOREIGN KEY (`service_groupid`) REFERENCES `tb_servicegroup` (`servicegroupid`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_service
-- ----------------------------
INSERT INTO `tb_service` VALUES (1, 'เวชระเบียน', 1, '1', 6, 2, '1', 3, '2', NULL);
INSERT INTO `tb_service` VALUES (2, 'Fast Track', 2, '1', 1, 2, 'A', 3, '2', NULL);
INSERT INTO `tb_service` VALUES (3, 'อายุรกรรม', 2, '1', 1, 2, 'B', 3, '2', NULL);
INSERT INTO `tb_service` VALUES (4, 'ตรวจสุขภาพ', 2, '1', 1, 2, 'C', 3, '2', NULL);
INSERT INTO `tb_service` VALUES (5, 'ตรวจโรคทั่วไป', 2, '1', 1, 2, 'D', 3, '2', NULL);

-- ----------------------------
-- Table structure for tb_service_profile
-- ----------------------------
DROP TABLE IF EXISTS `tb_service_profile`;
CREATE TABLE `tb_service_profile`  (
  `service_profile_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `counterservice_typeid` int(11) NOT NULL,
  `service_id` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  PRIMARY KEY (`service_profile_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_service_profile
-- ----------------------------
INSERT INTO `tb_service_profile` VALUES (1, 'ห้องตรวจ', 2, '2,3,4,5');
INSERT INTO `tb_service_profile` VALUES (2, 'เวชระเบียน', 1, '1');
INSERT INTO `tb_service_profile` VALUES (3, 'คัดกรอง', 5, '2,3,4,5');

-- ----------------------------
-- Table structure for tb_service_status
-- ----------------------------
DROP TABLE IF EXISTS `tb_service_status`;
CREATE TABLE `tb_service_status`  (
  `service_status_id` int(11) NOT NULL AUTO_INCREMENT,
  `service_status_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'สถานะ',
  PRIMARY KEY (`service_status_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_service_status
-- ----------------------------
INSERT INTO `tb_service_status` VALUES (1, 'Printting');
INSERT INTO `tb_service_status` VALUES (2, 'Calling');
INSERT INTO `tb_service_status` VALUES (3, 'Hold');
INSERT INTO `tb_service_status` VALUES (4, 'End');
INSERT INTO `tb_service_status` VALUES (5, 'รอพบแพทย์');
INSERT INTO `tb_service_status` VALUES (7, 'เรียกคิวพบแพทย์');
INSERT INTO `tb_service_status` VALUES (8, 'HoldQ');
INSERT INTO `tb_service_status` VALUES (9, 'EndQ');
INSERT INTO `tb_service_status` VALUES (10, 'เสร็จสิ้น');

-- ----------------------------
-- Table structure for tb_servicegroup
-- ----------------------------
DROP TABLE IF EXISTS `tb_servicegroup`;
CREATE TABLE `tb_servicegroup`  (
  `servicegroupid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'เลขที่กลุ่มบริการ',
  `servicegroup_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อกลุ่มบริการ',
  `servicegroup_order` int(11) NULL DEFAULT NULL COMMENT 'ลำดับการแสดง',
  PRIMARY KEY (`servicegroupid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_servicegroup
-- ----------------------------
INSERT INTO `tb_servicegroup` VALUES (1, 'เวชระเบียน', 1);
INSERT INTO `tb_servicegroup` VALUES (2, 'ห้องตรวจโรค', 2);

-- ----------------------------
-- Table structure for tb_sound
-- ----------------------------
DROP TABLE IF EXISTS `tb_sound`;
CREATE TABLE `tb_sound`  (
  `sound_id` int(11) NOT NULL AUTO_INCREMENT,
  `sound_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ชื่อไฟล์',
  `sound_path_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'โฟรเดอร์ไฟล์',
  `sound_th` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'เสียงเรียก',
  `sound_type` int(11) NULL DEFAULT NULL COMMENT 'ประเภทเสียง',
  PRIMARY KEY (`sound_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 117 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_sound
-- ----------------------------
INSERT INTO `tb_sound` VALUES (1, 'BLIP.wav', 'Prompt1', 'บี๊บ', 1);
INSERT INTO `tb_sound` VALUES (2, 'Prompt1_0.wav', 'Prompt1', 'ศูนย์', 1);
INSERT INTO `tb_sound` VALUES (3, 'Prompt1_1.wav', 'Prompt1', 'หนึ่ง', 1);
INSERT INTO `tb_sound` VALUES (4, 'Prompt1_10.wav', 'Prompt1', 'สิบ', 1);
INSERT INTO `tb_sound` VALUES (5, 'Prompt1_100.wav', 'Prompt1', 'ร้อย', 1);
INSERT INTO `tb_sound` VALUES (6, 'Prompt1_1000.wav', 'Prompt1', 'พัน', 1);
INSERT INTO `tb_sound` VALUES (7, 'Prompt1_11.wav', 'Prompt1', 'สิบเอ็ด', 1);
INSERT INTO `tb_sound` VALUES (8, 'Prompt1_13.wav', 'Prompt1', 'สิบสาม', 1);
INSERT INTO `tb_sound` VALUES (9, 'Prompt1_14.wav', 'Prompt1', 'สิบสี่', 1);
INSERT INTO `tb_sound` VALUES (10, 'Prompt1_15.wav', 'Prompt1', 'สิบห้า', 1);
INSERT INTO `tb_sound` VALUES (11, 'Prompt1_16.wav', 'Prompt1', 'สิบหก', 1);
INSERT INTO `tb_sound` VALUES (12, 'Prompt1_17.wav', 'Prompt1', 'สิบเจ็ด', 1);
INSERT INTO `tb_sound` VALUES (13, 'Prompt1_18.wav', 'Prompt1', 'สิบแปด', 1);
INSERT INTO `tb_sound` VALUES (14, 'Prompt1_19.wav', 'Prompt1', 'สิบเก้า', 1);
INSERT INTO `tb_sound` VALUES (15, 'Prompt1_2.wav', 'Prompt1', 'สอง', 1);
INSERT INTO `tb_sound` VALUES (16, 'Prompt1_20.wav', 'Prompt1', 'ยี่สิบ', 1);
INSERT INTO `tb_sound` VALUES (17, 'Prompt1_21.wav', 'Prompt1', 'ยี่สิบเอ็ด', 1);
INSERT INTO `tb_sound` VALUES (18, 'Prompt1_22.wav', 'Prompt1', 'ยี่สิบสอง', 1);
INSERT INTO `tb_sound` VALUES (19, 'Prompt1_23.wav', 'Prompt1', 'ยี่สิบสาม', 1);
INSERT INTO `tb_sound` VALUES (20, 'Prompt1_24.wav', 'Prompt1', 'ยี่สิบสี่', 1);
INSERT INTO `tb_sound` VALUES (21, 'Prompt1_25.wav', 'Prompt1', 'ยี่สิบห้า', 1);
INSERT INTO `tb_sound` VALUES (22, 'Prompt1_26.wav', 'Prompt1', 'ยี่สิบหก', 1);
INSERT INTO `tb_sound` VALUES (23, 'Prompt1_27.wav', 'Prompt1', 'ยี่สิบเจ็ด', 1);
INSERT INTO `tb_sound` VALUES (24, 'Prompt1_29.wav', 'Prompt1', 'ยี่สิบเก้า', 1);
INSERT INTO `tb_sound` VALUES (25, 'Prompt1_3.wav', 'Prompt1', 'สาม', 1);
INSERT INTO `tb_sound` VALUES (26, 'Prompt1_30.wav', 'Prompt1', 'สามสิบ', 1);
INSERT INTO `tb_sound` VALUES (27, 'Prompt1_31.wav', 'Prompt1', 'สามสิบเอ็ด', 1);
INSERT INTO `tb_sound` VALUES (28, 'Prompt1_32.wav', 'Prompt1', 'สามสิบสอง', 1);
INSERT INTO `tb_sound` VALUES (29, 'Prompt1_33.wav', 'Prompt1', 'สามสิบสาม', 1);
INSERT INTO `tb_sound` VALUES (30, 'Prompt1_34.wav', 'Prompt1', 'สามสิบสี่', 1);
INSERT INTO `tb_sound` VALUES (31, 'Prompt1_35.wav', 'Prompt1', 'สามสิบห้า', 1);
INSERT INTO `tb_sound` VALUES (32, 'Prompt1_36.wav', 'Prompt1', 'สามสิบหก', 1);
INSERT INTO `tb_sound` VALUES (33, 'Prompt1_37.wav', 'Prompt1', 'สามสิบเจ็ด', 1);
INSERT INTO `tb_sound` VALUES (34, 'Prompt1_38.wav', 'Prompt1', 'สามสิบแปด', 1);
INSERT INTO `tb_sound` VALUES (35, 'Prompt1_39.wav', 'Prompt1', 'สามสิบเก้า', 1);
INSERT INTO `tb_sound` VALUES (36, 'Prompt1_4.wav', 'Prompt1', 'สี่', 1);
INSERT INTO `tb_sound` VALUES (37, 'Prompt1_40.wav', 'Prompt1', 'สี่สิบ', 1);
INSERT INTO `tb_sound` VALUES (38, 'Prompt1_5.wav', 'Prompt1', 'ห้า', 1);
INSERT INTO `tb_sound` VALUES (39, 'Prompt1_6.wav', 'Prompt1', 'หก', 1);
INSERT INTO `tb_sound` VALUES (40, 'Prompt1_7.wav', 'Prompt1', 'เจ็ด', 1);
INSERT INTO `tb_sound` VALUES (41, 'Prompt1_8.wav', 'Prompt1', 'แปด', 1);
INSERT INTO `tb_sound` VALUES (42, 'Prompt1_9.wav', 'Prompt1', 'เก้า', 1);
INSERT INTO `tb_sound` VALUES (43, 'Prompt1_A.wav', 'Prompt1', 'เอ', 1);
INSERT INTO `tb_sound` VALUES (44, 'Prompt1_B.wav', 'Prompt1', 'บี', 1);
INSERT INTO `tb_sound` VALUES (45, 'Prompt1_C.wav', 'Prompt1', 'ซี', 1);
INSERT INTO `tb_sound` VALUES (46, 'Prompt1_D.wav', 'Prompt1', 'ดี', 1);
INSERT INTO `tb_sound` VALUES (47, 'Prompt1_E.wav', 'Prompt1', 'อี', 1);
INSERT INTO `tb_sound` VALUES (48, 'Prompt1_F.wav', 'Prompt1', 'เอฟ', 1);
INSERT INTO `tb_sound` VALUES (49, 'Prompt1_G.wav', 'Prompt1', 'จี', 1);
INSERT INTO `tb_sound` VALUES (50, 'Prompt1_H.wav', 'Prompt1', 'เอช', 1);
INSERT INTO `tb_sound` VALUES (51, 'Prompt1_I.wav', 'Prompt1', 'ไอ', 1);
INSERT INTO `tb_sound` VALUES (52, 'Prompt1_J.wav', 'Prompt1', 'เจ', 1);
INSERT INTO `tb_sound` VALUES (53, 'Prompt1_K.wav', 'Prompt1', 'เค', 1);
INSERT INTO `tb_sound` VALUES (54, 'Prompt1_L.wav', 'Prompt1', 'แอล', 1);
INSERT INTO `tb_sound` VALUES (55, 'Prompt1_M.wav', 'Prompt1', 'เอ็ม', 1);
INSERT INTO `tb_sound` VALUES (56, 'Prompt1_N.wav', 'Prompt1', 'เอ็น', 1);
INSERT INTO `tb_sound` VALUES (57, 'Prompt1_Number.wav', 'Prompt1', 'หมายเลข', 1);
INSERT INTO `tb_sound` VALUES (58, 'Prompt1_O.wav', 'Prompt1', 'โอ', 1);
INSERT INTO `tb_sound` VALUES (59, 'Prompt1_P.wav', 'Prompt1', 'พี', 1);
INSERT INTO `tb_sound` VALUES (60, 'Prompt1_Q.wav', 'Prompt1', 'คิว', 1);
INSERT INTO `tb_sound` VALUES (61, 'Prompt1_R.wav', 'Prompt1', 'อาร์', 1);
INSERT INTO `tb_sound` VALUES (62, 'Prompt1_S.wav', 'Prompt1', 'เอส', 1);
INSERT INTO `tb_sound` VALUES (63, 'Prompt1_Service.wav', 'Prompt1', 'ที่ช่อง', 1);
INSERT INTO `tb_sound` VALUES (64, 'Prompt1_Service0.wav', 'Prompt1', 'ที่ช่อง', 1);
INSERT INTO `tb_sound` VALUES (65, 'Prompt1_Service1.wav', 'Prompt1', 'ที่ช่องการเงิน', 1);
INSERT INTO `tb_sound` VALUES (66, 'Prompt1_Service2.wav', 'Prompt1', 'ที่ห้องตรวจ', 1);
INSERT INTO `tb_sound` VALUES (67, 'Prompt1_Service3.wav', 'Prompt1', 'ที่ช่องรับยา', 1);
INSERT INTO `tb_sound` VALUES (68, 'Prompt1_Service4.wav', 'Prompt1', 'ที่โต๊ะอายุรกรรม', 1);
INSERT INTO `tb_sound` VALUES (69, 'Prompt1_Service5.wav', 'Prompt1', 'ที่โต๊ะเบาหวาน', 1);
INSERT INTO `tb_sound` VALUES (70, 'Prompt1_Service6.wav', 'Prompt1', 'ที่โต๊ะ', 1);
INSERT INTO `tb_sound` VALUES (71, 'Prompt1_Service7.wav', 'Prompt1', 'ที่ห้องแพทย์', 1);
INSERT INTO `tb_sound` VALUES (72, 'Prompt1_Service8.wav', 'Prompt1', 'ที่โต๊ะซักประวัติ', 1);
INSERT INTO `tb_sound` VALUES (73, 'Prompt1_Sir.wav', 'Prompt1', 'ค่ะ', 1);
INSERT INTO `tb_sound` VALUES (74, 'Prompt1_T.wav', 'Prompt1', 'ที', 1);
INSERT INTO `tb_sound` VALUES (75, 'Prompt1_U.wav', 'Prompt1', 'ยู', 1);
INSERT INTO `tb_sound` VALUES (76, 'Prompt1_V.wav', 'Prompt1', 'วี', 1);
INSERT INTO `tb_sound` VALUES (77, 'Prompt1_W.wav', 'Prompt1', 'ดับเบิลยู', 1);
INSERT INTO `tb_sound` VALUES (78, 'Prompt1_X.wav', 'Prompt1', 'เอกซ์', 1);
INSERT INTO `tb_sound` VALUES (79, 'Prompt1_Y.wav', 'Prompt1', 'วาย', 1);
INSERT INTO `tb_sound` VALUES (80, 'Prompt1_Z.wav', 'Prompt1', 'แซด', 1);
INSERT INTO `tb_sound` VALUES (81, 'Prompt1_to.wav', 'Prompt1', 'ถึง', 1);
INSERT INTO `tb_sound` VALUES (82, 'please.wav', 'Prompt1', 'เชิญหมายเลข', 1);
INSERT INTO `tb_sound` VALUES (83, 'BLIP.WAV', 'Prompt2', 'บี๊บ', 2);
INSERT INTO `tb_sound` VALUES (84, 'Prompt2_0.wav', 'Prompt2', 'ศูนย์', 2);
INSERT INTO `tb_sound` VALUES (85, 'Prompt2_1.wav', 'Prompt2', 'หนึ่ง', 2);
INSERT INTO `tb_sound` VALUES (86, 'Prompt2_10.wav', 'Prompt2', 'สิบ', 2);
INSERT INTO `tb_sound` VALUES (87, 'Prompt2_100.wav', 'Prompt2', 'ร้อย', 2);
INSERT INTO `tb_sound` VALUES (88, 'Prompt2_1000.wav', 'Prompt2', 'พัน', 2);
INSERT INTO `tb_sound` VALUES (89, 'Prompt2_11.wav', 'Prompt2', 'สิบเอ็ด', 2);
INSERT INTO `tb_sound` VALUES (90, 'Prompt2_2.wav', 'Prompt2', 'สอง', 2);
INSERT INTO `tb_sound` VALUES (91, 'Prompt2_20.wav', 'Prompt2', 'ยี่สิบ', 2);
INSERT INTO `tb_sound` VALUES (92, 'Prompt2_3.wav', 'Prompt2', 'สาม', 2);
INSERT INTO `tb_sound` VALUES (93, 'Prompt2_4.wav', 'Prompt2', 'สี่', 2);
INSERT INTO `tb_sound` VALUES (94, 'Prompt2_5.wav', 'Prompt2', 'ห้า', 2);
INSERT INTO `tb_sound` VALUES (95, 'Prompt2_6.wav', 'Prompt2', 'หก', 2);
INSERT INTO `tb_sound` VALUES (96, 'Prompt2_7.wav', 'Prompt2', 'เจ็ด', 2);
INSERT INTO `tb_sound` VALUES (97, 'Prompt2_8.wav', 'Prompt2', 'แปด', 2);
INSERT INTO `tb_sound` VALUES (98, 'Prompt2_9.wav', 'Prompt2', 'เก้า', 2);
INSERT INTO `tb_sound` VALUES (99, 'Prompt2_A.wav', 'Prompt2', 'เอ', 2);
INSERT INTO `tb_sound` VALUES (100, 'Prompt2_B.wav', 'Prompt2', 'บี', 2);
INSERT INTO `tb_sound` VALUES (101, 'Prompt2_C.wav', 'Prompt2', 'ซี', 2);
INSERT INTO `tb_sound` VALUES (102, 'Prompt2_D.wav', 'Prompt2', 'ดี', 2);
INSERT INTO `tb_sound` VALUES (103, 'Prompt2_E.wav', 'Prompt2', 'อี', 2);
INSERT INTO `tb_sound` VALUES (104, 'Prompt2_F.wav', 'Prompt2', 'เอฟ', 2);
INSERT INTO `tb_sound` VALUES (105, 'Prompt2_G.wav', 'Prompt2', 'จี', 2);
INSERT INTO `tb_sound` VALUES (106, 'Prompt2_Number.wav', 'Prompt2', 'หมายเลข', 2);
INSERT INTO `tb_sound` VALUES (107, 'Prompt2_Q.wav', 'Prompt2', 'คิว', 2);
INSERT INTO `tb_sound` VALUES (108, 'Prompt2_Service.wav', 'Prompt2', 'ที่ช่อง', 2);
INSERT INTO `tb_sound` VALUES (109, 'Prompt2_Service0.wav', 'Prompt2', 'ที่ช่อง', 2);
INSERT INTO `tb_sound` VALUES (110, 'Prompt2_Service1.wav', 'Prompt2', 'ที่ช่องการเงิน', 2);
INSERT INTO `tb_sound` VALUES (111, 'Prompt2_Service2.wav', 'Prompt2', 'ที่ห้องตรวจ', 2);
INSERT INTO `tb_sound` VALUES (112, 'Prompt2_Service3.wav', 'Prompt2', 'ที่ช่องรับยา', 2);
INSERT INTO `tb_sound` VALUES (113, 'Prompt2_Service4.wav', 'Prompt2', 'ที่โต๊ะอายุรกรรม', 2);
INSERT INTO `tb_sound` VALUES (114, 'Prompt2_Service5.wav', 'Prompt2', 'ที่โต๊ะเบาหวาน', 2);
INSERT INTO `tb_sound` VALUES (115, 'Prompt2_Service6.wav', 'Prompt2', 'ที่โต๊ะ', 2);
INSERT INTO `tb_sound` VALUES (116, 'Prompt2_Sir.wav', 'Prompt2', 'ครับ', 2);

-- ----------------------------
-- Table structure for tb_sound_station
-- ----------------------------
DROP TABLE IF EXISTS `tb_sound_station`;
CREATE TABLE `tb_sound_station`  (
  `sound_station_id` int(11) NOT NULL AUTO_INCREMENT,
  `sound_station_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ชื่อ',
  `counterserviceid` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ช่องบริการ',
  PRIMARY KEY (`sound_station_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_sound_station
-- ----------------------------
INSERT INTO `tb_sound_station` VALUES (1, 'เครื่องเล่นเสียงที่ 1 (ห้องตรวจ)', '11,12,13,14,15,16,17,18,19,20');
INSERT INTO `tb_sound_station` VALUES (2, 'เครื่องเล่นเสียงที่ 2 (เวชระเบียน)', '1,2,3,4,5,6,7,8,9,10');
INSERT INTO `tb_sound_station` VALUES (3, 'คัดกรอง', '25');

-- ----------------------------
-- Table structure for tb_ticket
-- ----------------------------
DROP TABLE IF EXISTS `tb_ticket`;
CREATE TABLE `tb_ticket`  (
  `ids` int(1) NOT NULL AUTO_INCREMENT,
  `hos_name_th` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ชื่อ รพ. ไทย',
  `hos_name_en` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อ รพ. อังกฤษ',
  `template` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'แบบบัตรคิว',
  `default_template` text CHARACTER SET utf8 COLLATE utf8_general_ci NULL COMMENT 'ต้นฉบับบัตรคิว',
  `logo_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `logo_base_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `barcode_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'รหัสโค้ด',
  `status` int(255) NULL DEFAULT NULL COMMENT 'สถานะการใช้งาน',
  PRIMARY KEY (`ids`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 7 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_ticket
-- ----------------------------
INSERT INTO `tb_ticket` VALUES (1, 'โรงพยาบาลบ้านบึง', 'Barnbung Hospital', '<div class=\"x_content\">\r\n<div class=\"row\" style=\"margin-bottom:0px; margin-left:0px; margin-right:0px; margin-top:0px; width:80mm\">\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:1cm 21px 0px 21px\">\r\n<div class=\"col-xs-12\" style=\"padding:0\"><img alt=\"\" class=\"center-block\" src=\"/img/logo/logo.jpg\" style=\"width:100px\" /></div>\r\n\r\n<div class=\"col-xs-12\" style=\"padding:0\">\r\n<h4 style=\"text-align:center\"><strong>{hos_name_th}</strong></h4>\r\n\r\n<h6 style=\"text-align:center\"><strong>งานบริการผู้ป่วยนอก</strong></h6>\r\n</div>\r\n\r\n<div class=\"col-xs-12\" style=\"padding:3px 0px 10px 0px; text-align:left\">\r\n<h6 style=\"margin-left:1px; margin-right:1px\"><strong>HN</strong> : <strong>{q_hn}</strong></h6>\r\n\r\n<h6 style=\"margin-left:1px; margin-right:1px\"><strong>ชื่อ-นามสกุล</strong> : <strong>{pt_name}</strong></h6>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-12\" style=\"padding:0\">\r\n<h1 style=\"text-align:center\"><strong>{q_num}</strong></h1>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<h5 style=\"text-align:center\"><strong>{pt_visit_type}</strong></h5>\r\n</div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<h5 style=\"text-align:center\"><strong>{sec_name}</strong></h5>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:5px 20px 0px 20px\">\r\n<div class=\"col-xs-12\" style=\"padding:0; text-align:left\">\r\n<div class=\"col-xs-12\" style=\"border-top:dashed 1px #404040; padding:4px 0px 3px 0px\">\r\n<div class=\"col-xs-12\" style=\"padding:1px\">\r\n<h6 style=\"margin-left:0px; margin-right:0px\"><strong>Scan QR Code เพื่อดูสถานะการรอคิว</strong></h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<div id=\"qrcode\"><img alt=\"\" src=\"/img/qrcode.png\" /></div>\r\n</div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<div id=\"bcTarget\" style=\"overflow:auto; padding:0px; width:143px\">\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:10px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:4px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:4px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:4px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:10px\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; clear:both; color:#000000; font-size:10px; margin-top:5px; text-align:center; width:100%\">1234567890128</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:10px 0px 0px 0px\">\r\n<h4 style=\"text-align:center\"><strong>ขอบคุณที่ใช้บริการ</strong></h4>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0; text-align:left\">\r\n<h6 style=\"text-align:left\"><strong>{time}</strong></h6>\r\n</div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0; text-align:right\">\r\n<h6 style=\"text-align:right\"><strong>{user_print}</strong></h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n', '<center>\r\n            <div class=\"x_content\">\r\n                <div class=\"row\" style=\"width: 80mm;margin: auto;\">\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 1cm 21px 0px 21px;\">\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <img src=\"/img/logo/logo.jpg\" alt=\"\" class=\"center-block\" style=\"width: 100px\">\r\n                        </div>\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <h4 class=\"color\" style=\"margin-top: 0px;margin-bottom: 0px;text-align: center;\"><b style=\"font-weight: bold;\">{hos_name_th}</b></h4>\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: center;\"><b>งานบริการผู้ป่วยนอก</b></h6>\r\n                        </div>\r\n                        <div class=\"col-xs-12\" style=\"padding: 3px 0px 10px 0px;;text-align: left;\">\r\n                            <h6 style=\"margin: 4px 1px;\" class=\"color\">\r\n                                <b style=\"font-size: 14px; font-weight: 600;\">HN</b>  :  <b style=\"font-size: 13px;\">{q_hn}</b>\r\n                            </h6>\r\n                            <h6 style=\"margin: 4px 1px;\" class=\"color\">\r\n                                <b style=\"font-size: 14px; font-weight: 600;\">ชื่อ-นามสกุล</b>  :  <b style=\"font-size: 13px;\">{pt_name}</b>\r\n                            </h6>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <h1 style=\"text-align: center;\"><b style=\"font-weight: 600;text-align: center;\">{q_num}</b></h1>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <h5 style=\"text-align: center;\"><b style=\"font-weight: 600;\">{pt_visit_type}</b></h5>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <h5 style=\"text-align: center;\"><b style=\"font-weight: 600;\">{sec_name}</b></h5>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 5px 20px 0px 20px;\">\r\n                        <div class=\"col-xs-12\" style=\"text-align: left;padding: 0;\">\r\n                            <div class=\"col-xs-12\" style=\"padding: 4px 0px 3px 0px;border-top: dashed 1px #404040;\">\r\n                                <div class=\"col-xs-12\" style=\"padding: 1px;\">\r\n                                    <h6 class=\"color\" style=\"margin: 0px;\"><b>Scan QR Code เพื่อดูสถานะการรอคิว</b></h6>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <div id=\"qrcode\"><img alt=\"\" src=\"/img/qrcode.png\" /></div>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <div id=\"bcTarget\" style=\"overflow: auto; padding: 0px; width: 143px;\"><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 10px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 4px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 4px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 4px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 10px\"></div><div style=\"clear:both; width: 100%; background-color: #FFFFFF; color: #000000; text-align: center; font-size: 10px; margin-top: 5px;\">1234567890128</div></div>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 10px 0px 0px 0px;\">\r\n                        <h4 class=\"color\" style=\"margin-top: 0px;margin-bottom: 0px;text-align: center;\"><b>ขอบคุณที่ใช้บริการ</b></h4>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;text-align: left;\">\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: left;\"><b>{time}</b></h6>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;text-align: right;\">\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: right;\"><b>{user_print}</b></h6>\r\n                        </div>\r\n                    </div>\r\n\r\n                </div>\r\n            </div>\r\n        </center>', '1/bYqPMMJ3pXUQ2RO-QtdIhqwVkv_Tlpp5.png', '/uploads', 'code128', 1);
INSERT INTO `tb_ticket` VALUES (6, 'เวชระเบียน', '', '<div class=\"x_content\">\r\n<div class=\"row\" style=\"margin-bottom:0px; margin-left:0px; margin-right:0px; margin-top:0px; width:80mm\">\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:1cm 21px 0px 21px\">\r\n<div class=\"col-xs-12\" style=\"padding:0\"><img alt=\"\" class=\"center-block\" src=\"/img/logo/logo.jpg\" style=\"width:100px\" /></div>\r\n\r\n<div class=\"col-xs-12\" style=\"padding:0\">\r\n<h4 style=\"text-align:center\"><strong>{hos_name_th}</strong></h4>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-12\" style=\"padding:0\">\r\n<h1 style=\"text-align:center\"><strong>{q_num}</strong></h1>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:5px 20px 0px 20px\">\r\n<div class=\"col-xs-12\" style=\"padding:0; text-align:left\">\r\n<div class=\"col-xs-12\" style=\"border-top:dashed 1px #404040; padding:4px 0px 3px 0px\">\r\n<div class=\"col-xs-12\" style=\"padding:1px\">\r\n<h6 style=\"margin-left:0px; margin-right:0px\"><strong>Scan QR Code เพื่อดูสถานะการรอคิว</strong></h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<div id=\"bcTarget\" style=\"overflow:auto; padding:0px; width:143px\">\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:10px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:4px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:4px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:4px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:10px\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; clear:both; color:#000000; font-size:10px; margin-top:5px; text-align:center; width:100%\">1234567890128</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:10px 0px 0px 0px\">\r\n<h4 style=\"text-align:center\"><strong>ขอบคุณที่ใช้บริการ</strong></h4>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0; text-align:left\">\r\n<h6 style=\"text-align:left\"><strong>{time}</strong></h6>\r\n</div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0; text-align:right\">\r\n<h6 style=\"text-align:right\"><strong>{user_print}</strong></h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n', '<center>\r\n            <div class=\"x_content\">\r\n                <div class=\"row\" style=\"width: 80mm;margin: auto;\">\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 1cm 21px 0px 21px;\">\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <img src=\"/img/logo/logo.jpg\" alt=\"\" class=\"center-block\" style=\"width: 100px\">\r\n                        </div>\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <h4 class=\"color\" style=\"margin-top: 0px;margin-bottom: 0px;text-align: center;\"><b style=\"font-weight: bold;\">{hos_name_th}</b></h4>\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: center;\"><b>งานบริการผู้ป่วยนอก</b></h6>\r\n                        </div>\r\n                        <div class=\"col-xs-12\" style=\"padding: 3px 0px 10px 0px;;text-align: left;\">\r\n                            <h6 style=\"margin: 4px 1px;\" class=\"color\">\r\n                                <b style=\"font-size: 14px; font-weight: 600;\">HN</b>  :  <b style=\"font-size: 13px;\">{q_hn}</b>\r\n                            </h6>\r\n                            <h6 style=\"margin: 4px 1px;\" class=\"color\">\r\n                                <b style=\"font-size: 14px; font-weight: 600;\">ชื่อ-นามสกุล</b>  :  <b style=\"font-size: 13px;\">{pt_name}</b>\r\n                            </h6>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <h1 style=\"text-align: center;\"><b style=\"font-weight: 600;text-align: center;\">{q_num}</b></h1>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <h5 style=\"text-align: center;\"><b style=\"font-weight: 600;\">{pt_visit_type}</b></h5>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <h5 style=\"text-align: center;\"><b style=\"font-weight: 600;\">{sec_name}</b></h5>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 5px 20px 0px 20px;\">\r\n                        <div class=\"col-xs-12\" style=\"text-align: left;padding: 0;\">\r\n                            <div class=\"col-xs-12\" style=\"padding: 4px 0px 3px 0px;border-top: dashed 1px #404040;\">\r\n                                <div class=\"col-xs-12\" style=\"padding: 1px;\">\r\n                                    <h6 class=\"color\" style=\"margin: 0px;\"><b>Scan QR Code เพื่อดูสถานะการรอคิว</b></h6>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <div id=\"qrcode\"><img alt=\"\" src=\"/img/qrcode.png\" /></div>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <div id=\"bcTarget\" style=\"overflow: auto; padding: 0px; width: 143px;\"><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 10px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 4px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 4px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 4px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 10px\"></div><div style=\"clear:both; width: 100%; background-color: #FFFFFF; color: #000000; text-align: center; font-size: 10px; margin-top: 5px;\">1234567890128</div></div>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 10px 0px 0px 0px;\">\r\n                        <h4 class=\"color\" style=\"margin-top: 0px;margin-bottom: 0px;text-align: center;\"><b>ขอบคุณที่ใช้บริการ</b></h4>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;text-align: left;\">\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: left;\"><b>{time}</b></h6>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;text-align: right;\">\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: right;\"><b>{user_print}</b></h6>\r\n                        </div>\r\n                    </div>\r\n\r\n                </div>\r\n            </div>\r\n        </center>', '1/ktOk2ipqA3sX7_yvjNmqANML1goEdfm7.png', '/uploads', 'code128', 1);

-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token`  (
  `user_id` int(11) NOT NULL,
  `code` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `created_at` int(11) NOT NULL,
  `type` smallint(6) NOT NULL,
  UNIQUE INDEX `token_unique`(`user_id`, `code`, `type`) USING BTREE,
  CONSTRAINT `token_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of token
-- ----------------------------
INSERT INTO `token` VALUES (2, '4ByWFETTb9ECekc2-Ps00GQ7y-ik9nf0', 1517979470, 0);

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` varchar(60) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `auth_key` varchar(32) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `confirmed_at` int(11) NULL DEFAULT NULL,
  `unconfirmed_email` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `blocked_at` int(11) NULL DEFAULT NULL,
  `registration_ip` varchar(45) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `updated_at` int(11) NOT NULL,
  `flags` int(11) NOT NULL DEFAULT 0,
  `last_login_at` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `user_unique_username`(`username`) USING BTREE,
  UNIQUE INDEX `user_unique_email`(`email`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (2, 'admin', 'admin-banbung@qsystem.com', '$2y$12$/cRo6GQyHmT6gufgQ13BK.Uwck0dW.NdCM55XRq5rB56Pm2kKdpFm', 's59qUPZ27CxgPEaNgLw71CrygQD5Ni3x', 1517979470, NULL, NULL, '127.0.0.1', 1517979470, 1517979470, 0, 1524712075);

SET FOREIGN_KEY_CHECKS = 1;
