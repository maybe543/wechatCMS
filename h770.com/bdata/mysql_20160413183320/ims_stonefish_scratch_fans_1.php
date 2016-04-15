<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_scratch_fans`;");
E_C("CREATE TABLE `ims_stonefish_scratch_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `avatar` varchar(512) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `qq` varchar(15) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(50) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '联系地址',
  `gender` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性别',
  `telephone` varchar(15) NOT NULL DEFAULT '' COMMENT '固定电话',
  `idcard` varchar(30) NOT NULL DEFAULT '' COMMENT '证件号码',
  `company` varchar(50) NOT NULL DEFAULT '' COMMENT '公司名称',
  `occupation` varchar(30) NOT NULL DEFAULT '' COMMENT '职业',
  `position` varchar(30) NOT NULL DEFAULT '' COMMENT '职位',
  `inpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '起始数',
  `outpoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '已兑换数',
  `sharepoint` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '分享助力',
  `sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `share_num` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `createtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `lasttime` int(10) unsigned NOT NULL COMMENT '最后参与时间',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `ticketid` int(11) DEFAULT '0' COMMENT '店员或商家网点ID',
  `ticketname` varchar(50) DEFAULT '' COMMENT '店员或商家网点名称',
  `zhongjiang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否中奖',
  `xuni` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否虚拟中奖',
  `todaynum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '今日参与次数',
  `totalnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '总参与次数',
  `tosharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享使用次数',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '获奖次数',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>