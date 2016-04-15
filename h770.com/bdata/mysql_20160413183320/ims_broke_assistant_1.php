<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_broke_assistant`;");
E_C("CREATE TABLE `ims_broke_assistant` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `company` varchar(50) DEFAULT NULL,
  `code` varchar(20) NOT NULL,
  `status` tinyint(1) DEFAULT '1',
  `flag` tinyint(1) DEFAULT '0' COMMENT '0为销售员，1为经理',
  `content` text,
  `createtime` int(10) NOT NULL,
  `loupan` int(10) NOT NULL DEFAULT '0',
  `pwd` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>