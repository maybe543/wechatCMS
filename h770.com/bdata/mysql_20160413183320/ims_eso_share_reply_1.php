<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_eso_share_reply`;");
E_C("CREATE TABLE `ims_eso_share_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `isname` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要绑定个人信息',
  `parentid` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(50) NOT NULL COMMENT '规则标题',
  `checkkeyword` varchar(50) NOT NULL COMMENT '查询关键词',
  `picture` varchar(100) NOT NULL COMMENT '图片',
  `start_time` int(10) unsigned NOT NULL COMMENT '开始时间',
  `end_time` int(10) unsigned NOT NULL COMMENT '结束时间',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `content` text NOT NULL COMMENT '内容',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '开关状态',
  `r` int(10) unsigned NOT NULL DEFAULT '0',
  `z` int(10) unsigned NOT NULL DEFAULT '0',
  `u` varchar(255) DEFAULT NULL,
  `share_url` text,
  `share_txt` text,
  `share_desc` text,
  `share_title` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分享达人'");

require("../../inc/footer.php");
?>