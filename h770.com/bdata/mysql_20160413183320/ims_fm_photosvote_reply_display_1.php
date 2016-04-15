<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_reply_display`;");
E_C("CREATE TABLE `ims_fm_photosvote_reply_display` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `istopheader` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '顶部导航',
  `ipannounce` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '顶部公告',
  `isbgaudio` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '背景音乐',
  `bgmusic` varchar(125) NOT NULL COMMENT '背景音乐链接',
  `isedes` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启首页显示说明',
  `tmoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '首页显示模式',
  `indextpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '首页列表显示数',
  `indexorder` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '首页排序',
  `indexpx` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '活动首页显示,0 按最新排序 1 按人气排序 3 按投票数排序',
  `phbtpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人气榜显示个数',
  `zanzhums` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '赞助商显示',
  `xuninum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人数',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击量',
  `xuninumtime` int(10) unsigned NOT NULL DEFAULT '86400' COMMENT '虚拟间隔时间',
  `xuninuminitial` int(10) unsigned NOT NULL DEFAULT '10' COMMENT '虚拟随机数值1',
  `xuninumending` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '虚拟随机数值2',
  `xuninum_time` int(10) unsigned NOT NULL COMMENT '虚拟随机数值',
  `isrealname` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入姓名0为不需要1为需要',
  `ismobile` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入手机号0为不需要1为需要',
  `isweixin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入微信号0为不需要1为需要',
  `isqqhao` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入QQ号0为不需要1为需要',
  `isemail` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入邮箱0为不需要1为需要',
  `isjob` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入职业0为不需要1为需要',
  `isxingqu` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入兴趣0为不需要1为需要',
  `isaddress` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否需要输入地址0为不需要1为需要',
  `isindex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否首页显示0为不需要1为需要',
  `isreg` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否报名页显示0为不需要1为需要',
  `isdes` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否描述页显示0为不需要1为需要',
  `isvotexq` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否详情页显示0为不需要1为需要',
  `ispaihang` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否排行页显示0为不需要1为需要',
  `lapiao` varchar(5) NOT NULL COMMENT '拉票',
  `sharename` varchar(2) NOT NULL COMMENT '分享名字',
  `tpname` varchar(100) NOT NULL COMMENT '投票名称',
  `tpsname` varchar(100) NOT NULL COMMENT '投票数名称',
  `rqname` varchar(100) NOT NULL COMMENT '人气名称',
  `csrs` varchar(10) NOT NULL COMMENT '参赛作品',
  `ljtp` varchar(10) NOT NULL COMMENT '累计投票',
  `cyrs` varchar(10) NOT NULL COMMENT '参与人数',
  `iscopyright` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0显示公众号版权1为显示自定义版权',
  `copyrighturl` varchar(255) NOT NULL COMMENT '版权链接',
  `copyright` varchar(50) NOT NULL COMMENT '版权',
  `isvoteusers` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否显示投票人',
  `regtitlearr` varchar(500) NOT NULL COMMENT '姓名自定义',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>