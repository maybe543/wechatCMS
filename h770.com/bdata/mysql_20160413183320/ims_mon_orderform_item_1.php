<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_orderform_item`;");
E_C("CREATE TABLE `ims_mon_orderform_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `fid` int(10) DEFAULT NULL,
  `iname` varchar(200) DEFAULT NULL,
  `ititle` varchar(500) DEFAULT NULL,
  `ititle_pg` varchar(500) DEFAULT NULL,
  `ititle_url` varchar(500) DEFAULT NULL,
  `y_price` float(6,2) DEFAULT NULL,
  `x_price` float(6,2) DEFAULT NULL,
  `i_desc` text,
  `i_summary` varchar(50) DEFAULT NULL,
  `o_tel` varchar(50) DEFAULT NULL,
  `pay_type` int(1) DEFAULT NULL,
  `o_num` int(3) DEFAULT NULL,
  `displayorder` int(3) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>