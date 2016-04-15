<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_bigwheel_exchange`;");
E_C("CREATE TABLE `ims_stonefish_bigwheel_exchange` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `awardingtype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '单独兑奖1统一兑奖2',
  `beihuo` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启备货1开启0关闭',
  `beihuo_tips` varchar(20) DEFAULT '' COMMENT '备货提示词',
  `awardingpas` varchar(10) DEFAULT '' COMMENT '兑奖密码',
  `inventory` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖后库存1中奖减少2为兑奖后减少',
  `awardingstarttime` int(10) DEFAULT '0' COMMENT '兑奖开始时间',
  `awardingendtime` int(10) DEFAULT '0' COMMENT '兑奖结束时间',
  `awarding_tips` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `awardingaddress` varchar(50) DEFAULT '' COMMENT '兑奖地点',
  `awardingtel` varchar(50) DEFAULT '' COMMENT '兑奖电话',
  `baidumaplng` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `baidumaplat` varchar(10) DEFAULT '' COMMENT '兑奖导航',
  `before` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖资料活动前还是中奖后1前2为后',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isqq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isgender` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入性别0为不需要1为需要',
  `istelephone` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入固定电话0为不需要1为需要',
  `isidcard` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入证件号码0为不需要1为需要',
  `iscompany` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入公司名称0为不需要1为需要',
  `isoccupation` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isposition` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职位0为不需要1为需要',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `isfansname` varchar(225) NOT NULL DEFAULT '真实姓名,手机号码,QQ号,邮箱,地址,性别,固定电话,证件号码,公司名称,职业,职位' COMMENT '显示字段名称',
  `tmplmsg_participate` int(11) DEFAULT '0' COMMENT '参与消息模板',
  `tmplmsg_winning` int(11) DEFAULT '0' COMMENT '中奖消息模板',
  `tmplmsg_exchange` int(11) DEFAULT '0' COMMENT '兑奖消息模板',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>