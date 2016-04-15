<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hx_qr_user`;");
E_C("CREATE TABLE `ims_hx_qr_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `acid` int(10) unsigned NOT NULL,
  `reply_id` int(10) unsigned NOT NULL,
  `openid` varchar(40) NOT NULL,
  `nickname` varchar(20) NOT NULL,
  `avater` varchar(100) NOT NULL,
  `qrid` int(10) unsigned NOT NULL,
  `sub_openid` varchar(40) NOT NULL,
  `first_level` int(10) unsigned NOT NULL,
  `secend_level` int(10) unsigned NOT NULL,
  `status` tinyint(1) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>