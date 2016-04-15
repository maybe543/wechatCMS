<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_fighting_doings`;");
E_C("CREATE TABLE `ims_stonefish_fighting_doings` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `realname` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `mobile` varchar(15) NOT NULL DEFAULT '' COMMENT '电话',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态',
  `createtime` int(10) unsigned NOT NULL COMMENT '时间',
  `verifytime` int(10) unsigned NOT NULL COMMENT '验证时间',
  `welfare` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '中奖倍数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>