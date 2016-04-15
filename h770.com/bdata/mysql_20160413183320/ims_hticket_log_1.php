<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hticket_log`;");
E_C("CREATE TABLE `ims_hticket_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `orderid` int(11) unsigned NOT NULL COMMENT '对应订单号',
  `actid` int(11) unsigned NOT NULL COMMENT '活动ID',
  `scanown` varchar(50) NOT NULL COMMENT '刷卡人openid',
  `type` int(2) unsigned NOT NULL DEFAULT '1' COMMENT '日志类型',
  `remark` varchar(100) NOT NULL COMMENT '备注',
  `createtime` int(11) unsigned NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `orderid` (`orderid`),
  KEY `actid` (`actid`),
  KEY `scanown` (`scanown`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>