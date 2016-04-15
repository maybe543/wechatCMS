<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_planting_reply`;");
E_C("CREATE TABLE `ims_stonefish_planting_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(11) DEFAULT '0' COMMENT '公众号ID',
  `title` varchar(50) DEFAULT '' COMMENT '活动名称',
  `description` varchar(255) DEFAULT '' COMMENT '活动简介',
  `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片',
  `isshow` tinyint(1) DEFAULT '0',
  `award_times` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `award_type` tinyint(1) DEFAULT '0' COMMENT '抽奖机会1为保留0为越级',
  `ticket_information` varchar(200) DEFAULT '' COMMENT '兑奖信息',
  `starttime` int(10) DEFAULT '0' COMMENT '活动开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '活动结束时间',
  `end_theme` varchar(50) DEFAULT '' COMMENT '结束标题',
  `end_instruction` varchar(200) DEFAULT '' COMMENT '活动结束简介',
  `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片',
  `adpic` varchar(200) DEFAULT '' COMMENT '活动页顶部广告图',
  `adpicurl` varchar(200) DEFAULT '' COMMENT '活动页顶部广告链接',
  `total_num` int(11) DEFAULT '0' COMMENT '奖品数量(自动加)',
  `copyright` varchar(20) DEFAULT '' COMMENT '自定义版权',
  `show_num` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品数量',
  `viewnum` int(11) DEFAULT '0' COMMENT '浏览次数',
  `fansnum` int(11) DEFAULT '0' COMMENT '参与人数',
  `seedid` varchar(100) NOT NULL COMMENT '种子集',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `awardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页滚动中奖人数显示',
  `createtime` int(10) DEFAULT '0' COMMENT '活动创建时间',
  `ticketinfo` varchar(50) DEFAULT '' COMMENT '兑奖参数提示词',
  `tickettype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖类型1为前端后台2为店员3为商家网点',
  `duijiangtype` tinyint(1) unsigned NOT NULL DEFAULT '2' COMMENT '库存类型1抽中减少2兑奖减少',
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
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `homepictime` int(3) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与选项 0任何人1关注粉丝2为商户赠送',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送参数说明',
  `award_info` text NOT NULL COMMENT '奖品详细介绍',
  `credit_times` tinyint(1) DEFAULT '0',
  `credit_type` varchar(20) DEFAULT '',
  `showparameters` varchar(1000) NOT NULL COMMENT '显示界面参数：背景色、背景图以及文字色等',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>