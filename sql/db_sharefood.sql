/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : localhost:3306
Source Database       : db_sharefood

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2018-03-14 21:45:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `t_admin`
-- ----------------------------
DROP TABLE IF EXISTS `t_admin`;
CREATE TABLE `t_admin` (
  `admin_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `mobile` char(11) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  PRIMARY KEY (`admin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_admin
-- ----------------------------
INSERT INTO `t_admin` VALUES ('1', '18866668888', '165', 'qqqq');
INSERT INTO `t_admin` VALUES ('2', '13510660148', '456123', 'admin1');

-- ----------------------------
-- Table structure for `t_article`
-- ----------------------------
DROP TABLE IF EXISTS `t_article`;
CREATE TABLE `t_article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `publish_user_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `add_time` datetime NOT NULL,
  `cover_image` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `click_count` int(11) NOT NULL DEFAULT '0',
  `publish_user_name` varchar(255) NOT NULL,
  `verify_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `article_status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0:未审核 1:审核 2:删除',
  `c_id` tinyint(4) unsigned NOT NULL COMMENT '类别id',
  `type` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`article_id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_article
-- ----------------------------
INSERT INTO `t_article` VALUES ('9', '1', '<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;国庆甲鱼肥成猪 &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ！！！！！！ &nbsp;！</p>', '2017-10-14 12:22:39', 'http://qcloud.dpfile.com/pc/sefQgqgK3amjzIY56db9XYvLIFZ7FQ3ACR-h9MopXfpfvXDPj6ufn7ygGkheaIIZTYGVDmosZWTLal1WbWRW3A.jpg', '班戟榴莲小千层', '23', 'YOGA', '0000-00-00 00:00:00', '1', '1', '1');
INSERT INTO `t_article` VALUES ('11', '2', '<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;国庆甲鱼肥成猪\r\n\r\n\r\n &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>', '2017-10-14 12:26:45', 'http://qcloud.dpfile.com/pc/uVeNdkTFpjkSBwXsE-GBGv2LieCC_TlVybNcuSLEWFHcCX7p6YJh_qbkoc9r5I2QTYGVDmosZWTLal1WbWRW3A.jpg', '椰汁雪糕', '8', '嘉瑜', '0000-00-00 00:00:00', '1', '2', '1');
INSERT INTO `t_article` VALUES ('12', '1', '<p>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;国庆甲鱼肥成猪\r\n\r\n\r\n &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;</p>', '2017-10-14 12:26:59', 'http://qcloud.dpfile.com/pc/B6vZTHGhRsJLmal5DAgiCvnl2LVZvYTDDp6UhNtzXKsCCnq-2fPZxM8ThQ2sImF6TYGVDmosZWTLal1WbWRW3A.jpg', '七色彩虹蛋糕', '9', 'YOGA', '0000-00-00 00:00:00', '1', '3', '1');
INSERT INTO `t_article` VALUES ('13', '1', '<p>!!!!你好啊！</p>', '2017-10-14 12:32:47', 'http://qcloud.dpfile.com/pc/sJ9x5GXVBhYLFRPvauPsPK5RQrPNgx9SzIC6aUJrgvXBEsfCGQirJpwXRyncBMGKTYGVDmosZWTLal1WbWRW3A.jpg', '爆好吃！芋圆', '2', 'YOGA', '0000-00-00 00:00:00', '0', '2', '1');
INSERT INTO `t_article` VALUES ('14', '1', '<p>猪是我！！！！<br/></p><p><img src=\"/ueditor/php/upload/image/20171014/1507955615275231.jpg\" title=\"1507955615275231.jpg\" alt=\"微信图片_20170912212613.jpg\"/></p>', '2017-10-14 12:33:45', 'http://qcloud.dpfile.com/pc/fdMeywtJSwYTzfp3meIqZVxtoMg5zH35-3mtKVxRPuc85m6KLxJrZMuRwDiePBFkTYGVDmosZWTLal1WbWRW3A.jpg', '爆好吃！芋圆!!!', '13', 'YOGA', '0000-00-00 00:00:00', '1', '3', '1');

-- ----------------------------
-- Table structure for `t_article_statistics`
-- ----------------------------
DROP TABLE IF EXISTS `t_article_statistics`;
CREATE TABLE `t_article_statistics` (
  `s_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `add_time` datetime NOT NULL,
  PRIMARY KEY (`s_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_article_statistics
-- ----------------------------
INSERT INTO `t_article_statistics` VALUES ('1', '1', '1', '2017-10-01 18:17:45');
INSERT INTO `t_article_statistics` VALUES ('2', '1', '1', '2017-10-01 18:18:33');
INSERT INTO `t_article_statistics` VALUES ('3', '2', '1', '2017-10-01 18:31:53');

-- ----------------------------
-- Table structure for `t_category`
-- ----------------------------
DROP TABLE IF EXISTS `t_category`;
CREATE TABLE `t_category` (
  `c_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `c_name` varchar(255) NOT NULL,
  `c_content` varchar(255) NOT NULL,
  PRIMARY KEY (`c_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_category
-- ----------------------------
INSERT INTO `t_category` VALUES ('1', '小吃', '');
INSERT INTO `t_category` VALUES ('2', '甜品', '');
INSERT INTO `t_category` VALUES ('3', '主食', '');

-- ----------------------------
-- Table structure for `t_comment`
-- ----------------------------
DROP TABLE IF EXISTS `t_comment`;
CREATE TABLE `t_comment` (
  `comment_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `comment_content` text NOT NULL,
  `add_time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `c_user` int(11) NOT NULL,
  PRIMARY KEY (`comment_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `t_marticle`
-- ----------------------------
DROP TABLE IF EXISTS `t_marticle`;
CREATE TABLE `t_marticle` (
  `ma_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `publish_user_id` int(11) NOT NULL,
  `add_time` datetime NOT NULL,
  `verify_time` datetime NOT NULL,
  `longitude` varchar(255) NOT NULL COMMENT '经度',
  `latitude` varchar(255) NOT NULL COMMENT '纬度',
  `address` varchar(255) NOT NULL,
  `cover_image` varchar(255) NOT NULL,
  `click_count` int(11) NOT NULL,
  `article_status` int(11) NOT NULL,
  `publish_user_name` varchar(255) NOT NULL,
  `content` text,
  PRIMARY KEY (`ma_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_marticle
-- ----------------------------
INSERT INTO `t_marticle` VALUES ('1', '喜茶HEYTEA', '1', '2017-11-04 15:36:46', '0000-00-00 00:00:00', '113.93546', '22.51806', '广东省深圳市南山区南山商业中心天利中央商务广场二期210铺gaga鲜语对面)喜茶(海岸城店)', '/uploads\\20171104\\a9242f5a58fb00f3b7d5b9a62b566c7e.jpg', '31', '1', 'YOGA', '<p><img src=\"/ueditor/php/upload/image/20171104/1509780716109688.jpg\" title=\"1509780716109688.jpg\" alt=\"p70928378.webp.jpg\"/></p><p><img src=\"/ueditor/php/upload/image/20171104/1509780723707872.jpg\" title=\"1509780723707872.jpg\" alt=\"p70928383.webp.jpg\"/></p>');

-- ----------------------------
-- Table structure for `t_merchant`
-- ----------------------------
DROP TABLE IF EXISTS `t_merchant`;
CREATE TABLE `t_merchant` (
  `m_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `m_introduce` text NOT NULL,
  `become_time` datetime NOT NULL,
  `m_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `cover_image` varchar(255) NOT NULL,
  `click_count` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`m_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_merchant
-- ----------------------------
INSERT INTO `t_merchant` VALUES ('1', '年轻人的圣地', '2017-10-21 20:31:07', '可可西里负氧离子音乐餐厅', '东门街道东门中路2002号瑞季精品酒店江南大厦5楼(华佳广场旁)', '15999695813', 'https://img.meituan.net/msmerchant/87031fa2d38c887dd59d3e3cfb7f7353138192.jpg%40700w_700h_0e_1l%7Cwatermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20', '61', '1');
INSERT INTO `t_merchant` VALUES ('2', '这是一款最懂90后用户心理的奶茶', '2017-10-21 20:31:03', '喜茶(东门1234店)', '喜茶(东门1234店)', '0755-86629229', 'https://img.meituan.net/msmerchant/cbbd1d1937fd303529bf7733736cd03785546.jpg%40700w_700h_0e_1l%7Cwatermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20', '8', '2');
INSERT INTO `t_merchant` VALUES ('3', '榴莲班戟、榴莲千层蛋糕让你回味无穷', '2017-10-21 20:58:16', '四季榴莲(茂业奥特莱斯店) ', ' 和平路3009号茂业奥特莱斯1层', '0755-22197358', 'http://p1.meituan.net/deal/33ad8f5f401491569b001fa8cab5f66b102401.jpg%40700w_700h_0e_1l%7Cwatermark%3D1%26%26r%3D1%26p%3D9%26x%3D2%26y%3D2%26relative%3D1%26o%3D20', '29', '1');

-- ----------------------------
-- Table structure for `t_user`
-- ----------------------------
DROP TABLE IF EXISTS `t_user`;
CREATE TABLE `t_user` (
  `user_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `mobile` char(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `sex` tinyint(4) NOT NULL,
  `avatar_url` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `reg_time` datetime NOT NULL,
  `is_admin` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0：不是管理员  1：是管理员',
  `user_status` tinyint(4) NOT NULL COMMENT '用户状态：0：封停  1：正常',
  `introduce` text NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of t_user
-- ----------------------------
INSERT INTO `t_user` VALUES ('1', '18218526325', 'YOGA', '1', 'http://wx.qlogo.cn/mmopen/AIYelhPibCCdnzs8AcS9eiaxMibScUAvyR9DbWyuDWJ4FtRwpyO93dbZn79R0nYRHC46N0I7Pd2Tbcrwr5YjWibdVg/132', '111111', '2017-10-01 15:32:00', '1', '1', '一个想要尝遍天下美食的BOY');
INSERT INTO `t_user` VALUES ('2', '13510660148', '嘉瑜', '2', 'http://pic.3h3.com/up/2012-12/2012121227271453243468.jpg', '111111', '2017-10-16 16:56:37', '0', '1', '聪明可爱');
INSERT INTO `t_user` VALUES ('7', '13510660148', '张三', '1', 'https://p1.ssl.qhmsg.com/t01ea8edadeb0a61744.jpg', '111111', '2017-10-16 18:12:16', '0', '1', '');
