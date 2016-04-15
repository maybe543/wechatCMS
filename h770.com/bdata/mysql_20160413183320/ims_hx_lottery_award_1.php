<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hx_lottery_award`;");
E_C("CREATE TABLE `ims_hx_lottery_award` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `reply_id` int(10) DEFAULT '0',
  `uid` int(10) DEFAULT '0',
  `name` varchar(50) DEFAULT '' COMMENT '名称',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `level` int(10) unsigned NOT NULL,
  `createtime` int(10) DEFAULT '0',
  `consumetime` int(10) DEFAULT '0',
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>