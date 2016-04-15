<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_weicard_sysset`;");
E_C("CREATE TABLE `ims_amouse_weicard_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `guanzhuUrl` varchar(255) DEFAULT '1' COMMENT '引导关注',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `cnzz` varchar(800) DEFAULT '' COMMENT '第三方统计',
  `appid` varchar(255) DEFAULT '',
  `isoauth` int(2) unsigned NOT NULL DEFAULT '1',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>