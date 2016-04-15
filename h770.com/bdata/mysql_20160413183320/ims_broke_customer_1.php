<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_broke_customer`;");
E_C("CREATE TABLE `ims_broke_customer` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `mobile` varchar(11) NOT NULL COMMENT '手机号码',
  `realname` varchar(50) NOT NULL,
  `loupan` int(10) unsigned NOT NULL,
  `status` tinyint(3) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `flag` tinyint(1) DEFAULT '0',
  `cid` int(10) unsigned DEFAULT '0' COMMENT '该客户从属于某销售员',
  `allottime` int(10) DEFAULT NULL COMMENT '分配时间',
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=68 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>