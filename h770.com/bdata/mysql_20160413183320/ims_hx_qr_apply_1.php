<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hx_qr_apply`;");
E_C("CREATE TABLE `ims_hx_qr_apply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `uid` int(10) unsigned NOT NULL,
  `realname` varchar(20) NOT NULL,
  `qq` varchar(50) NOT NULL,
  `type` tinyint(1) unsigned NOT NULL,
  `alipay` varchar(50) NOT NULL,
  `cardid` varchar(50) NOT NULL,
  `cardfrom` varchar(255) NOT NULL,
  `cardname` varchar(10) NOT NULL,
  `credit2` decimal(10,2) unsigned NOT NULL DEFAULT '0.00',
  `mobile` varchar(12) NOT NULL,
  `status` tinyint(2) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `remark` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>