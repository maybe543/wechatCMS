<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hc_hunxiao_commission`;");
E_C("CREATE TABLE `ims_hc_hunxiao_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL COMMENT '粉丝ID',
  `ogid` int(10) unsigned DEFAULT NULL COMMENT '订单商品ID',
  `commission` decimal(10,2) NOT NULL COMMENT '佣金',
  `content` text,
  `flag` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为账户充值记录，1为提现记录, 2为积分兑换提现记录',
  `isout` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未导出，1为已导出',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>