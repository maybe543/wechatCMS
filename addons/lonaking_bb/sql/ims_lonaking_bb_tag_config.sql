/*
Navicat MySQL Data Transfer

Source Server         : 静安
Source Server Version : 50096
Source Host           : 116.255.194.194:3306
Source Database       : newwe7

Target Server Type    : MYSQL
Target Server Version : 50096
File Encoding         : 65001

Date: 2015-08-26 17:37:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_lonaking_bb_tag_config
-- ----------------------------
DROP TABLE IF EXISTS `ims_lonaking_bb_tag_config`;
CREATE TABLE `ims_lonaking_bb_tag_config` (
  `id` int(11) NOT NULL auto_increment,
  `uniacid` int(11) default NULL,
  `tag` varchar(20) default NULL,
  `color` varchar(30) default NULL,
  `create_time` int(11) default NULL,
  `update_time` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;
