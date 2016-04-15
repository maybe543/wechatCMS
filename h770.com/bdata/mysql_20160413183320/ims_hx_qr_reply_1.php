<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hx_qr_reply`;");
E_C("CREATE TABLE `ims_hx_qr_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL,
  `uniacid` int(10) unsigned NOT NULL,
  `bg` varchar(255) NOT NULL,
  `qrleft` int(10) unsigned NOT NULL,
  `qrtop` int(10) unsigned NOT NULL,
  `qrwidth` int(10) unsigned NOT NULL,
  `qrheight` int(10) unsigned NOT NULL,
  `avatarleft` int(10) unsigned NOT NULL,
  `avatartop` int(10) unsigned NOT NULL,
  `avatarwidth` int(10) unsigned NOT NULL,
  `avatarheight` int(10) unsigned NOT NULL,
  `nameleft` int(10) unsigned NOT NULL,
  `nametop` int(10) unsigned NOT NULL,
  `namesize` int(10) unsigned NOT NULL,
  `newbie_credit` int(10) unsigned NOT NULL,
  `click_credit` int(10) unsigned NOT NULL,
  `sub_click_credit` int(10) unsigned NOT NULL,
  `keyword` varchar(50) NOT NULL,
  `reply1` varchar(255) NOT NULL,
  `reply2` varchar(255) NOT NULL,
  `color` varchar(10) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>