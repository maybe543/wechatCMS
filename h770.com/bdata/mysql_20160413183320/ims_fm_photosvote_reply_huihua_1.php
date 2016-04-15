<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_reply_huihua`;");
E_C("CREATE TABLE `ims_fm_photosvote_reply_huihua` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `command` varchar(10) NOT NULL COMMENT '报名命令',
  `tcommand` varchar(10) NOT NULL COMMENT '报名命令',
  `ishuodong` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '1 开启',
  `huodongname` varchar(20) NOT NULL COMMENT '活动名字',
  `huodongdes` varchar(50) NOT NULL COMMENT '活动简介',
  `huodongurl` varchar(125) NOT NULL COMMENT '活动链接',
  `hhhdpicture` varchar(125) NOT NULL COMMENT '活动图片',
  `regmessagetemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知报名成功',
  `messagetemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知',
  `shmessagetemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知投票审核成功',
  `fmqftemplate` varchar(50) NOT NULL COMMENT '投票创建成功通知群发消息',
  `msgtemplate` varchar(50) NOT NULL COMMENT '评论通知消息',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>