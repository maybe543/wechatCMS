<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_yyzf_record`;");
E_C("CREATE TABLE `ims_mon_yyzf_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `yid` int(11) unsigned DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(200) NOT NULL,
  `headimgurl` varchar(200) NOT NULL,
  `wish` varchar(500) NOT NULL,
  `serverId` varchar(500) NOT NULL,
  `createtime` int(10) unsigned NOT NULL COMMENT '日期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>