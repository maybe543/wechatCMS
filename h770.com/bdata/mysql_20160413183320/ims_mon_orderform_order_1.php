<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_orderform_order`;");
E_C("CREATE TABLE `ims_mon_orderform_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `orderno` varchar(100) DEFAULT NULL,
  `outno` varchar(300) DEFAULT NULL,
  `acid` varchar(100) DEFAULT NULL,
  `fid` int(10) DEFAULT NULL,
  `iid` int(10) DEFAULT NULL,
  `openid` varchar(200) NOT NULL,
  `nickname` varchar(300) DEFAULT NULL,
  `headimgurl` varchar(300) DEFAULT NULL,
  `uname` varchar(300) DEFAULT NULL,
  `utel` varchar(50) DEFAULT NULL,
  `ordertime` int(10) DEFAULT NULL,
  `paytime` int(10) DEFAULT NULL,
  `ordernum` int(3) DEFAULT NULL,
  `o_yprice` float(6,2) DEFAULT NULL,
  `o_xprice` float(6,2) DEFAULT NULL,
  `zf_price` float(6,2) DEFAULT NULL,
  `js_price` float(6,2) DEFAULT NULL,
  `pay_type` int(3) DEFAULT NULL,
  `remark` varchar(2000) DEFAULT NULL,
  `status` int(1) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>