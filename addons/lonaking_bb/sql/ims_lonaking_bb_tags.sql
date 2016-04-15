/*
Navicat MySQL Data Transfer

Source Server         : 静安
Source Server Version : 50096
Source Host           : 116.255.194.194:3306
Source Database       : newwe7

Target Server Type    : MYSQL
Target Server Version : 50096
File Encoding         : 65001

Date: 2015-08-26 17:36:56
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ims_lonaking_bb_tags
-- ----------------------------
DROP TABLE IF EXISTS `ims_lonaking_bb_tags`;
CREATE TABLE `ims_lonaking_bb_tags` (
  `id` int(11) NOT NULL auto_increment,
  `uniacid` int(11) default NULL,
  `fanid` int(11) default NULL,
  `openid` varchar(100) default NULL,
  `value` varchar(20) default NULL COMMENT '标签内容',
  `buzy` tinyint(1) default '0' COMMENT '0  闲置 1忙碌',
  `create_time` int(11) default NULL,
  `update_time` int(11) default NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;
