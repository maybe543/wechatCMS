<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_users`;");
E_C("CREATE TABLE `ims_cate_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `admin` tinyint(3) unsigned DEFAULT '0',
  `admintobe` tinyint(3) unsigned DEFAULT '0',
  `userid` int(10) unsigned DEFAULT '0',
  `openid` varchar(50) DEFAULT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `sex` varchar(10) DEFAULT '女',
  `address` varchar(255) DEFAULT NULL,
  `addressarr` text,
  `addressz` text,
  `money` decimal(10,2) DEFAULT '0.00' COMMENT '消费金额',
  `moneyone` decimal(10,2) DEFAULT '0.00' COMMENT '但是消费金额(最高数)',
  `moneyover` decimal(10,2) DEFAULT '0.00' COMMENT '账户余额',
  `ofdate` bigint(18) unsigned DEFAULT '0' COMMENT '最后消费时间',
  `indate` bigint(18) unsigned DEFAULT '0' COMMENT '注册时间',
  `enddate` bigint(18) unsigned DEFAULT '0' COMMENT '到期时间',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微餐饮 - 会员'");

require("../../inc/footer.php");
?>