<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_votelog`;");
E_C("CREATE TABLE `ims_fm_photosvote_votelog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `tptype` int(10) unsigned NOT NULL COMMENT '投票类型 1 微信页面投票  2 微信会话界面',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `tfrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '被投票用户openid',
  `yamishijie` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `w171` varchar(30) NOT NULL DEFAULT '0' COMMENT '0',
  `afrom_user` varchar(255) NOT NULL DEFAULT '' COMMENT '分享用户openid',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT '投票IP',
  `iparr` varchar(200) NOT NULL DEFAULT '' COMMENT 'ip地区',
  `photosnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '票数',
  `createtime` int(10) unsigned NOT NULL COMMENT '投票时间',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_createtime` (`createtime`),
  KEY `indx_from_user` (`from_user`),
  KEY `IDX_IP_CREATETIME` (`ip`,`createtime`),
  KEY `IDX_TFROM_USER` (`tfrom_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>