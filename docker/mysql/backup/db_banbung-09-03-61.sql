/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MariaDB
 Source Server Version : 100130
 Source Host           : localhost:3306
 Source Schema         : db_banbung

 Target Server Type    : MariaDB
 Target Server Version : 100130
 File Encoding         : 65001

 Date: 09/03/2018 16:42:25
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
INSERT INTO `auth_item` VALUES ('User', 1, 'ผู้ใช้งาน', NULL, NULL, 1517978934, 1517978934);
INSERT INTO `auth_item` VALUES ('อายุรกรรม', 2, NULL, NULL, NULL, 1520584559, 1520584559);
INSERT INTO `auth_item` VALUES ('เจาะเลือด', 2, NULL, NULL, NULL, 1520584617, 1520584617);

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
INSERT INTO `auth_item_child` VALUES ('Admin', 'อายุรกรรม');
INSERT INTO `auth_item_child` VALUES ('Admin', 'เจาะเลือด');
INSERT INTO `auth_item_child` VALUES ('User', '/site/*');
INSERT INTO `auth_item_child` VALUES ('User', '/user/settings/*');
INSERT INTO `auth_item_child` VALUES ('User', 'อายุรกรรม');
INSERT INTO `auth_item_child` VALUES ('User', 'เจาะเลือด');
INSERT INTO `auth_item_child` VALUES ('อายุรกรรม', '/kiosk/*');
INSERT INTO `auth_item_child` VALUES ('เจาะเลือด', '/kiosk/*');

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
) ENGINE = InnoDB AUTO_INCREMENT = 16 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of file_storage_item
-- ----------------------------
INSERT INTO `file_storage_item` VALUES (4, 'fileStorage', '/uploads', '1/HqOmC4NQtOBWkm3nPTb8SZkAulHeJB5C.png', 'image/png', 131577, 'HqOmC4NQtOBWkm3nPTb8SZkAulHeJB5C', '127.0.0.1', 1518077491);
INSERT INTO `file_storage_item` VALUES (6, 'fileStorage', '/uploads', '1/wztTX4WnG9CJmJm8qCu373LZktLyB_ZB.png', 'image/png', 131577, 'wztTX4WnG9CJmJm8qCu373LZktLyB_ZB', '127.0.0.1', 1518354952);
INSERT INTO `file_storage_item` VALUES (11, 'fileStorage', '/uploads', '1/LhB7uf0Y_-YMshBRI7JND4PcskgScK9_.png', 'image/png', 131577, 'LhB7uf0Y_-YMshBRI7JND4PcskgScK9_', '127.0.0.1', 1518356171);
INSERT INTO `file_storage_item` VALUES (13, 'fileStorage', '/uploads', '1/toCzqUFuEC9ZULBVuZdydEy2fvXQaMfS.jpg', 'image/jpeg', 112864, 'toCzqUFuEC9ZULBVuZdydEy2fvXQaMfS', '127.0.0.1', 1518497803);
INSERT INTO `file_storage_item` VALUES (14, 'fileStorage', '/uploads', '1/85Gt_w6b3RPYeBFZqdLfB2Q49UoOPejg.png', 'image/png', 131577, '85Gt_w6b3RPYeBFZqdLfB2Q49UoOPejg', '127.0.0.1', 1519360037);
INSERT INTO `file_storage_item` VALUES (15, 'fileStorage', '/uploads', '1/lvCa3PFqJzOASW145okm6zG0UDzx9Qcy.png', 'image/png', 131577, 'lvCa3PFqJzOASW145okm6zG0UDzx9Qcy', '127.0.0.1', 1520569421);

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
INSERT INTO `key_storage_item` VALUES ('app-name', 'ระบบคิว รพ.ชัยนาทนเรนทร', '', 1518009906, 1518009214);
INSERT INTO `key_storage_item` VALUES ('dynamic-limit', '20', '', 1519362612, 1519362612);
INSERT INTO `key_storage_item` VALUES ('frontend.body.class', 'fixed-sidebar fixed-navbar', '', 1518010225, NULL);
INSERT INTO `key_storage_item` VALUES ('frontend.navbar', 'navbar-fixed-top', 'fix navbar header', 1515767197, NULL);
INSERT INTO `key_storage_item` VALUES ('frontend.page-breadcrumbs', '0', 'breadcrumbs-fixed', 1515767838, NULL);
INSERT INTO `key_storage_item` VALUES ('frontend.page-header', '0', 'page-header-fixed', 1515767908, NULL);
INSERT INTO `key_storage_item` VALUES ('frontend.page-sidebar', 'sidebar-fixed menu-compact', 'sidebar-fixed , menu-compact', 1516690802, NULL);

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
  CONSTRAINT `fk_user_profile` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
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
  CONSTRAINT `fk_user_account` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_unicode_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for tb_caller
