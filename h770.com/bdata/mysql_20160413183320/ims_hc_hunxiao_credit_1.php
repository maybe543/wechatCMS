<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hc_hunxiao_credit`;");
E_C("CREATE TABLE `ims_hc_hunxiao_credit` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `mid` int(10) unsigned NOT NULL COMMENT '粉丝ID',
  `orderid` int(10) unsigned NOT NULL COMMENT '订单ID',
  `goodsid` int(10) unsigned NOT NULL COMMENT '商品ID',
  `credit` int(5) NOT NULL DEFAULT '0' COMMENT '积分',
  `flag` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '获得积分类型，0为签到获得, 1为购买获得',
  `status` tinyint(2) NOT NULL DEFAULT '0' COMMENT '-1为购买记录，0为未兑换，1为已兑换',
  `total` int(5) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL DEFAULT '0',
  `convertime` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>