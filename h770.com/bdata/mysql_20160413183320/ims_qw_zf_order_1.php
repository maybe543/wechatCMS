<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_qw_zf_order`;");
E_C("CREATE TABLE `ims_qw_zf_order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `fee` decimal(10,2) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `ordersn` varchar(255) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `xq` varchar(255) DEFAULT NULL,
  `transid` varchar(255) DEFAULT NULL,
  `paytype` int(1) DEFAULT '0',
  `addtime` datetime DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>