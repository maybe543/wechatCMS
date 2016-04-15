<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hticket_activity`;");
E_C("CREATE TABLE `ims_hticket_activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '活动标题',
  `description` varchar(100) NOT NULL COMMENT '活动描述',
  `shareimg` varchar(100) NOT NULL,
  `singleimg` varchar(100) NOT NULL COMMENT '单页图片地址',
  `content` text NOT NULL,
  `status` tinyint(1) unsigned DEFAULT '0' COMMENT '活动状态',
  `starttime` int(11) unsigned NOT NULL COMMENT '开始时间',
  `endtime` int(11) unsigned NOT NULL COMMENT '结束时间',
  `place` varchar(100) NOT NULL COMMENT '活动地点',
  `createtime` int(11) unsigned NOT NULL COMMENT '创建时间',
  `extype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '展示类型0内容1图片',
  `proname` varchar(50) NOT NULL COMMENT '产品名称',
  `tlimit` int(11) unsigned NOT NULL COMMENT '票数限制',
  `scantimes` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '卡可刷次数',
  `fee` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '票价',
  `author` varchar(50) NOT NULL COMMENT '主办单位',
  `groups` varchar(200) NOT NULL COMMENT '核销用户组',
  `viewnums` int(11) unsigned NOT NULL COMMENT '浏览次数',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>