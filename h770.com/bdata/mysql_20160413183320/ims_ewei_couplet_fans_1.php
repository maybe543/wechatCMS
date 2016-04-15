<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_couplet_fans`;");
E_C("CREATE TABLE `ims_ewei_couplet_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(100) DEFAULT '' COMMENT '用户ID',
  `nickname` varchar(255) DEFAULT '' COMMENT '昵称',
  `headurl` varchar(255) DEFAULT '' COMMENT '头像',
  `area` varchar(255) DEFAULT '' COMMENT '地区',
  `realname` varchar(255) DEFAULT '' COMMENT '姓名',
  `mobile` varchar(255) DEFAULT '' COMMENT '手机',
  `uptext` text COMMENT '上联',
  `downtext` text COMMENT '下联',
  `rule` text COMMENT '规则',
  `helps` int(11) DEFAULT '0' COMMENT '被帮助数',
  `status` tinyint(1) DEFAULT '0' COMMENT '0 未中奖 1 已中奖 2 已兑奖',
  `num` int(11) DEFAULT '0' COMMENT '抽中个数',
  `log` tinyint(1) DEFAULT '0',
  `sim` tinyint(1) DEFAULT '0',
  `createtime` int(10) DEFAULT '0' COMMENT '参与时间',
  `consumetime` int(10) DEFAULT '0' COMMENT '兑奖时间',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>