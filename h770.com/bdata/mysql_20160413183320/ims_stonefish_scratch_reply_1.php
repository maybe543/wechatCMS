<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_scratch_reply`;");
E_C("CREATE TABLE `ims_stonefish_scratch_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `templateid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动模板ID',
  `title` varchar(50) DEFAULT '' COMMENT '活动标题',
  `description` varchar(255) DEFAULT '' COMMENT '活动简介',
  `start_picurl` varchar(200) DEFAULT '' COMMENT '活动开始图片',
  `end_title` varchar(50) DEFAULT '' COMMENT '结束标题',
  `end_description` varchar(200) DEFAULT '' COMMENT '活动结束简介',
  `end_picurl` varchar(200) DEFAULT '' COMMENT '活动结束图片',
  `isshow` tinyint(1) DEFAULT '1' COMMENT '活动是否停止0为暂停1为活动中',
  `starttime` int(10) DEFAULT '0' COMMENT '开始时间',
  `endtime` int(10) DEFAULT '0' COMMENT '结束时间',
  `music` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否打开背景音乐',
  `musicurl` varchar(255) NOT NULL DEFAULT '' COMMENT '背景音乐地址',
  `mauto` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '音乐是否自动播放',
  `mloop` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否循环播放',
  `issubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与类型0为任意1为关注粉丝2为会员',
  `visubscribe` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型',
  `fansnum` int(10) DEFAULT '0' COMMENT '参与人数',
  `viewnum` int(10) DEFAULT '0' COMMENT '访问次数',
  `prize_num` int(10) DEFAULT '0' COMMENT '奖品总数',
  `award_num` int(11) DEFAULT '0' COMMENT '每人最多获奖次数',
  `award_num_tips` varchar(100) DEFAULT '' COMMENT '超过中奖数量提示',
  `number_times` int(11) DEFAULT '0' COMMENT '每人最多参与次数',
  `number_times_tips` varchar(100) DEFAULT '' COMMENT '超过总次数提示',
  `day_number_times` int(11) DEFAULT '0' COMMENT '每人每天最多参与次数',
  `day_number_times_tips` varchar(100) DEFAULT '' COMMENT '超过每天次数提示',
  `viewawardnum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '首页显示中奖人数',
  `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数',
  `showprize` tinyint(1) DEFAULT '0' COMMENT '是否显示奖品',
  `prizeinfo` text NOT NULL COMMENT '奖品详细介绍',
  `awardtext` varchar(1000) DEFAULT '' COMMENT '中奖提示文字',
  `notawardtext` varchar(1000) DEFAULT '' COMMENT '没有中奖提示文字',
  `notprizetext` varchar(1000) DEFAULT '' COMMENT '没有奖品提示文字',
  `tips` varchar(200) DEFAULT '' COMMENT '活动次数提示',
  `copyright` varchar(20) DEFAULT '' COMMENT '版权',
  `inpointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值1',
  `inpointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '初始分值2',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取助力者头像昵称1opneid 2头像昵称',
  `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小',
  `powertype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '助力类型0访问助力1点击助力',
  `randompointstart` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围开始数',
  `randompointend` float(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '助力随机金额范围结束数',
  `addp` tinyint(1) DEFAULT '100' COMMENT '好友助力机率%',
  `limittype` tinyint(1) DEFAULT '0' COMMENT '限制类型0为只能一次1为每天一次',
  `totallimit` tinyint(1) DEFAULT '1' COMMENT '好友助力总次数制',
  `helptype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '互助0为互助1为禁止',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '500' COMMENT '虚拟人数',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '100' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟更新时间',
  `adpic` varchar(255) DEFAULT '' COMMENT '活动页顶部广告图',
  `adpicurl` varchar(255) DEFAULT '' COMMENT '活动页顶部广告链接',
  `homepictime` tinyint(1) unsigned NOT NULL COMMENT '首页秒显图片显示时间',
  `homepictype` tinyint(1) unsigned NOT NULL COMMENT '首页广告类型1为每次2为每天3为每周4为仅1次',
  `homepic` varchar(225) NOT NULL COMMENT '首页秒显图片',
  `opportunity` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '参与次数选项0活动设置1商户赠送2为积分购买',
  `opportunity_txt` text NOT NULL COMMENT '商户赠送/积分购买说明',
  `credit_type` varchar(20) DEFAULT '' COMMENT '积分类型',
  `credit_value` int(11) DEFAULT '0' COMMENT '积分购买多少积分',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>