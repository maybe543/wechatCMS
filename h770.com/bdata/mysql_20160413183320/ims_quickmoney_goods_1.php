<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickmoney_goods`;");
E_C("CREATE TABLE `ims_quickmoney_goods` (
  `goods_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `amount` int(11) NOT NULL DEFAULT '0',
  `deadline` datetime NOT NULL,
  `per_user_limit` int(11) NOT NULL DEFAULT '0',
  `cost` decimal(10,2) NOT NULL DEFAULT '100.00' COMMENT '消耗余额数',
  `userchangecost` int(10) NOT NULL DEFAULT '0' COMMENT '用户是否可以修改兑换值',
  `exchangetype` int(10) NOT NULL DEFAULT '1' COMMENT '1支付宝 2银行卡 3微信支付',
  `vip_require` int(10) NOT NULL DEFAULT '0' COMMENT '兑换最低VIP级别',
  `content` text NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`goods_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>