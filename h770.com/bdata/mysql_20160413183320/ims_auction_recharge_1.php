<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_auction_recharge`;");
E_C("CREATE TABLE `ims_auction_recharge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `status` smallint(4) NOT NULL DEFAULT '0' COMMENT '0未支付,1为已付款',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为余额支付,2为支付宝,3为微信支付,4为定价返还',
  `transid` varchar(30) NOT NULL COMMENT '微信订单号',
  `price` int(10) unsigned NOT NULL COMMENT '充值金额',
  `createtime` int(10) unsigned NOT NULL COMMENT '充值时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `from_user` (`from_user`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>