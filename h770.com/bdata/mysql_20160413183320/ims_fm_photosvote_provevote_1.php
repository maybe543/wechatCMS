<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_provevote`;");
E_C("CREATE TABLE `ims_fm_photosvote_provevote` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户编号',
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `tagid` int(10) unsigned NOT NULL COMMENT '分组id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被投票用户openid',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `sex` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '性别，1、男 2、女 0 、未知',
  `photo` varchar(200) NOT NULL DEFAULT '' COMMENT '照片',
  `music` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `mediaid` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐id',
  `timelength` varchar(200) NOT NULL DEFAULT '' COMMENT '时间轴',
  `voice` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `vedio` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  `youkuurl` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  `fmmid` varchar(200) NOT NULL DEFAULT '' COMMENT '识别',
  `picarr` varchar(2000) NOT NULL DEFAULT '' COMMENT '照片组',
  `description` text NOT NULL COMMENT '简介，描述',
  `photoname` varchar(50) NOT NULL DEFAULT '' COMMENT '照片名字',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `realname` varchar(20) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `job` varchar(20) NOT NULL DEFAULT '' COMMENT '职业',
  `xingqu` varchar(20) NOT NULL DEFAULT '' COMMENT '兴趣',
  `weixin` varchar(255) NOT NULL DEFAULT '' COMMENT '联系微信号',
  `qqhao` varchar(20) NOT NULL DEFAULT '' COMMENT '联系QQ号码',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '联系邮箱',
  `address` varchar(100) NOT NULL DEFAULT '' COMMENT '联系地址',
  `www` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `photosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '票数',
  `xnphotosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟票数',
  `hits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '人气',
  `xnhits` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟人气',
  `yaoqingnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '邀请量',
  `zans` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '点赞数',
  `ewm` varchar(200) NOT NULL DEFAULT '' COMMENT '二维码地址',
  `status` tinyint(1) unsigned NOT NULL COMMENT '审核状态',
  `isadmin` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否设置为管理员',
  `istuijian` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否设置为推荐',
  `limitsd` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '限速',
  `createip` varchar(50) NOT NULL DEFAULT '' COMMENT '创建IP',
  `lastip` varchar(50) NOT NULL DEFAULT '' COMMENT '编辑IP',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'ip地区',
  `lasttime` int(10) unsigned NOT NULL COMMENT '最后编辑时间',
  `sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `sharenum` int(10) unsigned NOT NULL COMMENT '最后分享',
  `createtime` int(10) unsigned NOT NULL COMMENT '注册时间',
  `ysid` int(10) unsigned NOT NULL COMMENT 'ysid',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_uid` (`uid`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>