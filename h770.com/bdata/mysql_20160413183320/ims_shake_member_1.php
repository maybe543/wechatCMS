<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_shake_member`;");
E_C("CREATE TABLE `ims_shake_member` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `shakecount` int(10) unsigned NOT NULL DEFAULT '0',
  `remark` varchar(500) NOT NULL DEFAULT '',
  `lastupdate` int(10) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0为不可摇奖，1为可摇奖',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_openid_replyid` (`openid`,`rid`),
  KEY `idx_replyid` (`rid`),
  KEY `idx_shakecount` (`rid`,`shakecount`),
  KEY `createtime` (`createtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>