<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_planting_award`;");
E_C("CREATE TABLE `ims_stonefish_planting_award` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `rid` int(11) DEFAULT '0',
  `fid` int(11) DEFAULT '0',
  `from_user` varchar(50) DEFAULT '0' COMMENT '用户ID',
  `prizeid` int(11) DEFAULT '0' COMMENT '奖品ID',
  `shengzhangid` tinyint(1) DEFAULT '0' COMMENT '种子生成级别',
  `prizename` varchar(50) DEFAULT '' COMMENT '奖品名称',
  `prizetype` varchar(10) DEFAULT '' COMMENT '类型',
  `description` varchar(200) DEFAULT '' COMMENT '描述',
  `createtime` int(10) DEFAULT '0' COMMENT '中奖时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '领奖时间',
  `status` tinyint(1) DEFAULT '0' COMMENT '状态0为未领取1为领取',
  `xuni` tinyint(1) DEFAULT '0',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '兑奖人ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '兑奖人姓名',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>