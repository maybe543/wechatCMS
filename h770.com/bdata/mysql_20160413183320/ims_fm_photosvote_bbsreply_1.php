<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_bbsreply`;");
E_C("CREATE TABLE `ims_fm_photosvote_bbsreply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `tid` varchar(125) NOT NULL COMMENT '帖子的ID',
  `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '帖子作者的openid',
  `reply_id` varchar(125) NOT NULL COMMENT '回复评论帖子的ID',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '回复评论帖子的openid',
  `to_reply_id` int(11) NOT NULL DEFAULT '0' COMMENT '回复评论的id',
  `rfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被回复的评论的作者的openid',
  `content` text NOT NULL COMMENT '评论回复内容',
  `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已删除 0-否 1-是',
  `status` tinyint(2) DEFAULT '0' COMMENT '是否审核 0-否 1-是',
  `storey` int(11) NOT NULL DEFAULT '0' COMMENT '绝对楼层',
  `zan` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `ip` varchar(255) NOT NULL DEFAULT '' COMMENT '回复IP',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'IP区域',
  `createtime` int(11) NOT NULL COMMENT '回复时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>