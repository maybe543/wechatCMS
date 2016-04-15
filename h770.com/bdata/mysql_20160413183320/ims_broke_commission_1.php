<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_broke_commission`;");
E_C("CREATE TABLE `ims_broke_commission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `mid` int(10) unsigned NOT NULL COMMENT '经纪人ID',
  `cid` int(10) unsigned DEFAULT NULL COMMENT '客户ID',
  `commission` int(10) unsigned NOT NULL COMMENT '佣金',
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `flag` tinyint(1) DEFAULT '0',
  `opid` int(10) unsigned DEFAULT '0' COMMENT '操作员ID经理或销售或管理员',
  `opname` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=98 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>