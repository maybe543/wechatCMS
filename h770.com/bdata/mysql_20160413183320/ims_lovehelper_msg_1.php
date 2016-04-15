<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_lovehelper_msg`;");
E_C("CREATE TABLE `ims_lovehelper_msg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text NOT NULL,
  `fromuser` varchar(50) NOT NULL,
  `bgimage` varchar(200) NOT NULL,
  `viewcount` int(11) NOT NULL DEFAULT '1',
  `forward` int(11) NOT NULL,
  `praise` int(11) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=131 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>