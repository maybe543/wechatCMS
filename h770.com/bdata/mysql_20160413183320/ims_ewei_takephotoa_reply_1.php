<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_takephotoa_reply`;");
E_C("CREATE TABLE `ims_ewei_takephotoa_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `rid` int(11) DEFAULT '0',
  `title` varchar(255) DEFAULT '',
  `description` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `starttime` int(11) DEFAULT '0',
  `endtime` int(11) DEFAULT '0',
  `bgimg` varchar(255) DEFAULT '',
  `helpimg` varchar(255) DEFAULT '',
  `shareimg` varchar(255) DEFAULT '',
  `titleimg` varchar(255) DEFAULT '',
  `cameraimg` varchar(255) DEFAULT '',
  `numberimg` varchar(255) DEFAULT '',
  `items` text COMMENT '物品',
  `follow_url` varchar(1000) DEFAULT '',
  `share_url` varchar(1000) DEFAULT '',
  `viewnum` int(11) DEFAULT '0',
  `status` tinyint(1) DEFAULT '0',
  `share_desc` varchar(500) DEFAULT '',
  `share_icon` varchar(255) DEFAULT '',
  `share_title` varchar(255) DEFAULT '',
  `createtime` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_rid` (`rid`),
  KEY `idx_status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>