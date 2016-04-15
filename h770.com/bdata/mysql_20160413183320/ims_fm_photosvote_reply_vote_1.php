<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_reply_vote`;");
E_C("CREATE TABLE `ims_fm_photosvote_reply_vote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `iscode` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启投票验证码',
  `codekey` varchar(255) NOT NULL COMMENT '验证码key',
  `addpvapp` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '前端是否允许用户报名',
  `isfans` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '0只保存本模块下1同步更新至官方FANS表',
  `mediatype` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `mediatypem` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `mediatypev` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '上传模式',
  `voicemoshi` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '语音室模式',
  `moshi` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '展示模式： 1 相册模式  2 详情模式',
  `webinfo` text NOT NULL COMMENT '内容',
  `cqtp` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否可重复投票',
  `tpsh` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '投稿是否需审核',
  `isbbsreply` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启评论',
  `tmyushe` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '弹幕评论是否同步到数据库',
  `tmreply` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '弹幕评论是否同步到数据库',
  `isipv` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否开启IP作弊限制',
  `ipturl` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '存在作弊ip后是否继续允许查看，投票，评论等',
  `ipstopvote` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '存在作弊ip后是否继续允许查看，投票，评论等',
  `iplocallimit` varchar(100) NOT NULL COMMENT '地区限制',
  `iplocaldes` varchar(100) NOT NULL COMMENT '地区限制',
  `tpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投稿照片数限制',
  `autolitpic` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '裁剪大小',
  `autozl` int(10) unsigned NOT NULL DEFAULT '50' COMMENT '裁剪质量',
  `limitip` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '投票ip每天限制数',
  `daytpxz` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '每日投票数限制',
  `dayonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一选手投票数限制',
  `allonetp` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '同一选手最高投票数',
  `fansmostvote` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户最高投票数',
  `userinfo` varchar(200) NOT NULL COMMENT '输入姓名或手机时的提示词',
  `votesuccess` varchar(200) NOT NULL COMMENT '投票成功提示语',
  `limitsd` varchar(20) NOT NULL COMMENT '全体限速',
  `limitsdps` varchar(20) NOT NULL COMMENT '全体限速投机票',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>