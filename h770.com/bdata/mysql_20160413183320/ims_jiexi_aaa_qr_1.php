<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jiexi_aaa_qr`;");
E_C("CREATE TABLE `ims_jiexi_aaa_qr` (
  `uniacid` int(10) unsigned NOT NULL,
  `scene_id` varchar(50) NOT NULL,
  `qr_url` varchar(1024) NOT NULL,
  `media_id` varchar(1024) NOT NULL,
  `createtime` int(11) NOT NULL,
  `poster_id` int(10) NOT NULL DEFAULT '0',
  `openid` varchar(100) NOT NULL,
  PRIMARY KEY (`uniacid`,`scene_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>