<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hc_hunxiao_memberrelative`;");
E_C("CREATE TABLE `ims_hc_hunxiao_memberrelative` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `orderid` int(10) unsigned NOT NULL,
  `goodsid` int(10) unsigned NOT NULL,
  `shareid` int(10) unsigned NOT NULL,
  `userdefault` int(10) unsigned NOT NULL COMMENT '分销级数标识',
  `commission` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '该订单的推荐佣金',
  `applytime` int(10) unsigned DEFAULT NULL COMMENT '申请时间',
  `checktime` int(10) unsigned DEFAULT NULL COMMENT '审核时间',
  `flag` tinyint(4) NOT NULL DEFAULT '0' COMMENT '-1取消状态，0普通状态，1为已付款，2为已发货，3为成功',
  `status` tinyint(3) DEFAULT '0' COMMENT '申请状态，-2为标志删除，-1为审核无效，0为未申请，1为正在申请，2为审核通过',
  `content` text,
  `total` int(10) unsigned NOT NULL DEFAULT '1',
  `isjieyong` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>