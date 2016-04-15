<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_weicard_member`;");
E_C("CREATE TABLE `ims_amouse_weicard_member` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) DEFAULT NULL,
  `realname` varchar(50) DEFAULT NULL,
  `mobile` varchar(11) DEFAULT NULL,
  `company` varchar(100) DEFAULT NULL,
  `job` varchar(50) DEFAULT NULL,
  `qq` varchar(50) DEFAULT NULL,
  `industry` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `weixin` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `headimg` varchar(255) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `myattention` varchar(255) DEFAULT NULL,
  `myfocus` varchar(255) DEFAULT NULL,
  `createtime` int(11) DEFAULT NULL,
  `companyAddress` varchar(255) DEFAULT NULL,
  `lat` decimal(18,10) DEFAULT '0.0000000000',
  `lng` decimal(18,10) DEFAULT '0.0000000000',
  `status` tinyint(1) DEFAULT NULL COMMENT '0表示已审核，1表示未审核，2表示禁用',
  `qianming` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>