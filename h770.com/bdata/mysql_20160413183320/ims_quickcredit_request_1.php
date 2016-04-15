<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickcredit_request`;");
E_C("CREATE TABLE `ims_quickcredit_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user_realname` varchar(50) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(200) NOT NULL,
  `mobile` varchar(200) NOT NULL,
  `residedist` varchar(200) NOT NULL,
  `alipay` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `goods_id` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>