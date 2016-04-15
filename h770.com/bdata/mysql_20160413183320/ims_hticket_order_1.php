<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hticket_order`;");
E_C("CREATE TABLE `ims_hticket_order` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `openid` varchar(50) NOT NULL,
  `actid` int(11) unsigned NOT NULL COMMENT '活动ID',
  `type` varchar(20) NOT NULL COMMENT '支付类型',
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '费用',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '下单1已付款2已核销3已关闭4',
  `scanown` varchar(50) NOT NULL COMMENT '审核人',
  `remark` varchar(50) NOT NULL COMMENT '备注说明',
  `createtime` int(11) unsigned NOT NULL COMMENT '创建时间',
  `paytime` int(11) unsigned NOT NULL COMMENT '支付时间',
  `scantime` int(11) unsigned NOT NULL COMMENT '核销时间',
  `closetime` int(11) unsigned NOT NULL COMMENT '关闭时间',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `openid` (`openid`),
  KEY `actid` (`actid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>