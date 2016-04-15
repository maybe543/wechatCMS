<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_eso_runman_submit`;");
E_C("CREATE TABLE `ims_eso_runman_submit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT '',
  `rid` int(11) DEFAULT NULL,
  `openid` varchar(255) DEFAULT '',
  `title` varchar(255) DEFAULT '0',
  `did` int(10) unsigned DEFAULT '0',
  `indate` bigint(18) unsigned DEFAULT '0',
  `update` bigint(18) unsigned DEFAULT '0',
  `money` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '提现金额',
  `exchange` tinyint(3) unsigned DEFAULT '0' COMMENT '1为已处理',
  `setting` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奔跑兄弟 - 领取记录'");

require("../../inc/footer.php");
?>