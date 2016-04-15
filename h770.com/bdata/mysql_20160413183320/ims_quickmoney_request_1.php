<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickmoney_request`;");
E_C("CREATE TABLE `ims_quickmoney_request` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user_realname` varchar(50) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `realname` varchar(200) NOT NULL,
  `mobile` varchar(200) NOT NULL,
  `alipay` varchar(200) NOT NULL,
  `bankcard` varchar(200) NOT NULL,
  `bankname` varchar(200) NOT NULL,
  `note` varchar(200) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '100.00' COMMENT '消耗余额数',
  `userchangecost` int(10) NOT NULL DEFAULT '0' COMMENT '用户是否可以修改兑换值',
  `goods_id` int(10) unsigned NOT NULL,
  `exchangetype` int(10) NOT NULL DEFAULT '1' COMMENT '1支付宝 2银行卡 3微信支付',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>