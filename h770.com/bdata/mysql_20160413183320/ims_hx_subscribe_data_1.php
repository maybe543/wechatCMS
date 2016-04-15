<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hx_subscribe_data`;");
E_C("CREATE TABLE `ims_hx_subscribe_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `from_uid` int(10) unsigned NOT NULL,
  `sn` int(10) unsigned NOT NULL,
  `follow` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `article_id` int(10) unsigned NOT NULL,
  `shouyi` int(10) unsigned NOT NULL DEFAULT '0',
  `zjrs` int(10) unsigned NOT NULL DEFAULT '0',
  `jjrs` int(10) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>