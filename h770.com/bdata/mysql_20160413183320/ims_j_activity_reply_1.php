<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_j_activity_reply`;");
E_C("CREATE TABLE `ims_j_activity_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `weid` int(10) DEFAULT '0',
  `fid` int(10) DEFAULT '0' COMMENT '外链接ID',
  `pcate` int(10) DEFAULT '0' COMMENT '分类',
  `ccate` int(10) DEFAULT '0' COMMENT '分类',
  `picture` varchar(200) NOT NULL COMMENT '活动图片',
  `qrcode` varchar(200) NOT NULL DEFAULT '' COMMENT '二维码',
  `clientpic` varchar(200) NOT NULL COMMENT '转发图片',
  `title` varchar(100) NOT NULL COMMENT '活动标题',
  `description` varchar(100) NOT NULL COMMENT '转发介绍',
  `info` varchar(2000) NOT NULL COMMENT '活动介绍',
  `rule` text NOT NULL COMMENT '规则描述',
  `content` text NOT NULL COMMENT '活动完成介绍',
  `organizer` varchar(100) NOT NULL COMMENT '活动主办方',
  `charge` double(10,2) NOT NULL DEFAULT '0.00' COMMENT '报名费用',
  `applicants` int(10) NOT NULL DEFAULT '0' COMMENT '报名名额',
  `quota` int(10) NOT NULL DEFAULT '0' COMMENT '名额',
  `joinstarttime` int(10) unsigned NOT NULL COMMENT '报名开始时间',
  `joinendtime` int(10) unsigned NOT NULL COMMENT '报名结束时间',
  `starttime` int(10) unsigned NOT NULL COMMENT '活动开始时间',
  `endtime` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '活动结束时间',
  `status` tinyint(1) NOT NULL DEFAULT '0',
  `appendcode` varchar(10) NOT NULL DEFAULT '',
  `usertype` tinyint(1) NOT NULL DEFAULT '-1' COMMENT '会员组要求，-1不要求，0只允许普通粉丝，1只允许高级粉丝',
  `credit_join` int(10) NOT NULL DEFAULT '0' COMMENT '报名送积分',
  `credit_in` int(10) NOT NULL DEFAULT '0' COMMENT '入选积分',
  `credit_append` int(10) NOT NULL DEFAULT '0' COMMENT '签到积分',
  `visitied` int(10) NOT NULL DEFAULT '0' COMMENT '浏览次数',
  `address` varchar(250) NOT NULL COMMENT '活动地点',
  `latitude` varchar(50) NOT NULL COMMENT '纬度',
  `longitude` varchar(50) NOT NULL COMMENT '经度',
  `redirectmsg` varchar(250) NOT NULL COMMENT '报名成功后回调提示',
  `redirecturl` varchar(250) NOT NULL COMMENT '报名成功后回调',
  `parama` varchar(1000) NOT NULL COMMENT '参数',
  `label` varchar(10) NOT NULL COMMENT '标签',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>