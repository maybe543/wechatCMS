<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_moneygo_record`;");
E_C("CREATE TABLE `ims_moneygo_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from_user` varchar(50) NOT NULL COMMENT '微信会员ID',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称',
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `sid` int(10) unsigned NOT NULL COMMENT '商品编号',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0未支付，1为已付款',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额支付，2为支付宝，3为微信支付',
  `transid` varchar(30) NOT NULL COMMENT '微信订单号',
  `count` int(10) unsigned NOT NULL COMMENT '商品数量',
  `s_codes` longtext COMMENT '商品码',
  `createtime` int(10) unsigned NOT NULL COMMENT '购买时间',
  `zongji` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>