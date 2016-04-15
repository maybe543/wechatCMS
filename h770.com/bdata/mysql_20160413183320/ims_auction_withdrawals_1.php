<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_auction_withdrawals`;");
E_C("CREATE TABLE `ims_auction_withdrawals` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `status` smallint(4) NOT NULL COMMENT '0为提现中,1为提现成功，2提现失败',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为银行卡,2为支付宝',
  `price` int(10) unsigned NOT NULL COMMENT '提现金额',
  `createtime` int(10) unsigned NOT NULL COMMENT '申请时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>