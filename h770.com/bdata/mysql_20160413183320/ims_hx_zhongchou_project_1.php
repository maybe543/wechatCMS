<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hx_zhongchou_project`;");
E_C("CREATE TABLE `ims_hx_zhongchou_project` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `displayorder` int(10) unsigned NOT NULL DEFAULT '0',
  `title` varchar(100) NOT NULL DEFAULT '',
  `limit_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `donenum` int(10) unsigned NOT NULL DEFAULT '0',
  `finish_price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `starttime` int(10) unsigned DEFAULT '0',
  `deal_days` int(10) unsigned NOT NULL,
  `ishot` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `isrecommand` tinyint(1) unsigned DEFAULT '0',
  `pcate` int(10) unsigned NOT NULL DEFAULT '0',
  `ccate` int(10) unsigned NOT NULL DEFAULT '0',
  `thumb` varchar(255) DEFAULT '',
  `brief` varchar(1000) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `nosubuser` tinyint(1) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0暂停1正常2停止',
  `reason` varchar(255) DEFAULT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `subsurl` varchar(500) DEFAULT NULL,
  `show_type` int(10) DEFAULT '0',
  `type` tinyint(10) DEFAULT '0',
  `lianxiren` varchar(20) DEFAULT '',
  `qq` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>