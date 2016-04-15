<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hx_dialect_reply`;");
E_C("CREATE TABLE `ims_hx_dialect_reply` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `uniacid` int(10) NOT NULL,
  `r_name` varchar(200) NOT NULL,
  `r_title` varchar(200) NOT NULL,
  `thumb` varchar(1000) NOT NULL,
  `num` int(10) unsigned NOT NULL,
  `s_title` varchar(200) NOT NULL,
  `s_icon` varchar(1000) NOT NULL,
  `s_des` varchar(1000) NOT NULL,
  `s_cancel` varchar(200) NOT NULL,
  `s_share` varchar(2000) NOT NULL,
  `s_sucai` varchar(2000) NOT NULL,
  `py_1` varchar(200) NOT NULL,
  `py_2` varchar(200) NOT NULL,
  `py_3` varchar(200) NOT NULL,
  `py_4` varchar(200) NOT NULL,
  `py_5` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>