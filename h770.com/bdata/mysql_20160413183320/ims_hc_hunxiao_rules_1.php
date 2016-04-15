<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hc_hunxiao_rules`;");
E_C("CREATE TABLE `ims_hc_hunxiao_rules` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `rule` text,
  `terms` text,
  `credit` varchar(10) DEFAULT '',
  `conversion` int(10) unsigned DEFAULT '1',
  `promotertimes` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '默认成交一次才能成为推广员',
  `userdefault` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '用户自定义分销级数',
  `createtime` int(10) NOT NULL,
  `gzurl` varchar(255) DEFAULT NULL,
  `globalCommission` int(10) NOT NULL DEFAULT '0',
  `isrecommend` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `commtime` int(5) NOT NULL DEFAULT '15' COMMENT '默认15天',
  `title` varchar(20) DEFAULT '',
  `description` varchar(100) DEFAULT '',
  `picture` varchar(255) DEFAULT '',
  `online` varchar(255) DEFAULT '',
  `onlinepicture` varchar(255) DEFAULT '',
  `template_id` varchar(255) DEFAULT NULL,
  `qrpicture` varchar(255) DEFAULT NULL,
  `sendGoodsSend` varchar(100) DEFAULT NULL,
  `sendCommWarm` varchar(100) DEFAULT NULL,
  `sendCheckChange` varchar(100) DEFAULT NULL,
  `sendApplyMoneyBack` varchar(100) DEFAULT NULL,
  `sendMoneyBack` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>