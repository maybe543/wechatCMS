<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_auction_record`;");
E_C("CREATE TABLE `ims_auction_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL COMMENT '公众账号',
  `from_user` varchar(50) NOT NULL COMMENT '微信会员openID',
  `nickname` varchar(20) NOT NULL COMMENT '用户昵称',
  `uid` int(10) unsigned NOT NULL COMMENT '用户ID',
  `sid` int(10) unsigned NOT NULL COMMENT '商品编号',
  `ordersn` varchar(20) NOT NULL COMMENT '订单编号',
  `price` int(10) unsigned NOT NULL COMMENT '交易价格',
  `bond` int(10) unsigned NOT NULL COMMENT '保证金',
  `createtime` int(10) unsigned NOT NULL COMMENT '购买时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `sid` (`sid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>