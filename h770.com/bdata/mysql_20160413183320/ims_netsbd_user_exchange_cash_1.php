<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_netsbd_user_exchange_cash`;");
E_C("CREATE TABLE `ims_netsbd_user_exchange_cash` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `cash` int(10) DEFAULT NULL,
  `cash_type` int(1) DEFAULT NULL COMMENT '1支付宝\r\n            2微信',
  `remark` varchar(50) DEFAULT NULL,
  `state` int(1) DEFAULT NULL COMMENT '0 未审核\r\n            1 提现中\r\n            2 已完成',
  `createtime` int(10) DEFAULT NULL,
  `finishtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='会员提现表'");

require("../../inc/footer.php");
?>