-- ----------------------------
DROP TABLE IF EXISTS `tb_caller`;
CREATE TABLE `tb_caller`  (
  `caller_ids` int(11) NOT NULL AUTO_INCREMENT COMMENT 'running',
  `q_ids` int(11) NULL DEFAULT NULL,
  `qtran_ids` int(11) NULL DEFAULT NULL,
  `service_sec_id` int(11) NULL DEFAULT NULL,
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
  `counterserviceid` int(11) NOT NULL AUTO_INCREMENT COMMENT 'เลขที่บริการ',
  `counterservice_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อบริการ',
  `counterservice_callnumber` int(11) NULL DEFAULT NULL,
  `counterservice_type` int(11) NULL DEFAULT NULL COMMENT 'ประเภทบริการ',
  `servicegroupid` int(11) NULL DEFAULT NULL,
  `userid` int(11) NULL DEFAULT NULL COMMENT 'ผู้ให้บริการ (1,2,3 หรือ all)',
  `sec_id` int(11) NULL DEFAULT NULL COMMENT 'แผนก',
  `sound_stationid` int(11) NULL DEFAULT NULL,
  `sound_typeid` int(11) NULL DEFAULT NULL COMMENT 'ประเภทเสียง',
  `counterservice_status` varchar(10) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sound_path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `sound_service_number` int(11) NULL DEFAULT NULL,
  `sound_service_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`counterserviceid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 9 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_counterservice
-- ----------------------------
INSERT INTO `tb_counterservice` VALUES (2, 'โต๊ะคัดกรอง 1', NULL, 1, NULL, NULL, 1, 1, 1, '1', 'Prompt1', 1, 'Prompt1_Service0.wav');
INSERT INTO `tb_counterservice` VALUES (3, 'โต๊ะคัดกรอง 2', NULL, 1, NULL, NULL, 1, 1, 1, '1', 'Prompt1', 2, 'Prompt1_Service0.wav');
INSERT INTO `tb_counterservice` VALUES (4, 'ห้องตรวจ 1', NULL, 2, NULL, 1, 1, 1, 1, '1', 'Prompt1', 1, 'Prompt1_Service2.wav');
INSERT INTO `tb_counterservice` VALUES (5, 'ห้องตรวจ 2', NULL, 2, NULL, 2, 1, 1, 1, '1', 'Prompt1', 2, 'Prompt1_Service2.wav');
INSERT INTO `tb_counterservice` VALUES (6, 'ห้องตรวจ 3', NULL, 2, NULL, 3, 1, 1, 1, '1', 'Prompt1', 3, 'Prompt1_Service2.wav');
INSERT INTO `tb_counterservice` VALUES (7, 'ห้องตรวจ 4', NULL, 2, NULL, 7, 1, 1, 1, '1', 'Prompt1', 4, 'Prompt1_Service2.wav');
INSERT INTO `tb_counterservice` VALUES (8, 'ห้องเจาะเลือด 1', NULL, 3, NULL, NULL, 2, 1, 1, '1', 'Prompt1', 1, 'Prompt1_Service0.wav');

-- ----------------------------
-- Table structure for tb_counterservice_type
-- ----------------------------
DROP TABLE IF EXISTS `tb_counterservice_type`;
CREATE TABLE `tb_counterservice_type`  (
  `tb_counterservice_typeid` int(11) NOT NULL AUTO_INCREMENT,
  `tb_counterservice_type` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `q_waiting_status` int(11) NOT NULL COMMENT 'สถานะรอ',
  `q_calling_status` int(11) NOT NULL COMMENT 'สถานะเรียก',
  PRIMARY KEY (`tb_counterservice_typeid`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_counterservice_type
-- ----------------------------
INSERT INTO `tb_counterservice_type` VALUES (1, 'โต๊ะคัดกรอง', 1, 2);
INSERT INTO `tb_counterservice_type` VALUES (2, 'ห้องตรวจ', 5, 7);
INSERT INTO `tb_counterservice_type` VALUES (3, 'ห้องเจาะเลือด', 3, 4);

-- ----------------------------
-- Table structure for tb_display_config
-- ----------------------------
DROP TABLE IF EXISTS `tb_display_config`;
CREATE TABLE `tb_display_config`  (
  `display_ids` int(11) NOT NULL AUTO_INCREMENT,
  `display_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
  `counterservice_type` int(11) NOT NULL,
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
  PRIMARY KEY (`display_ids`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_display_config
-- ----------------------------
INSERT INTO `tb_display_config` VALUES (1, 'คัดกรอง', 1, 'เรียกคิวคัดกรอง', 'อายุรกรรม', 'หมายเลข', 'ช่อง', 4, 'คิวที่เรียกไปแล้ว', '#000000', '#666666', '#204d74', '#ffffff', '#ffffff', '#62cb31');
INSERT INTO `tb_display_config` VALUES (2, 'ห้องตรวจ', 2, 'เรียกคิวห้องตรวจ', 'อายุรกรรม', 'หมายเลข', 'ห้อง', 4, 'คิวที่เรียกไปแล้ว', '#000000', '#666666', '#204d74', '#ffffff', '#ffffff', '#62cb31');
INSERT INTO `tb_display_config` VALUES (3, 'ห้องเจาะเลือด', 3, 'เรียกคิวห้องเจาะเลือด', 'เจาะเลือด', 'หมายเลข', 'ช่อง', 4, 'คิวที่เรียกไปแล้ว', '#000000', '#666666', '#204d74', '#ffffff', '#ffffff', '#62cb31');

-- ----------------------------
-- Table structure for tb_his_data
-- ----------------------------
DROP TABLE IF EXISTS `tb_his_data`;
CREATE TABLE `tb_his_data`  (
  `ids` int(11) NOT NULL AUTO_INCREMENT COMMENT 'running',
  `q_vn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Visit number ของผู้ป่วย',
  `q_hn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'หมายเลข HN ผู้ป่วย',
  `pt_id` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  `pt_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อผู้ป่วย',
  `pt_visit_type_id` int(11) NULL DEFAULT NULL,
  `pt_appoint_secid` int(11) NULL DEFAULT NULL COMMENT 'แผนกที่นัดหมาย',
  `doctor_id` int(11) NULL DEFAULT NULL COMMENT 'รหัสแพทย์ผู้นัดหมาย',
  PRIMARY KEY (`ids`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 717 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_his_data
-- ----------------------------
INSERT INTO `tb_his_data` VALUES (2, '2', '6145539', NULL, 'นายธราวุธ    ขาวยะบุตร', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (3, '3', '6145541', NULL, 'นายนพรุจ    หาริชัย', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (4, '4', '6145547', NULL, 'นายวัชรินทร์    เวชกามา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (5, '5', '6145552', NULL, 'นายอันดรูว์    ศรีมุกดา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (6, '6', '6145558', NULL, 'นางสาวจันทรัตน์    กลยณีย์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (7, '7', '6145560', NULL, 'นางสาวธนภร    คณาณุวัฒนวนิช', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (8, '8', '6145564', NULL, 'นางสาวเบญญทิพย์    กุจะพันธ์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (9, '9', '6145567', NULL, 'นางสาวปิยะวรรณ    ชมภูหลวง', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (10, '10', '6145568', NULL, 'นางสาวปุณยนุช    ประโคทัง', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (11, '11', '6145571', NULL, 'นางสาวพิชญานันท์    ทองอันตัง', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (12, '12', '6145575', NULL, 'นางสาวมัชฌิมา    กิณเรศ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (13, '13', '6145577', NULL, 'นางสาวรุ้งจรัส    จันทรังษี', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (14, '14', '6145579', NULL, 'นางสาววิชญาดา    โยตะสี', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (15, '15', '6145580', NULL, 'นางสาววิชญาภา    จันทร์นามวงค์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (16, '16', '6145581', NULL, 'นางสาวศิรประภา    บุญท้าว', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (17, '17', '6145583', NULL, 'นางสาวสุทิตา    สิงห์สวัสดิ์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (18, '18', '6145585', NULL, 'นางสาวอัสพาภรณ์    พันธ์สวรรค์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (19, '19', '6145594', NULL, 'นายณัฏฐณิช    ราชบุญเรือง', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (20, '20', '6145597', NULL, 'นายธราเทพ    สานุศิษย์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (21, '21', '6145598', NULL, 'นายปริวัตร    โสมแผ้ว', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (22, '22', '6145600', NULL, 'นายพิชญา    เปรมอุดม', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (23, '23', '6145603', NULL, 'นายอานนท์    บุญสิทธิ์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (24, '24', '6145616', NULL, 'นางสาวปรารถนา    สุวรรณพันธ์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (25, '25', '6145622', NULL, 'นางสาวภาวัชญา    พิลาวรรณ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (26, '26', '6145629', NULL, 'นางสาวสิริรัตน์    มุลตะกร', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (27, '27', '6145653', NULL, 'นายรัฎฌานนท์    ภูกองไชย', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (28, '28', '6145658', NULL, 'นายอุกฤษฎ์    ฮ่มป่า', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (29, '29', '6145686', NULL, 'นายตุลาการ    อุ่นศิริ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (30, '30', '6145699', NULL, 'นายอิทธิกร    ประกายศักดิ์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (31, '31', '6145720', NULL, 'นางสาวมิ่งขวัญ    มุงคุณ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (32, '32', '6145722', NULL, 'นางสาวรวิวรรณ    เภาโพธิ์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (33, '33', '6146137', NULL, 'นายชนาธิป    เหลืองชาลี', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (34, '34', '6146138', NULL, 'นายณัฐสิทธิ์    ศรีมุกดา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (35, '35', '6146139', NULL, 'นายธณดล    กาวิละแพทย์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (36, '36', '6146140', NULL, 'นายธนาคม    พันธุ์สีเลา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (37, '37', '6146141', NULL, 'นายบดินทร์    เสนีวงศ์  ณ อยุธยา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (38, '38', '6146142', NULL, 'นายพรหมเทพ    อนุญาหงษ์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (39, '39', '6146147', NULL, 'นายสุภวัฒน์    ปัญจะ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (40, '40', '6146150', NULL, 'นางสาวเจษณี    ดาบสีพาย', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (41, '41', '6146151', NULL, 'นางสาวชุติกาญจน์    กวีวรญาณ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (42, '42', '6146153', NULL, 'นางสาวฑิตฐิตา    ทะแพงพันธ์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (43, '43', '6146155', NULL, 'นางสาวปฏิญาพร    กวดวงศ์ษา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (44, '44', '6146156', NULL, 'นางสาวปทิตตา    ยุพิน', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (45, '45', '6146161', NULL, 'นางสาววริศรา    วงศ์มหาชัย', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (46, '46', '6146162', NULL, 'นางสาวศุภลักษ์    แพงจ่าย', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (47, '47', '6146163', NULL, 'นางสาวสโรชา    พรศิลปกุล', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (48, '48', '6146189', NULL, 'นางสาวสรวงชนก    ระเวงวรรณ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (49, '49', '6148820', NULL, 'นายวชิรวิทย์    บุตรประชา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (50, '50', '6148821', NULL, 'นายแดนนี่    ชีฟาล่า', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (51, '51', '6145536', NULL, 'นายกสิวัฒน์    ขาวขันธ์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (52, '52', '6145545', NULL, 'นายรัตติพงศ์    ไชยรา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (53, '53', '6145546', NULL, 'นายวัชรพล    ทิพกุล', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (54, '54', '6145549', NULL, 'นายวิภพ    ใครบุตร', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (55, '55', '6145551', NULL, 'นายอรรถพล    วรรณศรี', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (56, '56', '6145561', NULL, 'นางสาวธนัชชนก    อภิวัชรกุล', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (57, '57', '6145570', NULL, 'นางสาวพิกุลแก้ว    แก้วพิกุล', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (58, '58', '6145573', NULL, 'นางสาวภัทรพร    สิงห์คาม', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (59, '59', '6145605', NULL, 'นางสาวกมลวรรณ    เคะนะอ่อน', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (60, '60', '6145607', NULL, 'นางสาวจิดาภา    ศรีมามาศ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (61, '61', '6145614', NULL, 'นางสาวเบญญาภา    ขันทีท้าว', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (62, '62', '6145620', NULL, 'นางสาวพัชรีภรณ์    ปานโบ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (63, '63', '6145632', NULL, 'นางสาวสุพิชชา    อุทัยวี', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (64, '64', '6145644', NULL, 'นายฐานทัพ    เศียรกระโทก', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (65, '65', '6145646', NULL, 'นายธนภัทร    กันบุรมย์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (66, '66', '6145650', NULL, 'นายพงศภัค    เปลี่ยนเอก', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (67, '67', '6145654', NULL, 'นายวงศธร    วรกิตติกุล', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (68, '68', '6145659', NULL, 'นางสาวกนิษฐนาฏ    เสาวภาคลิมป์กุล', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (69, '69', '6145660', NULL, 'นางสาวกษวรรณ    เดชผล', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (70, '70', '6145664', NULL, 'นางสาวณัฎฐณิภร    จุลพล', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (71, '71', '6145667', NULL, 'นางสาวทิวาวรรณ    แก้วก่า', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (72, '72', '6145671', NULL, 'นางสาวบัญฑิตา    พรมสุ่ย', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (73, '73', '6145682', NULL, 'นางสาวสุริมา    กลยนี', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (74, '74', '6145684', NULL, 'นางสาวอรพรรณ    เสนาคำ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (75, '75', '6145689', NULL, 'นายปรารภ    ไตรพิษ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (76, '76', '6145696', NULL, 'นายอนันต์    สุนทรา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (77, '77', '6145709', NULL, 'นางสาวตวัน    ทัศนบรรลือ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (78, '78', '6145719', NULL, 'นางสาวภิฌาฎา    แสนรัษฎากร', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (79, '79', '6145723', NULL, 'นางสาววรรณชนก    รูปเหลี่ยม', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (80, '80', '6145724', NULL, 'นางสาววรีวรรณ    นามตาแสง', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (81, '81', '6145731', NULL, 'นางสาวศุภัชฌา    เดชะ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (82, '82', '6145746', NULL, 'นายนพณัฐ    ธาตุระหัน', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (83, '83', '6145750', NULL, 'นายวิทวัส    ธารเอี่ยม', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (84, '84', '6145793', NULL, 'นายนมากรณ์    ศรีพลพา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (85, '85', '6145825', NULL, 'นางสาวรดาศา    คำเมือง', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (86, '86', '6145834', NULL, 'นางสาวอาทิรยา    วรรณจันทร์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (87, '87', '6145848', NULL, 'นางสาวกนกพร    ฐานทนดี', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (88, '88', '6145861', NULL, 'นางสาวดารารัตน์    เพลิดพราว', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (89, '89', '6146154', NULL, 'นางสาวธนสรณ์    ทองสุทธา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (90, '90', '6146170', NULL, 'นายดิศรณ์    นามละคร', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (91, '91', '6146172', NULL, 'นายนภดล    มุกดาประเสริฐ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (92, '92', '6146192', NULL, 'นางสาวสิริยากร    วินิจสุมานนท์', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (93, '93', '6147262', NULL, 'นางสาวกาญจนา    แซ่ตั้น', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (94, '94', '6147270', NULL, 'นางสาวกนกพร    พรมเรียน', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (95, '95', '6148822', NULL, 'นายศิริภูมิ    นิ่มมา', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (96, '96', '6148823', NULL, 'นายสหัสวรรษ    แจ่มจำรัส', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (97, '97', '6148824', NULL, 'นายอภิวัฒน์    ผ่ามวัน', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (98, '98', '6148825', NULL, 'นางสาวทิพาวรรณ    อ่อนโสตะ', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (99, '99', '6148826', NULL, 'นางสาวปาริชาติ    ยศทอง', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (100, '100', '6145543', NULL, 'นายปิยะบุตร    ปัทมธรรมกุล', 2, NULL, NULL);
INSERT INTO `tb_his_data` VALUES (101, '101', '6145556', NULL, 'นางสาวกัญญารัตน์    ก้อนแพง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (102, '102', '6145562', NULL, 'นางสาวธีร์จุฑา    แสนวิเศษ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (103, '103', '6145563', NULL, 'นางสาวนันทพร    จันทร์สะอาด', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (104, '104', '6145565', NULL, 'นางสาวปณัดดา    เสนาชัย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (105, '105', '6145590', NULL, 'นายกานต์    เกียรติสุขุมพงศ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (106, '106', '6145592', NULL, 'นายเจริญพร    สุวรรณธร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (107, '107', '6145604', NULL, 'นางสาวกนกรัตน์    ปทุมวัน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (108, '108', '6145610', NULL, 'นางสาวชุติกาญจน์    ประทุมทอง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (109, '109', '6145611', NULL, 'นางสาวณัฐกานต์    อิ้มพัฒน์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (110, '110', '6145617', NULL, 'นางสาวปวีณา    ศิริเดชไชยวงศ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (111, '111', '6145619', NULL, 'นางสาวพรรณภัทร    สุนสิน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (112, '112', '6145621', NULL, 'นางสาวพิชญาภา    ป้อมไชยา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (113, '113', '6145627', NULL, 'นางสาวศตนันท์    แก้วอุดม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (114, '114', '6145628', NULL, 'นางสาวสริตา    มีเกาะ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (115, '115', '6145631', NULL, 'นางสาวสุนิสา    กัญญาน้อย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (116, '116', '6145638', NULL, 'นายกิตติศักดิ์    พลอาษา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (117, '117', '6145641', NULL, 'นายจิรภัทร    ร่มเกษ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (118, '118', '6145647', NULL, 'นายธรรมรัตน์    พอกเพิ่มดี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (119, '119', '6145662', NULL, 'นางสาวกุลสตรี    ศรีเรืองสุข', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (120, '120', '6145666', NULL, 'นางสาวดุจดาว    เถาว์กลาง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (121, '121', '6145687', NULL, 'นายธนพล    ศรีพรหมษา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (122, '122', '6145688', NULL, 'นายนนทวัฒน์    แพงสาร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (123, '123', '6145695', NULL, 'นายหาญ    ฮึกเหิม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (124, '124', '6145698', NULL, 'นายอัมรินทร์    อุตระหงษ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (125, '125', '6145708', NULL, 'นางสาวณิชามญชุ์    อุปพงษ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (126, '126', '6145711', NULL, 'นางสาวนิตินพรัช    ก่อทอง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (127, '127', '6145717', NULL, 'นางสาวเพ็ญนภา    หลินภู', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (128, '128', '6145762', NULL, 'นางสาวณัฎฐ์ชญา    ขวัญสงค์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (129, '129', '6145773', NULL, 'นางสาวพรณิชชา    ตงศิริ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (130, '130', '6145803', NULL, 'นายสุรพัศ    อุดมผล', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (131, '131', '6145822', NULL, 'นางสาวปรางทิพย์    ประกอบนา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (132, '132', '6145826', NULL, 'นางสาวรวิสรา    เรืองชัยธนกูล', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (133, '133', '6145844', NULL, 'นายยุทธชัย    วิชนา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (134, '134', '6145849', NULL, 'นางสาวกรชนก    นนทวงษ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (135, '135', '6145854', NULL, 'นางสาวชลธิชา    งามกุดตุ้ม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (136, '136', '6145856', NULL, 'นางสาวชุติภา    สกนธวัฒน์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (137, '137', '6145872', NULL, 'นางสาวมิถุนา    ฝอยทอง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (138, '138', '6145874', NULL, 'นางสาววราทิพย์    ดอนเสนา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (139, '139', '6146177', NULL, 'นางสาวกัญญาภรณ์    รุ่งเรืองวาณิช', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (140, '140', '6148827', NULL, 'นายกฤษฎา    พากุล', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (141, '141', '6148828', NULL, 'นายคณัสนันท์    แก้วดี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (142, '142', '6148829', NULL, 'นายวรรธนัย    ไชยตะมาตย์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (143, '143', '6148830', NULL, 'นายวัชรพงษ์    โพธิ์นอก', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (144, '144', '6148831', NULL, 'นายศราวุฒิ    ไถนาคำ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (145, '145', '6148832', NULL, 'นายศุภกิจ    บิดร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (146, '146', '6148833', NULL, 'นางสาวนัฐิยา    จันสุข', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (147, '147', '6148834', NULL, 'นางสาวศิริพร    ตันเสนา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (148, '148', '6148835', NULL, 'นางสาวสุรินทร    แสนสุภา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (149, '149', '6145612', NULL, 'นางสาวณัฐยา    มหาวงศ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (150, '150', '6145623', NULL, 'นางสาวมธุรดา    โททุมพล', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (151, '151', '6145636', NULL, 'นายกิตติคุณ    สิงห์คำมา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (152, '152', '6145656', NULL, 'นายศุภณัฐ    จันทาศรี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (153, '153', '6145668', NULL, 'นางสาวธนัญญา    โถตันคำ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (154, '154', '6145673', NULL, 'นางสาวพัณทิภา    อุสาพรหม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (155, '155', '6145674', NULL, 'นางสาวพิยะดา    แก้วดี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (156, '156', '6145675', NULL, 'นางสาวรามาวดี    โฮมป่า', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (157, '157', '6145676', NULL, 'นางสาวริณรพี    ปัญญาประชุม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (158, '158', '6145680', NULL, 'นางสาวสิริโสภาคย์    สร้อยมาลัย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (159, '159', '6145681', NULL, 'นางสาวสุดารัตน์    ไกยะฝ่าย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (160, '160', '6145706', NULL, 'นางสาวชัญญา    คำสวัสดิ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (161, '161', '6145707', NULL, 'นางสาวณัฏฐณิชา    ศรีสวัสดิ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (162, '162', '6145727', NULL, 'นางสาวศศิธร    ชินบุตร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (163, '163', '6145728', NULL, 'นางสาวศศิภัทร    โคสาแสง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (164, '164', '6145732', NULL, 'นางสาวศุภาพิชญ์    นาคบุตรศรี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (165, '165', '6145734', NULL, 'นางสาวอรุโณทัย    อินธิแสง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (166, '166', '6145741', NULL, 'นายจิรเมธ    ศรีหนองห้าง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (167, '167', '6145745', NULL, 'นายธนภัทร    สุวรรณวิเศษ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (168, '168', '6145757', NULL, 'นางสาวงามผกา    อินธิสาร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (169, '169', '6145765', NULL, 'นางสาวธิดารัตน์    บรรไพร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (170, '170', '6145771', NULL, 'นางสาวปัทมา    ศรีล้านมี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (171, '171', '6145800', NULL, 'นายยศภัทร    ดวงปากดี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (172, '172', '6145833', NULL, 'นางสาวอัญธิดา    นนจันทร์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (173, '173', '6145841', NULL, 'นายธนดล    แถมสมดี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (174, '174', '6146073', NULL, 'นางสาวเนตรชนก    ธราพร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (175, '175', '6146086', NULL, 'นายกรรชิง    พรมเสนีย์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (176, '176', '6146167', NULL, 'นายกองทัพ    แรงสูงเนิน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (177, '177', '6146188', NULL, 'นางสาวภวิกา    แจ้งสุข', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (178, '178', '6147269', NULL, 'นายศุภชัย    พานิชย์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (179, '179', '6148837', NULL, 'นายโชคอนันต์    รันนะโคตร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (180, '180', '6148839', NULL, 'นายภานุวัฒน์    รุจิวัฒน์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (181, '181', '6148840', NULL, 'นายวทัญญู    พลราชม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (182, '182', '6148841', NULL, 'นางสาวกชพร    ยาสาไชย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (183, '183', '6148842', NULL, 'นางสาวจิณห์จุฑา    ศรีหล้า', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (184, '184', '6148843', NULL, 'นางสาวณัชราพร    ดาวรุ่งโรจน์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (185, '185', '6148844', NULL, 'นางสาวธนภรณ์    วังคีรี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (186, '186', '6148845', NULL, 'นางสาวนริศรา    ศรีโคตร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (187, '187', '6148846', NULL, 'นางสาวเปรมฤดี    สุริโย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (188, '188', '6148847', NULL, 'นางสาวพิชชากานต์    มูลโพธิ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (189, '189', '6148848', NULL, 'นางสาวพิมรา    ฮาดคะดี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (190, '190', '6148849', NULL, 'นางสาวศรินทิพย์    ประฮาดชัย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (191, '191', '6148851', NULL, 'นางสาวสิริพร    ผิวเดช', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (192, '192', '6148852', NULL, 'นางสาวสุชานาถ    ฮูวิชิต', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (193, '193', '6148853', NULL, 'นางสาวอาภา    สุวรรณสิงห์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (194, '194', '6149042', NULL, 'นางสาวธนาภรณ์    สินวร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (195, '195', '6149044', NULL, 'นางสาวพัณณิตา    กิณเรศ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (196, '196', '6150706', NULL, 'นายพุฒิพงษ์    ชินโน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (197, '197', '6145589', NULL, 'นายกันตวิชญ์    เมืองแสน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (198, '198', '6145602', NULL, 'นายอรรถวิทย์    แดนรักษ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (199, '199', '6145609', NULL, 'นางสาวชนินาถ    พูนปริญญา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (200, '200', '6145642', NULL, 'นายชญานนท์    ทิพม้อม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (201, '201', '6145649', NULL, 'นายบัณฑิต    แสนมุงคุณ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (202, '202', '6145665', NULL, 'นางสาวณัฏฐณิชา    พรหมเชษฐา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (203, '203', '6145669', NULL, 'นางสาวธันยธรณ์    ศรีแก้ว', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (204, '204', '6145691', NULL, 'นายภัทรศักดิ์    นนตะแสน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (205, '205', '6145700', NULL, 'นายอิสระ    วะจีสิงห์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (206, '206', '6145713', NULL, 'นางสาวพธนภรณ์    สุวรรณจันทร์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (207, '207', '6145733', NULL, 'นางสาวสุพิศรา    ยาทองไชย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (208, '208', '6145744', NULL, 'นายธนภัทร    จันทะลัย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (209, '209', '6145754', NULL, 'นางสาวกนกวรรณ    จันทะพรหม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (210, '210', '6145778', NULL, 'นางสาวสุกฤตา    สนอุดม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (211, '211', '6145781', NULL, 'นางสาวอติพร    เป้งคำภา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (212, '212', '6145790', NULL, 'นายวรินทร    ไชยบุญ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (213, '213', '6145792', NULL, 'นายนนทนันท์    จำปาที', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (214, '214', '6145795', NULL, 'นายบูรพา    ทุมคำ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (215, '215', '6145796', NULL, 'นายปฏิพัทธ์    ศรีจันทร์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (216, '216', '6145810', NULL, 'นางสาวณัฏฐ์ภรณ์    พันธุออน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (217, '217', '6145818', NULL, 'นางสาวนริศรา    สิมมาคำ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (218, '218', '6145835', NULL, 'นางสาวไอลดา    ศรีมงคล', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (219, '219', '6145847', NULL, 'นางสาวกชกร    สืบสิงห์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (220, '220', '6145858', NULL, 'นางสาวณัฐริกา    คณานันท์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (221, '221', '6145859', NULL, 'นางสาวณัฐริกานต์    พรมเมือง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (222, '222', '6145866', NULL, 'นางสาวนิศารัตน์    ชุมปัญญา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (223, '223', '6145871', NULL, 'นางสาวภาวิตา    ไชยสัตย์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (224, '224', '6145966', NULL, 'นางสาวจุฑามาศ    แก่นแก้ว', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (225, '225', '6145970', NULL, 'นางสาวณัฐฐินันท์    เดชธิสา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (226, '226', '6146120', NULL, 'นางสาวณัฐริกา    แก้ววันนา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (227, '227', '6146127', NULL, 'นางสาวภัทรนันท์    ทองเถาว์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (228, '228', '6146174', NULL, 'นายวิชัยชาญ    บุญเฮ้า', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (229, '229', '6148854', NULL, 'นายนพฤทธิ์    นวานุช', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (230, '230', '6148855', NULL, 'นายพงศกร    อุดร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (231, '231', '6148856', NULL, 'นายพุฒิพงค์    ต้นศรี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (232, '232', '6148857', NULL, 'นายสุวสันต์    ปทุมมาศ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (233, '233', '6148858', NULL, 'นางสาวกรกนก    ใยวังหน้า', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (234, '234', '6148859', NULL, 'นางสาวจิราภรณ์    เดชขันธ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (235, '235', '6148860', NULL, 'นางสาวณัฐณิชา    ศรีเพชร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (236, '236', '6148861', NULL, 'นางสาวน้ำพลอย    อัมไพพันธ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (237, '237', '6148862', NULL, 'นางสาวนิรชา    คำสิงห์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (238, '238', '6148863', NULL, 'นางสาวเนรัญชลา    ทองเลิศ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (239, '239', '6148864', NULL, 'นางสาวบุษยารัตน์    ทองทา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (240, '240', '6148865', NULL, 'นางสาวปวีณา    การุญ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (241, '241', '6148866', NULL, 'นางสาวพรนภัส    พลวงค์ษา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (242, '242', '6148868', NULL, 'นางสาวพิทยารัตน์    พ่อชมภู', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (243, '243', '6148869', NULL, 'นางสาวสุดาภา    จันทะรังษี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (244, '244', '6148870', NULL, 'นางสาวอมิตา    กิ้วภาวัน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (245, '245', '6148871', NULL, 'นางสาวอัจฉริยา    เหลาพรม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (246, '246', '6148872', NULL, 'นางสาวอารียา    พานโฮม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (247, '247', '6149882', NULL, 'นางสาวสุวพิชญ์    บุญเรือง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (248, '248', '6145645', NULL, 'นายณัฐวัฒน์    สกุลวงศ์วณิชย์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (249, '249', '6145694', NULL, 'นายสุรวุฒิ    ศรีมงคล', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (250, '250', '6145737', NULL, 'นายกานต์ชนก    ธีระอกนิษฐ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (251, '251', '6145739', NULL, 'นายกุลภัสสร์    เกาะแก้ว', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (252, '252', '6145742', NULL, 'นายเจษฎา    ผาไธสงค์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (253, '253', '6145756', NULL, 'นางสาวคณิตา    วรพันธ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (254, '254', '6145764', NULL, 'นางสาวดนิตา    เที่ยงธรรม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (255, '255', '6145768', NULL, 'นางสาวนัทธมน    ตรงวัฒนาวุฒิ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (256, '256', '6145798', NULL, 'นายภานุวัฒน์    โทพล', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (257, '257', '6145813', NULL, 'นางสาวธนัชพร    ฤกษ์ฉวี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (258, '258', '6145820', NULL, 'นางสาวบุญญานุช    ไชยวงศ์คต', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (259, '259', '6145842', NULL, 'นางสาวธาริณี    นนตะแสน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (260, '260', '6145862', NULL, 'นางสาวธัญญพร    ทองบัว', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (261, '261', '6145870', NULL, 'นางสาวพิชญาภา    หนาดคำ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (262, '262', '6145877', NULL, 'นางสาววันวิสาข์    กงลีมา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (263, '263', '6145891', NULL, 'นายณัฐวัตร    แสงคำ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (264, '264', '6145898', NULL, 'นายธีรภัทร    บุตตะโคตร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (265, '265', '6145931', NULL, 'นางสาววิชุรดา    ปานพรม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (266, '266', '6145932', NULL, 'นางสาวศศิวิมล    สมสายผล', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (267, '267', '6145977', NULL, 'นางสาวพิชชาภา    รอบรู้', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (268, '268', '6145983', NULL, 'นางสาวสุพิชญา    โนยราช', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (269, '269', '6145984', NULL, 'นางสาวสุภิศตา    พลราชม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (270, '270', '6146025', NULL, 'นางสาวเบญจพร    บุญชู', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (271, '271', '6146030', NULL, 'นางสาวภาวินี    ทะชัยวงศ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (272, '272', '6146035', NULL, 'นางสาวอภิญญา    ไชยศรี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (273, '273', '6146070', NULL, 'นางสาวณิชมน    คนชุม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (274, '274', '6146075', NULL, 'นางสาวมินตรา    ฮมแสน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (275, '275', '6146083', NULL, 'นางสาวอรปรียา    หาญจำปา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (276, '276', '6146093', NULL, 'นายธงไทย    ศิริขันธ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (277, '277', '6146094', NULL, 'นายธนทัต    คำพันธ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (278, '278', '6146102', NULL, 'นายภัทรภณ    จวนสาง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (279, '279', '6146114', NULL, 'นางสาวกมลวรรณ    จันทรังษี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (280, '280', '6146134', NULL, 'นางสาวอมรพรรณ    ฤทธิ์ตะเกตุ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (281, '281', '6146175', NULL, 'นายวุฒิกร    พูนปริญญา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (282, '282', '6147266', NULL, 'นางสาวมุกดา    กิจดี', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (283, '283', '6147268', NULL, 'นายณัฐพนธ์    ทัศนพงษ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (284, '284', '6148873', NULL, 'นายกิตติศักดิ์    เรืองสวัสดิ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (285, '285', '6148876', NULL, 'นายนพณัฐ    เทศรุ่งเรือง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (286, '286', '6148877', NULL, 'นายปิยทัศน์    แสนศิลา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (287, '287', '6148878', NULL, 'นายภานุกร    ไชยรา', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (288, '288', '6148880', NULL, 'นายสิรภพ    ยางธิสาร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (289, '289', '6148881', NULL, 'นางสาวจริยา    จันทบาล', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (290, '290', '6148882', NULL, 'นางสาวจุลฑาทิพย์    ชะนะแสบง', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (291, '291', '6148883', NULL, 'นางสาวฐิติรัตน์    ใครบุตร', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (292, '292', '6148884', NULL, 'นางสาวสิรินาฎ    เชื้อตาหมื่น', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (293, '293', '6148885', NULL, 'นางสาวหนึ่งฤทัย    มีไกรราช', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (294, '294', '6148965', NULL, 'นางสาวสุวิภา    ทักษ์สิน', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (295, '295', '6149041', NULL, 'นางสาวกชกร    ผลเอี่ยม', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (296, '296', '6145657', NULL, 'นายอนิวรรต    มาลีลัย', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (297, '297', '6145661', NULL, 'นางสาวกิริติยา    นันทะวงค์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (298, '298', '6145678', NULL, 'นางสาวศิริลักษณ์    อุปพงษ์', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (299, '299', '6145725', NULL, 'นางสาววาสินี    ทันอินทรอาจ', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (300, '300', '6145726', NULL, 'นางสาวแวววัน    กลิ่นไสว', 1, 1, 1);
INSERT INTO `tb_his_data` VALUES (301, '301', '6145747', NULL, 'นายพงศธร    พลราชม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (302, '302', '6145777', NULL, 'นางสาวศุภาพิชญ์    ตรงวัฒนาวุฒิ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (303, '303', '6145785', NULL, 'นางสาวอุมาพร    เทพประสิทธิ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (304, '304', '6145816', NULL, 'นางสาวนงนภัส    สุทธิชัยตระกูล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (305, '305', '6145829', NULL, 'นางสาวโสภาพรรณ    ตรีถาวรพิศาล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (306, '306', '6145867', NULL, 'นางสาวบุษบง    วงค์สีดา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (307, '307', '6145883', NULL, 'นางสาวอรญา    สูงภิไลย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (308, '308', '6145892', NULL, 'นายทฤษฎี    เจริญชัย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (309, '309', '6145905', NULL, 'นายวชิรวิทย์    เจริญพร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (310, '310', '6145920', NULL, 'นางสาวเนตรอัปสร    สุหญ้านาง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (311, '311', '6145923', NULL, 'นางสาวปาริดา    วงศ์ษาสิทธิ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (312, '312', '6145927', NULL, 'นางสาวพิชชาอร    รักษา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (313, '313', '6145951', NULL, 'นายรัชชานนท์    ศิริสานต์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (314, '314', '6145975', NULL, 'นางสาวปรารถนา    ไปแดน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (315, '315', '6145980', NULL, 'นางสาววนาลี    พลวงศ์ษา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (316, '316', '6146023', NULL, 'นางสาวนนทินี    จันทะจร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (317, '317', '6146049', NULL, 'นายนคเรศ    คำภูแสน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (318, '318', '6146055', NULL, 'นายวัชรวีร์    ประพันธ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (319, '319', '6146065', NULL, 'นางสาวกิติยาภรณ์    อรรถวิลัย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (320, '320', '6146079', NULL, 'นางสาววิภาพร    ธ.น.โม้', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (321, '321', '6146081', NULL, 'นางสาวอภิญญา    ยาโน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (322, '322', '6146104', NULL, 'นายรัฐธรรม    แสงผา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (323, '323', '6146107', NULL, 'นายวิศรุต    ศรีนัครินทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (324, '324', '6146179', NULL, 'นางสาวโฉมศิริณัฐ    โคตรมิตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (325, '325', '6146187', NULL, 'นางสาวพิมพ์ชนก    เจริญรส', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (326, '326', '6148886', NULL, 'นายธนากร    นามมุงคุณ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (327, '327', '6148887', NULL, 'นายธนาวุฒิ    ทาโยธี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (328, '328', '6148888', NULL, 'นายธีรวัฒน์    เหมะธุลิน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (329, '329', '6148889', NULL, 'นายภควัต    ยาทองไชย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (330, '330', '6148890', NULL, 'นายภานุพงษ์    แสนศรี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (331, '331', '6148891', NULL, 'นายวรพงษ์    หินดำ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (332, '332', '6148892', NULL, 'นายสหรัถ    สารเนตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (333, '333', '6148893', NULL, 'นางสาวกุลนิษฐ์ตา    แก้วปัญญา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (334, '334', '6148894', NULL, 'นางสาวจิตรวรรณ    วะชุม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (335, '335', '6148895', NULL, 'นางสาวเจนจิรา    บุญธรรม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (336, '336', '6148896', NULL, 'นางสาวณัฐวรรณ    นาโควงค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (337, '337', '6148897', NULL, 'นางสาวนัทธ์ชนัน    ชาแสน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (338, '338', '6148898', NULL, 'นางสาวพัชรพร    โกษาแสง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (339, '339', '6148899', NULL, 'นางสาวพิชชภรณ์    เครือบุดดี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (340, '340', '6148901', NULL, 'นางสาวโยษิตา    ทำเลดี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (341, '341', '6148902', NULL, 'นางสาววรรษกานต์    สัตตาคม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (342, '342', '6148903', NULL, 'นางสาวศิริลักษณ์    พันธุโคตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (343, '343', '6149043', NULL, 'นายพัชรดล    เลิศไธสงค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (344, '344', '6149880', NULL, 'นางสาวณัฐพร    จิตมาตย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (345, '345', '6149883', NULL, 'นางสาวนิมินันท์    วรวิรุฬห์วงศ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (346, '346', '6150708', NULL, 'นายปฐมพร    ประเสริฐสังข์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (347, '347', '6145643', NULL, 'นายชัยวิรัฐ    สุดตาสอน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (348, '348', '6145655', NULL, 'นายศิวกร    ไชยปัญหา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (349, '349', '6145730', NULL, 'นางสาวศิริวรรณ    ไชยตะมาตย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (350, '350', '6145738', NULL, 'นายการุณย์    เพชรสังข์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (351, '351', '6145782', NULL, 'นางสาวอทิติยา    นารีแพงสี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (352, '352', '6145788', NULL, 'นายคณิศร    จำปาแก้ว', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (353, '353', '6145799', NULL, 'นายภานุสรณ์    คำภูแสน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (354, '354', '6145802', NULL, 'นายสราวุธ    กิตติชัยวัฒนา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (355, '355', '6145809', NULL, 'นางสาวณัฏฐา    ฉัตรทอง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (356, '356', '6145812', NULL, 'นางสาวณัฐสุดา    ไชยชมภู', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (357, '357', '6145830', NULL, 'นางสาวอนันตญา    บัวศรี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (358, '358', '6145852', NULL, 'นางสาวเจนนิสา    อุปสิทธิ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (359, '359', '6145873', NULL, 'นางสาวรัตติยา    ทองชัย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (360, '360', '6145882', NULL, 'นางสาวอรจิรา    รัตนพจน์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (361, '361', '6145919', NULL, 'นางสาวนิลาวัลย์    ฤทธิ์ฤาชัย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (362, '362', '6145924', NULL, 'นางสาวพนิดา    มูลประสาร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (363, '363', '6145938', NULL, 'นายเกริกไกร    ปะละคะ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (364, '364', '6145948', NULL, 'นายนราทร    ประกิ่ง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (365, '365', '6145973', NULL, 'นางสาวธนัญญา    พิมขันธุ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (366, '366', '6145978', NULL, 'นางสาวมานิกา    ไขประภาย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (367, '367', '6145981', NULL, 'นางสาวศุภสุดา    พันธ์ดี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (368, '368', '6145993', NULL, 'นายณัฐนนท์    วงษ์ตาแพง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (369, '369', '6145995', NULL, 'นายณัฐพัชร์    เตชโรจนกิตติ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (370, '370', '6146003', NULL, 'นายพรพชร    พรมยศ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (371, '371', '6146027', NULL, 'นางสาวปวริศา    พลหาญ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (372, '372', '6146066', NULL, 'นางสาวจุราภรณ์    กลิ่นน้อย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (373, '373', '6146087', NULL, 'นายกิตติพงษ์    กิตติศรีรัตนกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (374, '374', '6146095', NULL, 'นายธิตินันท์    โนยราช', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (375, '375', '6146111', NULL, 'นายสิรภพ    ศรีสร้อย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (376, '376', '6146115', NULL, 'นางสาวกฤติกา    มะโนชัย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (377, '377', '6146124', NULL, 'นางสาวนุชนารถ    แก้วชุมภู', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (378, '378', '6146185', NULL, 'นางสาวเบญจวรรณ    ณะใจ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (379, '379', '6147284', NULL, 'นางสาวสาธิตา    บัณฑิตนวศาสตร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (380, '380', '6148904', NULL, 'นายชัชวาลย์    พานเงิน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (381, '381', '6148905', NULL, 'นายพงษ์สันต์    มาตยาคุณ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (382, '382', '6148907', NULL, 'นายสยามรัฐ    ผงจำปา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (383, '383', '6148908', NULL, 'นายอภิเดช    สังข์สี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (384, '384', '6148909', NULL, 'นางสาวกชกร    การุญ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (385, '385', '6148910', NULL, 'นางสาวกานต์มณี    สุนสิน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (386, '386', '6148911', NULL, 'นางสาวจิราภา    กุลวงษ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (387, '387', '6148912', NULL, 'นางสาวธิติญาพร    ขันธ์ละ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (388, '388', '6148913', NULL, 'นางสาวนาถนฤมล    เปี่ยมทองคำ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (389, '389', '6148914', NULL, 'นางสาวบุญวรา    เจริญพืช', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (390, '390', '6148915', NULL, 'นางสาวปภาวดี    ติยะบุตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (391, '391', '6148916', NULL, 'นางสาวพรายดาว    พุทธจง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (392, '392', '6148918', NULL, 'นางสาวมาริษา    คอมแพงจันทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (393, '393', '6148919', NULL, 'นางสาวรัตนาพร    คำภาพันธ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (394, '394', '6148920', NULL, 'นางสาวศิริวิภา    ฝ่ายทะแสง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (395, '395', '6149045', NULL, 'นายคณุตม์    การุญ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (396, '396', '6149920', NULL, 'นายเพชรนคร    กลางสาทร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (397, '397', '6145634', NULL, 'นางสาวอภิชญา    งอยภูธร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (398, '398', '6145637', NULL, 'นายกิตตินันท์    เรืองสวัสดิ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (399, '399', '6145652', NULL, 'นายเมธา    กิ่งโก้', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (400, '400', '6145663', NULL, 'นางสาวชลิตา    ห่อหุ้มดี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (401, '401', '6145670', NULL, 'นางสาวน้ำหนึ่ง    คำชมภู', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (402, '402', '6145704', NULL, 'นางสาวชญานิษฐ์    โกษาแสง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (403, '403', '6145705', NULL, 'นางสาวชมพูนุช    ไชยพิทย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (404, '404', '6145715', NULL, 'นางสาวพรรษพร    เพิ่มผล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (405, '405', '6145763', NULL, 'นางสาวธัญทิพย์    ไชยขันธุ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (406, '406', '6145772', NULL, 'นางสาวพชรกมล    อินทนนท์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (407, '407', '6145784', NULL, 'นางสาวอรนุช    พันธ์ศรี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (408, '408', '6145794', NULL, 'นายนันทวุฒิ    ตระกูลแสน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (409, '409', '6145805', NULL, 'นางสาวแก้วรัดเกล้า    กลยนี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (410, '410', '6145811', NULL, 'นางสาวณัฐริกา    ผายเงิน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (411, '411', '6145814', NULL, 'นางสาวธนากาญจน์    ศรีนา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (412, '412', '6145860', NULL, 'นางสาวณีรนุช    ตาพระ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (413, '413', '6145876', NULL, 'นางสาววัชราภรณ์    ชวนาพิทักษ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (414, '414', '6145878', NULL, 'นางสาวศุภสุดา    กองพันธ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (415, '415', '6145897', NULL, 'นายธีรภัทร    เต็งจารึกชัย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (416, '416', '6145911', NULL, 'นายศิวพล    ไชยฮัง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (417, '417', '6145939', NULL, 'นายเกียรติภูมิ    สิงห์คำมา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (418, '418', '6145960', NULL, 'นายสหัสวรรษ    ดงบัง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (419, '419', '6146011', NULL, 'นายสรวิศ    เบ็ญเจิด', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (420, '420', '6146022', NULL, 'นางสาวธัญญาภรณ์    งิ้วไชยราช', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (421, '421', '6146026', NULL, 'นางสาวปณิตา    ไชยมงคล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (422, '422', '6146052', NULL, 'นายภาณุวัฒน์    โมละดา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (423, '423', '6146053', NULL, 'นายภีมเดช    เหมะธุรินทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (424, '424', '6146054', NULL, 'นายวรวุฒิ    นาคราช', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (425, '425', '6146092', NULL, 'นายตรีพล    หาญสนาม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (426, '426', '6146113', NULL, 'นางสาวกนกวรรณ    คำเพชร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (427, '427', '6146125', NULL, 'นางสาวปณิตา    ผันกลาง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (428, '428', '6146131', NULL, 'นางสาววาสนา    ไกยะฝ่าย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (429, '429', '6146430', NULL, 'นางสาวธนภรณ์    ภูลายยาว', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (430, '430', '6147260', NULL, 'นายหานสกล    พลข้อ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (431, '431', '6148143', NULL, 'นางสาวธีรนุช    อภิชาติหิรัญ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (432, '432', '6148154', NULL, 'นายกีรติ    นามโยธา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (433, '433', '6148922', NULL, 'นายณัชพนธ์    สุขรี่', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (434, '434', '6148923', NULL, 'นายธราธิป    ครุดอุทา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (435, '435', '6148924', NULL, 'นายพีรณัฐ    กุลดิลก', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (436, '436', '6148925', NULL, 'นายวรท    จันทรเสนา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (437, '437', '6148926', NULL, 'นางสาวจารุนันท์    มีบุตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (438, '438', '6148927', NULL, 'นางสาวจารุวรรณ    พงษ์สิงห์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (439, '439', '6148928', NULL, 'นางสาวจุฑามาศ    ปุณริบูรณ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (440, '440', '6148929', NULL, 'นางสาวณัฏฐริกา    ใครบุตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (441, '441', '6148930', NULL, 'นางสาวปรมาส    วงศ์คำจันทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (442, '442', '6148931', NULL, 'นางสาวรพีพรรณ    ชั้นน้อย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (443, '443', '6148932', NULL, 'นางสาววรรณนิสา    นาลงพรม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (444, '444', '6148933', NULL, 'นางสาววิลาสินี    บุดดีด้วง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (445, '445', '6148934', NULL, 'นางสาวศุกัญญา    ขำคมเขต', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (446, '446', '6149881', NULL, 'นางสาวภัทรวดี    วชิรศิริกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (447, '447', '6144961', NULL, 'นายธนายุทธ    คำกอง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (448, '448', '6145640', NULL, 'นายจิตติพล    สิงห์คำมา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (449, '449', '6145672', NULL, 'นางสาวพัชรี    นรสาร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (450, '450', '6145685', NULL, 'นางสาวอลิษา    พิศสุวรรณ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (451, '451', '6145714', NULL, 'นางสาวพรพิมล    หว่างพัฒน์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (452, '452', '6145735', NULL, 'นางสาวอารยา    บุตรวงศ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (453, '453', '6145740', NULL, 'นายเกศฎา    ศรีประทุม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (454, '454', '6145748', NULL, 'นายพีรดนย์    พงษ์ไพบูลย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (455, '455', '6145755', NULL, 'นางสาวกมลลักษณ์    มาตราช', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (456, '456', '6145770', NULL, 'นางสาวปทิตตา    แก้วหล่อ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (457, '457', '6145779', NULL, 'นางสาวสุพิชญา    ลามคำ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (458, '458', '6145786', NULL, 'นายกฤษณะ    จักเครือ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (459, '459', '6145806', NULL, 'นางสาวจิดาภา    มาตะรักษ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (460, '460', '6145824', NULL, 'นางสาวพรเทวัญ    ที่ภักดี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (461, '461', '6145836', NULL, 'นายจารุวัฒน์    สอนเกิด', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (462, '462', '6145838', NULL, 'นายฐิติวัสส์    โพธิ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (463, '463', '6145840', NULL, 'นายธนชัย    แตงรัตนา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (464, '464', '6145850', NULL, 'นางสาวกุลจิรา    คำผิว', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (465, '465', '6145869', NULL, 'นางสาวปิยะนารถ    พรหมสาขา ณ สกลนคร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (466, '466', '6145901', NULL, 'นายปัณณธร    ผาใต้', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (467, '467', '6145916', NULL, 'นางสาวกิตติมา    เนื่องสิทธิ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (468, '468', '6145926', NULL, 'นางสาวพาขวัญ    น้อยใจบุญ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (469, '469', '6145942', NULL, 'นายณัฐพิเชษฐ์    ปัญญา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (470, '470', '6145949', NULL, 'นางสาวภาณุมาศ    ทานาลาด', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (471, '471', '6145972', NULL, 'นางสาวณัฐริกา    ฤทธิ์สุวรรณ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (472, '472', '6145976', NULL, 'นางสาวปรารถนา    วัฒนะสุระ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (473, '473', '6145986', NULL, 'นายกนกพล    นามโยธา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (474, '474', '6146028', NULL, 'นางสาวภควรรณ    ศรีวงษา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (475, '475', '6146029', NULL, 'นางสาวภัทรสุดา    ธนาไสย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (476, '476', '6146036', NULL, 'นายกิจภิวัฒน์    จุดดา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (477, '477', '6146041', NULL, 'นายชัยวัฒน์    วชิรศิริกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (478, '478', '6146058', NULL, 'นายศักย์ศรณ์    ส่งเสริม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (479, '479', '6146105', NULL, 'นายวนวิช    ตั้งเจริญสกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (480, '480', '6146135', NULL, 'นางสาวไอศวรรย์    ชะเอม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (481, '481', '6146436', NULL, 'นางสาวสุวรี    บุริพา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (482, '482', '6147267', NULL, 'นายปรัธนา    ปาปะขา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (483, '483', '6148935', NULL, 'นายจิรัสย์    ตีรสวัสดิชัย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (484, '484', '6148936', NULL, 'นายณัฐพงศ์    แสนสุริวงค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (485, '485', '6148937', NULL, 'นายภัทรนันท์    แสงสุวรรณ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (486, '486', '6148938', NULL, 'นายวุฒิชัย    นอนิล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (487, '487', '6148939', NULL, 'นายสกล    ศรีสวัสดิ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (488, '488', '6148940', NULL, 'นายอัจฉริยะ    บงบุตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (489, '489', '6148941', NULL, 'นางสาวคชาภรณ์    ผลจันทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (490, '490', '6148942', NULL, 'นางสาวทิพวรรณ    รัตนะ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (491, '491', '6148943', NULL, 'นางสาวนริศรา    ศรีพลน้อย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (492, '492', '6148944', NULL, 'นางสาวนิศาชล    ศรีพั้ว', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (493, '493', '6148945', NULL, 'นางสาวปานชีวา    กุลีสูงเนิน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (494, '494', '6148946', NULL, 'นางสาวภัชรินทร์    ไชยโคตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (495, '495', '6148948', NULL, 'นางสาววริศรา    กรโสภา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (496, '496', '6148949', NULL, 'นางสาววิลาสินี    ภูดี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (497, '497', '6148950', NULL, 'นางสาวอภิญญา    ยมสีดำ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (498, '498', '6145690', NULL, 'นายพันธการ    ฮังชัย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (499, '499', '6145693', NULL, 'นายสหัสวรรษ    ดำรงค์กิจ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (500, '500', '6145697', NULL, 'นายอัครพล    พจนา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (501, '501', '6145758', NULL, 'นางสาวจิราพร    โสภา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (502, '502', '6145767', NULL, 'นางสาวนนทิชา    ศรีวงษ์ลัก', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (503, '503', '6145769', NULL, 'นางสาวเนตรชนก    ชุ่มผึ้ง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (504, '504', '6145783', NULL, 'นางสาวอมิตา    โรมรัมย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (505, '505', '6145815', NULL, 'นางสาวธันยพร    ลีมงคล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (506, '506', '6145885', NULL, 'นางสาวอัญชุลี    อนันตภูมิ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (507, '507', '6145902', NULL, 'นายพีรพล    นายางเจริญ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (508, '508', '6145946', NULL, 'นายธนวัฒน์    บุษมงคล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (509, '509', '6145947', NULL, 'นายธวัชชัย    ชาวเวียง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (510, '510', '6145953', NULL, 'นายวิชยุตม์    จารีรัตน์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (511, '511', '6145956', NULL, 'นายศิริวัฒน์    ไพเรืองโสม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (512, '512', '6145965', NULL, 'นางสาวกมลชนก    ทิพม่อม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (513, '513', '6145982', NULL, 'นางสาวสิปปปวีร์    ไชยบุบผา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (514, '514', '6145990', NULL, 'นายจิรภัทร    แสงจันทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (515, '515', '6145992', NULL, 'นายชัชวาลย์    เศษมาตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (516, '516', '6145994', NULL, 'นายณัฐพงศ์    บุตรงาม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (517, '517', '6145997', NULL, 'นายธนกร    อินทรพาณิชย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (518, '518', '6145998', NULL, 'นายนรบดี    สุภาผล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (519, '519', '6146007', NULL, 'นายภัทรพล    พลราษฎร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (520, '520', '6146009', NULL, 'นายวีรยุทธ    จันทร์น้อย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (521, '521', '6146010', NULL, 'นายสรนันท์    เลิศศรี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (522, '522', '6146012', NULL, 'นายสหัสวรรษ    หิริโกกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (523, '523', '6146013', NULL, 'นายอัศวิน    กลางนนท์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (524, '524', '6146019', NULL, 'นางสาวณัฐิริณี    ศิริฟอง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (525, '525', '6146042', NULL, 'นายชาคริต    ศิริจันทพันธ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (526, '526', '6146044', NULL, 'นายธนวัฒน์    จันใด', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (527, '527', '6146045', NULL, 'นายธนวัฒน์    ศรีพานิชย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (528, '528', '6146048', NULL, 'นายธีรภัทร    บรรณาลัย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (529, '529', '6146072', NULL, 'นางสาวนัฏฐิกา    ไตรวงศ์ย้อย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (530, '530', '6146074', NULL, 'นางสาวปรายฟ้า    อินธิแสง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (531, '531', '6146078', NULL, 'นางสาววาสนา    ศิริสวัสดิ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (532, '532', '6146080', NULL, 'นางสาวศุฑาทิพย์    สุขามานพ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (533, '533', '6146084', NULL, 'นางสาวอัจฉราภรณ์    แสงจันทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (534, '534', '6146117', NULL, 'นางสาวชุติกาณฑ์    นาเชียงใต้', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (535, '535', '6146126', NULL, 'นางสาวปิยธิดา    ผลประสาท', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (536, '536', '6147261', NULL, 'นางสาวกันตินันท์    สัญญะงาม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (537, '537', '6148867', NULL, 'นางสาวพัชริดา    สัพโส', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (538, '538', '6148875', NULL, 'นายณัฐภัทร    มาตราช', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (539, '539', '6149921', NULL, 'นางสาวชาริกา    มาลาศิริ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (540, '540', '6145701', NULL, 'นางสาวธมกร    พ่อหลอน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (541, '541', '6145887', NULL, 'นายจตุฤทธิ์    มุงคุณ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (542, '542', '6145890', NULL, 'นายชลากร    นวลมณี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (543, '543', '6145894', NULL, 'นายธนบูรณ์    สุธรรม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (544, '544', '6145909', NULL, 'นายศักดิ์สิทธิ์    ขันทีท้าว', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (545, '545', '6145914', NULL, 'นางสาวกนกเรขา    พรหมสาขา ณ สกลนคร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (546, '546', '6145934', NULL, 'นางสาวสุชัญญา    เสนาไชย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (547, '547', '6145940', NULL, 'นายคณาวุฒิ    กัลยา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (548, '548', '6145944', NULL, 'นายทศวรรษ    แก่นประชา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (549, '549', '6145955', NULL, 'นายวุฒินันท์    เดชสุวรรณ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (550, '550', '6145962', NULL, 'นายอภิภูมิ    บุญบุตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (551, '551', '6145963', NULL, 'นายอรรถพล    แว่นเตื่อรอง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (552, '552', '6145964', NULL, 'นายอาธิกรณ์    อุปพงษ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (553, '553', '6146001', NULL, 'นายนิติวัฒน์    กันเสนา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (554, '554', '6146033', NULL, 'นางสาวสุธิดา    วงศ์อุดม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (555, '555', '6146043', NULL, 'นายธนพงศ์    จันทหลุย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (556, '556', '6146050', NULL, 'นายนิติวุฒิ    คำชมภู', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (557, '557', '6146063', NULL, 'นางสาวกมลพรรณ    อนันต์ลักษณ์การ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (558, '558', '6146099', NULL, 'นายพิสิฐ    อรุณเกียรติก้อง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (559, '559', '6146119', NULL, 'นางสาวณภัทร    กรองกาญจนสิริ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (560, '560', '6146128', NULL, 'นางสาวมณินทร    งามขำ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (561, '561', '6146431', NULL, 'นางสาวนันท์นภัส    ภูแพง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (562, '562', '6146442', NULL, 'นายจีรวัฒน์    แสนราช', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (563, '563', '6147271', NULL, 'นางสาวจิรนันท์    นนท์สะเกตุ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (564, '564', '6148145', NULL, 'นางสาวปาริชาต    วัฒนสุชาติ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (565, '565', '6148921', NULL, 'นางสาวอารียา    อริยะ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (566, '566', '6148947', NULL, 'นางสาวรวิษฎา    อินทรรักษ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (567, '567', '6148951', NULL, 'นายรัฐวุฒิ    สาขามุละ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (568, '568', '6148952', NULL, 'นายสุริยะภัทร    ศรีอำคา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (569, '569', '6148953', NULL, 'นายอิสรานนต์    สว่างวงค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (570, '570', '6148954', NULL, 'นางสาวกานดา    โพธิ์ตันคำ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (571, '571', '6148955', NULL, 'นางสาวจุฑามาศ    เจริญนุ่ม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (572, '572', '6148956', NULL, 'นางสาวจุฬาลักษณ์    เอกจักรแก้ว', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (573, '573', '6148957', NULL, 'นางสาวชญานี    ฤทธิธรรม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (574, '574', '6148958', NULL, 'นางสาวพชรดา    ยี่สารพัฒน์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (575, '575', '6148959', NULL, 'นางสาวอุบลวรรณ    เข็มจันทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (576, '576', '6149884', NULL, 'นายธวัชชัย    จันทร์โสภา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (577, '577', '6145595', NULL, 'นายดลสุข    ภูมิประสิทธิ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (578, '578', '6145599', NULL, 'นายพศุตม์    สุระมรรคา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (579, '579', '6145718', NULL, 'นางสาวภัทราวดี    อินธิแสง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (580, '580', '6145721', NULL, 'นางสาวเมรีนา    ชาน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (581, '581', '6145736', NULL, 'นายกานต์    โพธิดอกไม้', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (582, '582', '6145787', NULL, 'นายจารุพัฒน์    ขันธพัฒน์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (583, '583', '6145789', NULL, 'นายณัฐพงษ์    เต่าทอง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (584, '584', '6145791', NULL, 'นายธรรมนูญ    งอยกุดจิก', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (585, '585', '6145808', NULL, 'นางสาวชลันดา    เข็มศรี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (586, '586', '6145831', NULL, 'นางสาวอมลณัฐ    บุนนาค', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (587, '587', '6145851', NULL, 'นางสาวจีรนันท์    ปิดรัมย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (588, '588', '6145864', NULL, 'นางสาวนรีกานต์    เฒ่าอุดม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (589, '589', '6145879', NULL, 'นางสาวสโรชา    ผานะวงค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (590, '590', '6145888', NULL, 'นายเจษฎา    ขันทอง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (591, '591', '6145899', NULL, 'นายธีรภัทร    อภิวัฒน์ชัชวาลย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (592, '592', '6145917', NULL, 'นางสาวณัฐธิดา    สาระคร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (593, '593', '6145935', NULL, 'นางสาวสุดารัตน์    จันทร์ปุ่ม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (594, '594', '6145968', NULL, 'นางสาวชุติมา    สิงห์แจ่ม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (595, '595', '6145987', NULL, 'นายกันตินันท์    ตันวัฒนะพงษ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (596, '596', '6145988', NULL, 'นายกิตติพัฒน์    ตั้งไพบูลย์กิจ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (597, '597', '6145991', NULL, 'นายชลสิทธิ์    กาติวงค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (598, '598', '6145999', NULL, 'นายนฤชิต    สุภา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (599, '599', '6146018', NULL, 'นางสาวณัฐิชา    พินัส', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (600, '600', '6146020', NULL, 'นางสาวดลหทัย    ศรีหนองห้าง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (601, '601', '6146021', NULL, 'นางสาวดุสิตา    เภารังค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (602, '602', '6146032', NULL, 'นางสาวสิริประภา    คึมยะราช', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (603, '603', '6146071', NULL, 'นางสาวธัญภรณ์    เสนีวงศ์ ณ อยุธยา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (604, '604', '6146077', NULL, 'นางสาววรินรำไพ    จันทร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (605, '605', '6146106', NULL, 'นายวัชญะ    ผึ้งเถื่อน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (606, '606', '6146129', NULL, 'นางสาวลัดดา    โพธิราช', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (607, '607', '6146130', NULL, 'นางสาววันวิสา    เชิดชู', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (608, '608', '6146132', NULL, 'นางสาววิชุดา    มุมบุญ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (609, '609', '6146184', NULL, 'นางสาวบุษบา    ฤทธิปะ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (610, '610', '6147290', NULL, 'นายประณต    ศรีวรกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (611, '611', '6148906', NULL, 'นายรัฐนันท์    วรดี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (612, '612', '6148960', NULL, 'นายณราศักดิ์    แสงวงค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (613, '613', '6148961', NULL, 'นายฤทธิเดช    กันฮะ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (614, '614', '6148962', NULL, 'นางสาวกนกวรรณ    หลินภู', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (615, '615', '6148963', NULL, 'นางสาวชาลิสา    บุญหลาย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (616, '616', '6145559', NULL, 'นางสาวกรณัฐ    ศรีลำไพ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (617, '617', '6145593', NULL, 'นายณภัทร    สุวรรณชัยรบ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (618, '618', '6145596', NULL, 'นายทัศนัย    นาทัน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (619, '619', '6145618', NULL, 'นางสาวปาริมา    ดำรงไทย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (620, '620', '6145702', NULL, 'นางสาวจุฬารัตน์    วงศ์งาม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (621, '621', '6145729', NULL, 'นางสาวศิรประภา    ดีมาก', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (622, '622', '6145749', NULL, 'นายเลิศสิน    รักษาแสง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (623, '623', '6145752', NULL, 'นายอดิสร    ทัศคร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (624, '624', '6145760', NULL, 'นางสาวชีวจิต    จำวัน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (625, '625', '6145761', NULL, 'นางสาวฐิติพร    โถชาลี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (626, '626', '6145766', NULL, 'นางสาวนงนภัส    ศรีจันแก้ว', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (627, '627', '6145774', NULL, 'นางสาวพรพินชญา    บุญทรง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (628, '628', '6145776', NULL, 'นางสาวพิมพ์วิภา    โวหารลึก', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (629, '629', '6145807', NULL, 'นางสาวฉัตรฑิณีย์    คำเมือง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (630, '630', '6145817', NULL, 'นางสาวนภสร    พองพรหม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (631, '631', '6145853', NULL, 'นางสาวชนิสรา    อินภูวา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (632, '632', '6145857', NULL, 'นางสาวณัฐธิดา    นกพรมพะเนา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (633, '633', '6145880', NULL, 'นางสาวนรีรัตน์    มาตยาคุณ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (634, '634', '6145913', NULL, 'นางสาวกชกร    พลเยี่ยม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (635, '635', '6145922', NULL, 'นางสาวปวีณ์สุดา    ลาดสุวรรณ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (636, '636', '6145928', NULL, 'นางสาวเฟื่องฟ้า    ทุพแหม่ง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (637, '637', '6145929', NULL, 'นางสาวยุพเรศ    แก้วศรีไตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (638, '638', '6145930', NULL, 'นางสาวละมัย    มั่นพลศรี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (639, '639', '6145933', NULL, 'นางสาวสมฤทัย    พิมพ์มีลาย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (640, '640', '6145969', NULL, 'นางสาวณัฐกาญจน์    รมฤทธา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (641, '641', '6145979', NULL, 'นางสาววทัญญุตา    ทิพย์เลิศ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (642, '642', '6146017', NULL, 'นางสาวจิรัฐญา    โต๊ะมีเลาะ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (643, '643', '6146038', NULL, 'นายจักรพงศ์    จิตรทรัพย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (644, '644', '6146056', NULL, 'นายวิทวัส    นครังสุ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (645, '645', '6146085', NULL, 'นางสาวอัญชิสา    จันกันทะ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (646, '646', '6146091', NULL, 'นายณัฐพงษ์    อุดมเดชาเวทย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (647, '647', '6146121', NULL, 'นางสาวณัฐวดี    ทัศคร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (648, '648', '6146435', NULL, 'นางสาวรินรดา    วงศ์พาณิชยกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (649, '649', '6147259', NULL, 'นางสาวชิดชนก    แก้วคำแจ้ง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (650, '650', '6147263', NULL, 'นางสาวญาณิศา    บุตรอำคา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (651, '651', '6148144', NULL, 'นางสาวพนมพร    บุตรเพ็ง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (652, '652', '6148966', NULL, 'นายณัฐพงศ์    เพ็ชร์รุ่ง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (653, '653', '6148969', NULL, 'นายรัฐพล    สุทธิอาจ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (654, '654', '6148970', NULL, 'นางสาวชณัณญา    สังเกตุดี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (655, '655', '6148971', NULL, 'นางสาวชนัญญา    สุจริต', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (656, '656', '6148972', NULL, 'นางสาวถาวรีย์    เหลื่อมศรี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (657, '657', '6148973', NULL, 'นางสาวละอองทิพย์    โทนแก้ว', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (658, '658', '6148974', NULL, 'นางสาววราภรณ์    นามแสง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (659, '659', '6144682', NULL, 'นางสาวพิมพ์ลดา    ศักดิ์พิศุทธิกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (660, '660', '6145537', NULL, 'นายเกื้อวรกุล    จันทราสา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (661, '661', '6145538', NULL, 'นายเขตต์ไท    ปรีชาฤทธิรงค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (662, '662', '6145540', NULL, 'นายธีรภัทร    วงศรีลา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (663, '663', '6145542', NULL, 'นายนันทวัฒน์    เตชะนินทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (664, '664', '6145550', NULL, 'นายสหสวรรษ    ขันธ์พัฒน์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (665, '665', '6145554', NULL, 'นางสาวกมลลักษณ์    คะษาวงค์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (666, '666', '6145569', NULL, 'นางสาวพรปวีณ์    วังหอม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (667, '667', '6145601', NULL, 'นายศุภณัฐ    เดชภูมี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (668, '668', '6145606', NULL, 'นางสาวกัญญาณัฐ    กาศลุน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (669, '669', '6145608', NULL, 'นางสาวชฎิลดา    เกียรติวนากร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (670, '670', '6145613', NULL, 'นางสาวนภสร    เกตุคล้าย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (671, '671', '6145615', NULL, 'นางสาวปฏิมาพร    แซ่ตั้ง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (672, '672', '6145624', NULL, 'นางสาวมิส์เรียม    โชว์ตระกูลทอง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (673, '673', '6145626', NULL, 'นางสาววราภรณ์    อุติลา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (674, '674', '6145630', NULL, 'นางสาวกชนัฑ    สถาพรธนากร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (675, '675', '6145801', NULL, 'นายรชต    ศรีนา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (676, '676', '6145875', NULL, 'นางสาววริศรา    สุนารี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (677, '677', '6146145', NULL, 'นายภูสกล    อุ่นคำ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (678, '678', '6146171', NULL, 'นายธรรมรงค์    ตาลประดิษฐ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (679, '679', '6146180', NULL, 'นางสาวชนกมณี    ใจบุญ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (680, '680', '6148838', NULL, 'นายปรินทร    กระแสโท', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (681, '681', '6148850', NULL, 'นางสาวศิรินันท์    ก้อนแพง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (682, '682', '6148975', NULL, 'นางสาวกฤติยาภรณ์    แก้วฝ่าย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (683, '683', '6148976', NULL, 'นางสาวเกล็ดดาว    ปัตพี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (684, '684', '6148977', NULL, 'นางสาวนฤมล    บุณรังศรี', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (685, '685', '6148978', NULL, 'นางสาวปวีณา    วงค์อินพ่อ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (686, '686', '6148979', NULL, 'นางสาววัจนภรณ์    อรรคพิน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (687, '687', '6148980', NULL, 'นางสาวสุทธิดา    คำพัน', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (688, '688', '6148981', NULL, 'นายอภิสิทธิ์    วงศ์จำปา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (689, '689', '6145633', NULL, 'นางสาวอธิชา    วงศ์ประทุม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (690, '690', '6145884', NULL, 'นางสาวอรวรรณ    กิตติดำเกิง', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (691, '691', '6146166', NULL, 'นายกรณ์ธนัฐ    วันวัฒน์สันติกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (692, '692', '6146168', NULL, 'นายฉัตรชัย    จรุงเกียรติสกล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (693, '693', '6146169', NULL, 'นายณัฐชนน    โสรินทร์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (694, '694', '6146173', NULL, 'นายวศิน    ศรีหล้า', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (695, '695', '6146178', NULL, 'นางสาวจิรภัทร์    วิริยะเจริญกิจ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (696, '696', '6146182', NULL, 'นางสาวธนัชพร    จงกลรัตน์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (697, '697', '6146183', NULL, 'นางสาวนัทธมน    กุศลาไสยานนท์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (698, '698', '6146186', NULL, 'นางสาวปทิตตา    มานะวิสุทธิ์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (699, '699', '6146190', NULL, 'นางสาวสิรภัทร    พรหมอุดม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (700, '700', '6146193', NULL, 'นางสาวสุรภา    เสมทรัพย์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (701, '701', '6148982', NULL, 'นางสาวกชกร    ซ้อนบุตร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (702, '702', '6148983', NULL, 'นางสาวกัญญารัตน์    สุวรรณชัยรบ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (703, '703', '6148984', NULL, 'นางสาวขวัญจิรา    อุ่นใจ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (704, '704', '6148985', NULL, 'นางสาวจิลมิกา    สิทธิกานต์', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (705, '705', '6148986', NULL, 'นางสาวชณิดา    พรหมจักร', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (706, '706', '6148987', NULL, 'นางสาวดาราพร    ตุ่ยไชย', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (707, '707', '6148988', NULL, 'นางสาวนรีกานต์    ทองคำชุม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (708, '708', '6148990', NULL, 'นางสาวนุสรา    จันทร์เขียว', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (709, '709', '6148991', NULL, 'นางสาวปราณัสมา    จันทร์ชนะ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (710, '710', '6148992', NULL, 'นางสาวปวันรัตน์    ฮิมปะลาด', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (711, '711', '6148993', NULL, 'นางสาวพรสวรรค์    มโนมัยกิจ', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (712, '712', '6148994', NULL, 'นางสาวเฟื่องฟ้า    สร้อยงาม', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (713, '713', '6148995', NULL, 'นางสาวศุภาศินีย์    สุขศิริภูวกุล', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (714, '714', '6148996', NULL, 'นางสาวสิริกร    วงษา', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (715, '715', '6148997', NULL, 'นางสาวสุภัคสินี    วงค์ตาขี่', 1, 1, 2);
INSERT INTO `tb_his_data` VALUES (716, '716', '6148998', NULL, 'นางสาวอารียา    จิตวิขาม', 1, 1, 2);

-- ----------------------------
-- Table structure for tb_pt_visit_type
-- ----------------------------
DROP TABLE IF EXISTS `tb_pt_visit_type`;
CREATE TABLE `tb_pt_visit_type`  (
  `pt_visit_type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสประเภท',
  `pt_visit_type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ชื่อประเภท',
  `pt_visit_type_prefix` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ตัวอักษร/ตัวเลข นำหน้าคิว',
  `pt_visit_type_digit` int(11) NOT NULL COMMENT 'จำนวนหลักหมายเลขคิว',
  PRIMARY KEY (`pt_visit_type_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 5 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_pt_visit_type
-- ----------------------------
INSERT INTO `tb_pt_visit_type` VALUES (1, 'ผู้ป่วยนัดหมาย', 'A', 3);
INSERT INTO `tb_pt_visit_type` VALUES (2, 'ผู้ป่วยไม่นัดหมาย', 'B', 3);

-- ----------------------------
-- Table structure for tb_qtrans
-- ----------------------------
DROP TABLE IF EXISTS `tb_qtrans`;
CREATE TABLE `tb_qtrans`  (
  `ids` int(11) NOT NULL AUTO_INCREMENT,
  `q_ids` int(11) NULL DEFAULT NULL,
  `service_sec_id` int(11) NULL DEFAULT NULL COMMENT 'รหัสแผนก',
  `counter_service_id` int(11) NULL DEFAULT NULL COMMENT 'ช่องบริการ/ห้อง',
  `doctor_id` int(11) NULL DEFAULT NULL,
  `checkin_date` datetime(0) NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP(0) COMMENT 'เวลาลงทะเบียนแผนก',
  `checkout_date` datetime(0) NULL DEFAULT NULL COMMENT 'เวลาออกแผนก',
  `service_status_id` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL,
  `updated_at` datetime(0) NULL DEFAULT NULL,
  `created_by` int(11) NULL DEFAULT NULL,
  `updated_by` int(11) NULL DEFAULT NULL,
  PRIMARY KEY (`ids`) USING BTREE,
  INDEX `q_ids`(`q_ids`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Fixed;

-- ----------------------------
-- Table structure for tb_quequ
-- ----------------------------
DROP TABLE IF EXISTS `tb_quequ`;
CREATE TABLE `tb_quequ`  (
  `q_ids` int(11) NOT NULL AUTO_INCREMENT COMMENT 'running',
  `q_num` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'หมายเลขQ',
  `q_timestp` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่ออกQ',
  `q_status_id` int(11) NULL DEFAULT NULL COMMENT 'สถานะQ',
  `pt_id` int(11) NULL DEFAULT NULL,
  `q_vn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'Visit number ของผู้ป่วย',
  `q_hn` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'หมายเลข HN ผู้ป่วย',
  `pt_name` varchar(200) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL COMMENT 'ชื่อผู้ป่วย',
  `pt_visit_type_id` int(11) NULL DEFAULT NULL COMMENT 'ประเภท',
  `pt_appoint_sec_id` int(11) NULL DEFAULT NULL COMMENT 'แผนกที่นัดหมาย',
  `doctor_id` int(11) NULL DEFAULT NULL,
  `created_at` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่บันทึก',
  `updated_at` datetime(0) NULL DEFAULT NULL COMMENT 'วันที่แก้ไข',
  PRIMARY KEY (`q_ids`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 49 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_quequ
-- ----------------------------
INSERT INTO `tb_quequ` VALUES (6, 'A001', '2018-02-12 20:13:51', NULL, NULL, '14', '6145579', 'นางสาววิชญาดา    โยตะสี', 2, NULL, NULL, '2018-02-12 20:13:51', '2018-02-12 20:13:51');
INSERT INTO `tb_quequ` VALUES (7, 'A002', '2018-02-12 20:14:06', NULL, NULL, '4', '6145547', 'นายวัชรินทร์    เวชกามา', 2, NULL, NULL, '2018-02-12 20:14:06', '2018-02-12 20:14:06');
INSERT INTO `tb_quequ` VALUES (8, 'A003', '2018-02-12 22:01:19', NULL, NULL, '3', '6145541', 'นายนพรุจ    หาริชัย', 2, NULL, NULL, '2018-02-12 22:01:19', '2018-02-12 22:01:19');
INSERT INTO `tb_quequ` VALUES (9, 'A004', '2018-02-12 22:16:15', NULL, NULL, '20', '6145597', 'นายธราเทพ    สานุศิษย์', 2, NULL, NULL, '2018-02-12 22:16:15', '2018-02-12 22:16:15');
INSERT INTO `tb_quequ` VALUES (10, 'A005', '2018-02-13 10:19:54', NULL, NULL, '10', '6145568', 'นางสาวปุณยนุช    ประโคทัง', 2, NULL, NULL, '2018-02-13 10:19:54', '2018-02-13 10:19:54');
INSERT INTO `tb_quequ` VALUES (11, 'A006', '2018-02-13 10:21:46', NULL, NULL, '228', '6146174', 'นายวิชัยชาญ    บุญเฮ้า', 1, 1, 1, '2018-02-13 10:21:46', '2018-02-13 10:21:46');
INSERT INTO `tb_quequ` VALUES (12, 'A007', '2018-02-13 10:42:39', NULL, NULL, '230', '6148855', 'นายพงศกร    อุดร', 1, 1, 1, '2018-02-13 10:42:39', '2018-02-13 10:42:39');
INSERT INTO `tb_quequ` VALUES (13, 'A008', '2018-02-13 14:08:11', NULL, NULL, '2', '6145539', 'นายธราวุธ    ขาวยะบุตร', 2, NULL, NULL, '2018-02-13 14:08:11', '2018-02-13 14:08:11');
INSERT INTO `tb_quequ` VALUES (14, 'A009', '2018-02-13 14:08:37', NULL, NULL, '11', '6145571', 'นางสาวพิชญานันท์    ทองอันตัง', 2, NULL, NULL, '2018-02-13 14:08:37', '2018-02-13 14:08:37');
INSERT INTO `tb_quequ` VALUES (15, 'A010', '2018-02-15 09:47:35', NULL, NULL, '16', '6145581', 'นางสาวศิรประภา    บุญท้าว', 2, NULL, NULL, '2018-02-15 09:47:35', '2018-02-15 09:47:35');
INSERT INTO `tb_quequ` VALUES (16, 'A011', '2018-02-17 08:34:46', NULL, NULL, '708', '6148990', 'นางสาวนุสรา    จันทร์เขียว', 1, 1, 2, '2018-02-17 08:34:46', '2018-02-17 08:34:46');
INSERT INTO `tb_quequ` VALUES (17, 'A012', '2018-02-17 08:34:59', NULL, NULL, '709', '6148991', 'นางสาวปราณัสมา    จันทร์ชนะ', 1, 1, 2, '2018-02-17 08:34:59', '2018-02-17 08:34:59');
INSERT INTO `tb_quequ` VALUES (18, 'A013', '2018-02-17 08:35:12', NULL, NULL, '710', '6148992', 'นางสาวปวันรัตน์    ฮิมปะลาด', 1, 1, 2, '2018-02-17 08:35:12', '2018-02-17 08:35:12');
INSERT INTO `tb_quequ` VALUES (19, 'A014', '2018-02-17 08:36:48', NULL, NULL, '711', '6148993', 'นางสาวพรสวรรค์    มโนมัยกิจ', 1, 1, 2, '2018-02-17 08:36:48', '2018-02-17 08:36:48');
INSERT INTO `tb_quequ` VALUES (20, 'A015', '2018-02-17 08:37:00', NULL, NULL, '712', '6148994', 'นางสาวเฟื่องฟ้า    สร้อยงาม', 1, 1, 2, '2018-02-17 08:37:00', '2018-02-17 08:37:00');
INSERT INTO `tb_quequ` VALUES (21, 'A016', '2018-02-17 08:37:11', NULL, NULL, '713', '6148995', 'นางสาวศุภาศินีย์    สุขศิริภูวกุล', 1, 1, 2, '2018-02-17 08:37:11', '2018-02-17 08:37:11');
INSERT INTO `tb_quequ` VALUES (22, 'A017', '2018-02-17 08:37:51', NULL, NULL, '714', '6148996', 'นางสาวสิริกร    วงษา', 1, 1, 2, '2018-02-17 08:37:51', '2018-02-17 08:37:51');
INSERT INTO `tb_quequ` VALUES (23, 'A018', '2018-02-17 08:38:11', NULL, NULL, '715', '6148997', 'นางสาวสุภัคสินี    วงค์ตาขี่', 1, 1, 2, '2018-02-17 08:38:11', '2018-02-17 08:38:11');
INSERT INTO `tb_quequ` VALUES (24, 'A019', '2018-02-17 08:38:23', NULL, NULL, '716', '6148998', 'นางสาวอารียา    จิตวิขาม', 1, 1, 2, '2018-02-17 08:38:23', '2018-02-17 08:38:23');
INSERT INTO `tb_quequ` VALUES (25, 'A020', '2018-02-17 08:39:21', NULL, NULL, '695', '6146178', 'นางสาวจิรภัทร์    วิริยะเจริญกิจ', 1, 1, 2, '2018-02-17 08:39:21', '2018-02-17 08:39:21');
INSERT INTO `tb_quequ` VALUES (26, 'A021', '2018-02-18 12:27:46', NULL, NULL, '436', '6148925', 'นายวรท    จันทรเสนา', 1, 1, 2, '2018-02-18 12:27:46', '2018-02-18 12:27:46');
INSERT INTO `tb_quequ` VALUES (27, 'A022', '2018-02-18 12:28:00', NULL, NULL, '437', '6148926', 'นางสาวจารุนันท์    มีบุตร', 1, 1, 2, '2018-02-18 12:28:00', '2018-02-18 12:28:00');
INSERT INTO `tb_quequ` VALUES (28, 'A023', '2018-02-18 12:28:12', NULL, NULL, '438', '6148927', 'นางสาวจารุวรรณ    พงษ์สิงห์', 1, 1, 2, '2018-02-18 12:28:12', '2018-02-18 12:28:12');
INSERT INTO `tb_quequ` VALUES (29, 'A024', '2018-02-18 12:28:22', NULL, NULL, '439', '6148928', 'นางสาวจุฑามาศ    ปุณริบูรณ์', 1, 1, 2, '2018-02-18 12:28:22', '2018-02-18 12:28:22');
INSERT INTO `tb_quequ` VALUES (30, 'A025', '2018-02-18 12:28:34', NULL, NULL, '440', '6148929', 'นางสาวณัฏฐริกา    ใครบุตร', 1, 1, 2, '2018-02-18 12:28:34', '2018-02-18 12:28:34');
INSERT INTO `tb_quequ` VALUES (31, 'A026', '2018-02-18 17:00:16', NULL, NULL, '430', '6147260', 'นายหานสกล    พลข้อ', 1, 1, 2, '2018-02-18 17:00:16', '2018-02-18 17:00:16');
INSERT INTO `tb_quequ` VALUES (32, 'A027', '2018-02-18 17:00:27', NULL, NULL, '431', '6148143', 'นางสาวธีรนุช    อภิชาติหิรัญ', 1, 1, 2, '2018-02-18 17:00:27', '2018-02-18 17:00:27');
INSERT INTO `tb_quequ` VALUES (33, 'A028', '2018-02-18 17:00:37', NULL, NULL, '432', '6148154', 'นายกีรติ    นามโยธา', 1, 1, 2, '2018-02-18 17:00:37', '2018-02-18 17:00:37');
INSERT INTO `tb_quequ` VALUES (41, 'A029', '2018-03-09 10:50:05', NULL, NULL, '699', '6146190', 'นางสาวสิรภัทร    พรหมอุดม', 1, 1, 2, '2018-03-09 10:50:05', '2018-03-09 10:50:05');
INSERT INTO `tb_quequ` VALUES (48, 'B011', '2018-03-09 11:01:17', NULL, NULL, '34', '6146138', 'นายณัฐสิทธิ์    ศรีมุกดา', 2, NULL, NULL, '2018-03-09 11:01:17', '2018-03-09 11:01:17');

-- ----------------------------
-- Table structure for tb_section
-- ----------------------------
DROP TABLE IF EXISTS `tb_section`;
CREATE TABLE `tb_section`  (
  `sec_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'รหัสแผนก',
  `sec_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT 'ชื่อแผนก',
  `sec_firststatus` int(11) NOT NULL,
  PRIMARY KEY (`sec_id`) USING BTREE
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_section
-- ----------------------------
INSERT INTO `tb_section` VALUES (1, 'อายุรกรรม', 1);
INSERT INTO `tb_section` VALUES (2, 'ห้องเจาะเลือด', 3);

-- ----------------------------
-- Table structure for tb_service_md_name
-- ----------------------------
DROP TABLE IF EXISTS `tb_service_md_name`;
CREATE TABLE `tb_service_md_name`  (
  `service_md_name_id` int(2) NOT NULL AUTO_INCREMENT,
  `service_md_name` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
  PRIMARY KEY (`service_md_name_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of tb_service_md_name
-- ----------------------------
INSERT INTO `tb_service_md_name` VALUES (1, 'นายแพทย์ ก. นายแพทย์ ก.');
INSERT INTO `tb_service_md_name` VALUES (2, 'นายแพทย์ ข. นายแพทย์ ข. ');
INSERT INTO `tb_service_md_name` VALUES (3, 'นายแพทย์ ค. นายแพทย์ ค.');
INSERT INTO `tb_service_md_name` VALUES (7, 'นายแพทย์ ง. นายแพทย์ ง.');

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
INSERT INTO `tb_service_status` VALUES (1, 'รอคัดกรอง');
INSERT INTO `tb_service_status` VALUES (2, 'เรียกคิวคัดกรอง');
INSERT INTO `tb_service_status` VALUES (3, 'รอเจาะเลือด');
INSERT INTO `tb_service_status` VALUES (4, 'เรียกคิวเจาะเลือด');
INSERT INTO `tb_service_status` VALUES (5, 'รอพบแพทย์');
INSERT INTO `tb_service_status` VALUES (7, 'เรียกคิวพบแพทย์');
INSERT INTO `tb_service_status` VALUES (8, 'HoldQ');
INSERT INTO `tb_service_status` VALUES (9, 'EndQ');
INSERT INTO `tb_service_status` VALUES (10, 'เสร็จสิ้น');

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
) ENGINE = MyISAM AUTO_INCREMENT = 6 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of tb_ticket
-- ----------------------------
INSERT INTO `tb_ticket` VALUES (1, 'โรงพยาบาลชัยนาทนเรนทร', 'Barnbung Hospital', '<div class=\"x_content\">\r\n<div class=\"row\" style=\"margin-bottom:0px; margin-left:0px; margin-right:0px; margin-top:0px; width:80mm\">\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:1cm 21px 0px 21px\">\r\n<div class=\"col-xs-12\" style=\"padding:0\"><img alt=\"\" class=\"center-block\" src=\"/img/logo/logo.jpg\" style=\"width:100px\" /></div>\r\n\r\n<div class=\"col-xs-12\" style=\"padding:0\">\r\n<h4 style=\"text-align:center\"><strong>{hos_name_th}</strong></h4>\r\n\r\n<h6 style=\"text-align:center\"><strong>งานบริการผู้ป่วยนอก</strong></h6>\r\n</div>\r\n\r\n<div class=\"col-xs-12\" style=\"padding:3px 0px 10px 0px; text-align:left\">\r\n<h6 style=\"margin-left:1px; margin-right:1px\"><strong>HN</strong> : <strong>{q_hn}</strong></h6>\r\n\r\n<h6 style=\"margin-left:1px; margin-right:1px\"><strong>ชื่อ-นามสกุล</strong> : <strong>{pt_name}</strong></h6>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-12\" style=\"padding:0\">\r\n<h1 style=\"text-align:center\"><strong>{q_num}</strong></h1>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<h5 style=\"text-align:center\"><strong>{pt_visit_type}</strong></h5>\r\n</div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<h5 style=\"text-align:center\"><strong>{sec_name}</strong></h5>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:5px 20px 0px 20px\">\r\n<div class=\"col-xs-12\" style=\"padding:0; text-align:left\">\r\n<div class=\"col-xs-12\" style=\"border-top:dashed 1px #404040; padding:4px 0px 3px 0px\">\r\n<div class=\"col-xs-12\" style=\"padding:1px\">\r\n<h6 style=\"margin-left:0px; margin-right:0px\"><strong>Scan QR Code เพื่อดูสถานะการรอคิว</strong></h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<div id=\"qrcode\"><img alt=\"\" src=\"/img/qrcode.png\" /></div>\r\n</div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<div id=\"bcTarget\" style=\"overflow:auto; padding:0px; width:143px\">\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:10px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:4px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:4px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:4px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:10px\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; clear:both; color:#000000; font-size:10px; margin-top:5px; text-align:center; width:100%\">1234567890128</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:10px 0px 0px 0px\">\r\n<h4 style=\"text-align:center\"><strong>ขอบคุณที่ใช้บริการ</strong></h4>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0; text-align:left\">\r\n<h6 style=\"text-align:left\"><strong>{time}</strong></h6>\r\n</div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0; text-align:right\">\r\n<h6 style=\"text-align:right\"><strong>{user_print}</strong></h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n', '<center>\r\n            <div class=\"x_content\">\r\n                <div class=\"row\" style=\"width: 80mm;margin: auto;\">\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 1cm 21px 0px 21px;\">\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <img src=\"/img/logo/logo.jpg\" alt=\"\" class=\"center-block\" style=\"width: 100px\">\r\n                        </div>\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <h4 class=\"color\" style=\"margin-top: 0px;margin-bottom: 0px;text-align: center;\"><b style=\"font-weight: bold;\">{hos_name_th}</b></h4>\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: center;\"><b>งานบริการผู้ป่วยนอก</b></h6>\r\n                        </div>\r\n                        <div class=\"col-xs-12\" style=\"padding: 3px 0px 10px 0px;;text-align: left;\">\r\n                            <h6 style=\"margin: 4px 1px;\" class=\"color\">\r\n                                <b style=\"font-size: 14px; font-weight: 600;\">HN</b>  :  <b style=\"font-size: 13px;\">{q_hn}</b>\r\n                            </h6>\r\n                            <h6 style=\"margin: 4px 1px;\" class=\"color\">\r\n                                <b style=\"font-size: 14px; font-weight: 600;\">ชื่อ-นามสกุล</b>  :  <b style=\"font-size: 13px;\">{pt_name}</b>\r\n                            </h6>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <h1 style=\"text-align: center;\"><b style=\"font-weight: 600;text-align: center;\">{q_num}</b></h1>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <h5 style=\"text-align: center;\"><b style=\"font-weight: 600;\">{pt_visit_type}</b></h5>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <h5 style=\"text-align: center;\"><b style=\"font-weight: 600;\">{sec_name}</b></h5>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 5px 20px 0px 20px;\">\r\n                        <div class=\"col-xs-12\" style=\"text-align: left;padding: 0;\">\r\n                            <div class=\"col-xs-12\" style=\"padding: 4px 0px 3px 0px;border-top: dashed 1px #404040;\">\r\n                                <div class=\"col-xs-12\" style=\"padding: 1px;\">\r\n                                    <h6 class=\"color\" style=\"margin: 0px;\"><b>Scan QR Code เพื่อดูสถานะการรอคิว</b></h6>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <div id=\"qrcode\"><img alt=\"\" src=\"/img/qrcode.png\" /></div>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <div id=\"bcTarget\" style=\"overflow: auto; padding: 0px; width: 143px;\"><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 10px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 4px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 4px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 4px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 10px\"></div><div style=\"clear:both; width: 100%; background-color: #FFFFFF; color: #000000; text-align: center; font-size: 10px; margin-top: 5px;\">1234567890128</div></div>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 10px 0px 0px 0px;\">\r\n                        <h4 class=\"color\" style=\"margin-top: 0px;margin-bottom: 0px;text-align: center;\"><b>ขอบคุณที่ใช้บริการ</b></h4>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;text-align: left;\">\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: left;\"><b>{time}</b></h6>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;text-align: right;\">\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: right;\"><b>{user_print}</b></h6>\r\n                        </div>\r\n                    </div>\r\n\r\n                </div>\r\n            </div>\r\n        </center>', '1/lvCa3PFqJzOASW145okm6zG0UDzx9Qcy.png', '/uploads', 'code128', 1);
INSERT INTO `tb_ticket` VALUES (5, 'xx', 'xx', '<div class=\"x_content\">\r\n<div class=\"row\" style=\"border:1px dashed #dee5e7; margin-bottom:0px; margin-left:0px; margin-right:0px; margin-top:0px; width:80mm\">\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:1cm 21px 0px 21px\">\r\n<div class=\"col-xs-12\" style=\"padding:0\"><img alt=\"\" class=\"center-block\" src=\"/img/logo/logo.jpg\" style=\"width:100px\" /></div>\r\n\r\n<div class=\"col-xs-12\" style=\"padding:0\">\r\n<h4 style=\"text-align:center\"><strong>{hos_name_th}</strong></h4>\r\n\r\n<h6 style=\"text-align:center\"><strong>งานบริการผู้ป่วยนอก</strong></h6>\r\n</div>\r\n\r\n<div class=\"col-xs-12\" style=\"padding:3px 0px 10px 0px; text-align:left\">\r\n<h6 style=\"margin-left:1px; margin-right:1px\"><strong>HN</strong> : <strong>{q_hn}</strong></h6>\r\n\r\n<h6 style=\"margin-left:1px; margin-right:1px\"><strong>ชื่อ-นามสกุล</strong> : <strong>{pt_name}</strong></h6>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-12\" style=\"padding:0\">\r\n<h1 style=\"text-align:center\"><strong>{q_num}</strong></h1>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<h5 style=\"text-align:center\"><strong>{pt_visit_type}</strong></h5>\r\n</div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<h5 style=\"text-align:center\"><strong>{sec_name}</strong></h5>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:5px 20px 0px 20px\">\r\n<div class=\"col-xs-12\" style=\"padding:0; text-align:left\">\r\n<div class=\"col-xs-12\" style=\"border-top:dashed 1px #404040; padding:4px 0px 3px 0px\">\r\n<div class=\"col-xs-12\" style=\"padding:1px\">\r\n<h6 style=\"margin-left:0px; margin-right:0px\"><strong>Scan QR Code เพื่อดูสถานะการรอคิว</strong></h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0\"><img alt=\"\" src=\"/img/qrcode.png\" /></div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0\">\r\n<div id=\"bcTarget\" style=\"overflow:auto; padding:0px; width:143px\">\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:10px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:4px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:4px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:4px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:2px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:3px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:3px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:1px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:1px\">&nbsp;</div>\r\n\r\n<div style=\"border-left:2px solid #000000; float:left; font-size:0px; height:50px; width:0\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; float:left; font-size:0px; height:50px; width:10px\">&nbsp;</div>\r\n\r\n<div style=\"background-color:#ffffff; clear:both; color:#000000; font-size:10px; margin-top:5px; text-align:center; width:100%\">1234567890128</div>\r\n</div>\r\n</div>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:10px 0px 0px 0px\">\r\n<h4 style=\"text-align:center\"><strong>ขอบคุณที่ใช้บริการ</strong></h4>\r\n</div>\r\n\r\n<div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding:0px 21px 0px 21px\">\r\n<div class=\"col-xs-6\" style=\"padding:0; text-align:left\">\r\n<h6 style=\"text-align:left\"><strong>{time}</strong></h6>\r\n</div>\r\n\r\n<div class=\"col-xs-6\" style=\"padding:0; text-align:right\">\r\n<h6 style=\"text-align:right\"><strong>{user_print}</strong></h6>\r\n</div>\r\n</div>\r\n</div>\r\n</div>\r\n', '<center>\r\n            <div class=\"x_content\">\r\n                <div class=\"row\" style=\"width: 80mm;margin: auto;border: 1px dashed #dee5e7\">\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 1cm 21px 0px 21px;\">\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <img src=\"/img/logo/logo.jpg\" alt=\"\" class=\"center-block\" style=\"width: 100px\">\r\n                        </div>\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <h4 class=\"color\" style=\"margin-top: 0px;margin-bottom: 0px;text-align: center;\"><b style=\"font-weight: bold;\">{hos_name_th}</b></h4>\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: center;\"><b>งานบริการผู้ป่วยนอก</b></h6>\r\n                        </div>\r\n                        <div class=\"col-xs-12\" style=\"padding: 3px 0px 10px 0px;;text-align: left;\">\r\n                            <h6 style=\"margin: 4px 1px;\" class=\"color\">\r\n                                <b style=\"font-size: 14px; font-weight: 600;\">HN</b>  :  <b style=\"font-size: 13px;\">{q_hn}</b>\r\n                            </h6>\r\n                            <h6 style=\"margin: 4px 1px;\" class=\"color\">\r\n                                <b style=\"font-size: 14px; font-weight: 600;\">ชื่อ-นามสกุล</b>  :  <b style=\"font-size: 13px;\">{pt_name}</b>\r\n                            </h6>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-12\" style=\"padding: 0;\">\r\n                            <h1 style=\"text-align: center;\"><b style=\"font-weight: 600;text-align: center;\">{q_num}</b></h1>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <h5 style=\"text-align: center;\"><b style=\"font-weight: 600;\">{pt_visit_type}</b></h5>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <h5 style=\"text-align: center;\"><b style=\"font-weight: 600;\">{sec_name}</b></h5>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 5px 20px 0px 20px;\">\r\n                        <div class=\"col-xs-12\" style=\"text-align: left;padding: 0;\">\r\n                            <div class=\"col-xs-12\" style=\"padding: 4px 0px 3px 0px;border-top: dashed 1px #404040;\">\r\n                                <div class=\"col-xs-12\" style=\"padding: 1px;\">\r\n                                    <h6 class=\"color\" style=\"margin: 0px;\"><b>Scan QR Code เพื่อดูสถานะการรอคิว</b></h6>\r\n                                </div>\r\n                            </div>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <img src=\"/img/qrcode.png\" alt=\"\">\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;\">\r\n                            <div id=\"bcTarget\" style=\"overflow: auto; padding: 0px; width: 143px;\"><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 10px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 4px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 4px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 4px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 2px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 3px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 3px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 1px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 1px\"></div><div style=\"float: left; font-size: 0px; width:0; border-left: 2px solid #000000; height: 50px;\"></div><div style=\"float: left; font-size: 0px; background-color: #FFFFFF; height: 50px; width: 10px\"></div><div style=\"clear:both; width: 100%; background-color: #FFFFFF; color: #000000; text-align: center; font-size: 10px; margin-top: 5px;\">1234567890128</div></div>\r\n                        </div>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 10px 0px 0px 0px;\">\r\n                        <h4 class=\"color\" style=\"margin-top: 0px;margin-bottom: 0px;text-align: center;\"><b>ขอบคุณที่ใช้บริการ</b></h4>\r\n                    </div>\r\n\r\n                    <div class=\"col-md-12 col-sm-12 col-xs-12\" style=\"padding: 0px 21px 0px 21px;\">\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;text-align: left;\">\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: left;\"><b>{time}</b></h6>\r\n                        </div>\r\n                        <div class=\"col-xs-6\" style=\"padding: 0;text-align: right;\">\r\n                            <h6 class=\"color\" style=\"margin-top: 4px;margin-bottom: 0px;text-align: right;\"><b>{user_print}</b></h6>\r\n                        </div>\r\n                    </div>\r\n\r\n                </div>\r\n            </div>\r\n        </center>', NULL, NULL, 'code128', 1);

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
  CONSTRAINT `fk_user_token` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE RESTRICT
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
INSERT INTO `user` VALUES (2, 'admin', 'admin-banbung@qsystem.com', '$2y$12$/cRo6GQyHmT6gufgQ13BK.Uwck0dW.NdCM55XRq5rB56Pm2kKdpFm', 's59qUPZ27CxgPEaNgLw71CrygQD5Ni3x', 1517979470, NULL, NULL, '127.0.0.1', 1517979470, 1517979470, 0, 1520587800);

SET FOREIGN_KEY_CHECKS = 1;
