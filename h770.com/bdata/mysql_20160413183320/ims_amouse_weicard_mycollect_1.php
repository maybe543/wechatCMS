<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_weicard_mycollect`;");
E_C("CREATE TABLE `ims_amouse_weicard_mycollect` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `mid` int(10) NOT NULL,
  `cid` int(10) NOT NULL,
  `from_user` varchar(255) NOT NULL,
  `collect_mid` int(10) NOT NULL,
  `collect_cid` int(10) NOT NULL,
  `collect_from_user` varchar(255) NOT NULL,
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>