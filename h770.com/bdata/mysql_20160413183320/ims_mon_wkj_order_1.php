<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_wkj_order`;");
E_C("CREATE TABLE `ims_mon_wkj_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `kid` int(10) NOT NULL,
  `uid` int(10) NOT NULL,
  `uname` varchar(100) DEFAULT NULL,
  `address` varchar(100) DEFAULT NULL,
  `tel` varchar(50) DEFAULT NULL,
  `openid` varchar(200) DEFAULT NULL,
  `order_no` varchar(100) DEFAULT NULL,
  `wxorder_no` varchar(100) DEFAULT NULL,
  `y_price` float(10,2) DEFAULT NULL,
  `kh_price` float(10,2) DEFAULT NULL,
  `yf_price` float(10,2) DEFAULT NULL,
  `total_price` float(10,2) DEFAULT NULL,
  `pay_type` int(2) DEFAULT NULL,
  `p_model` varchar(1000) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `wxnotify` varchar(200) DEFAULT NULL,
  `notifytime` int(10) DEFAULT '0',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>