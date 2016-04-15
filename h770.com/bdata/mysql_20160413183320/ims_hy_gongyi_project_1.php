<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hy_gongyi_project`;");
E_C("CREATE TABLE `ims_hy_gongyi_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `limit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `donenum` int(10) unsigned NOT NULL DEFAULT '0',
  `finish_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `deal_days` int(10) unsigned NOT NULL,
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isrecommand` tinyint(1) unsigned DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `brief` varchar(1000) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `jinzhan` text NOT NULL,
  `nosubuser` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0暂停1正常2停止',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>