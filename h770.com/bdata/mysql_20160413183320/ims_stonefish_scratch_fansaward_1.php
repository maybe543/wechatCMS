<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_scratch_fansaward`;");
E_C("CREATE TABLE `ims_stonefish_scratch_fansaward` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户openid',
  `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID',
  `codesn` varchar(20) DEFAULT '0' COMMENT '中奖唯一码',
  `createtime` int(10) DEFAULT '0' COMMENT '领取时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '使用时间',
  `openstatus` tinyint(1) DEFAULT '0' COMMENT '是否拆开',
  `zhongjiangtime` int(10) DEFAULT '0' COMMENT '中奖时间',
  `zhongjiang` tinyint(1) DEFAULT '0' COMMENT '是否中奖0未中奖1中奖2兑奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_prizeid` (`prizeid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>