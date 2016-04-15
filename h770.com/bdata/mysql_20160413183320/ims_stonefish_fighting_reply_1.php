<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_fighting_reply`;");
E_C("CREATE TABLE `ims_stonefish_fighting_reply` (
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
  `visubscribetime` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '助力时效跳转',
  `sys_users` varchar(500) NOT NULL DEFAULT '' COMMENT '系统会员组ID',
  `sys_users_tips` varchar(300) DEFAULT '' COMMENT '会员组提示',
  `fansnum` int(10) DEFAULT '0' COMMENT '参与人数',
  `viewnum` int(10) DEFAULT '0' COMMENT '访问次数',
  `viewranknum` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '排行榜人数',
  `copyright` varchar(20) DEFAULT '' COMMENT '版权',
  `msgadpic` varchar(1000) DEFAULT '' COMMENT '消息提示广告图',
  `msgadpictime` tinyint(1) unsigned NOT NULL DEFAULT '5' COMMENT '消息提示时效',
  `power` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否获取头像昵称1opneid 2头像昵称',
  `poweravatar` varchar(3) DEFAULT '0' COMMENT '头像大小',
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
  `question` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '题库类型0单库1多库',
  `questiontype` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '题库单库ID',
  `questionnum` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '问题数量',
  `tid` varchar(2000) NOT NULL COMMENT '问题多库ID',
  `notquestionnum` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '问题未答完是否继续1继续0不继续',
  `marking` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '跳过扣分',
  `skip` tinyint(1) unsigned NOT NULL COMMENT '1允许跳过0不允许',
  `answertime` int(10) unsigned NOT NULL COMMENT '答题时间',
  `timeout` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '答题时间超时间是否默认选择答案答题',
  `number_times` int(10) unsigned NOT NULL COMMENT '参与总次数',
  `number_days` int(10) unsigned NOT NULL COMMENT '每天可参与次数',
  `ishelp` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启求助',
  `helptime` int(10) unsigned NOT NULL DEFAULT '60' COMMENT '求助时效',
  `isgroup` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否开启组团',
  `prizestype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '奖项类型',
  `premise` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '抽奖前提',
  `prizesnum` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '兑奖次数',
  `urlrid` int(10) unsigned NOT NULL COMMENT '活动rid',
  `yanzheng` tinyint(1) unsigned NOT NULL COMMENT '是否验证手机号',
  `createtime` int(10) DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>