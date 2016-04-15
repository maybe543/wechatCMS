<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jufeng_wcy_order`;");
E_C("CREATE TABLE `ims_jufeng_wcy_order` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `from_user` varchar(50) NOT NULL,
  `pcate` int(10) unsigned NOT NULL,
  `mobile` bigint(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `ordersn` varchar(20) NOT NULL,
  `price` varchar(10) NOT NULL,
  `status` tinyint(4) NOT NULL COMMENT '-2已删除，-1已取消，0已完成，1等待支付，2已下单，3已确认',
  `paytype` tinyint(1) unsigned NOT NULL COMMENT '1为在线付款，2为餐到付款',
  `other` varchar(100) NOT NULL DEFAULT '',
  `time` varchar(20) NOT NULL DEFAULT '',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>