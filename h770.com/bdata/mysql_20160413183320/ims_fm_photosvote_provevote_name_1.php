<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_provevote_name`;");
E_C("CREATE TABLE `ims_fm_photosvote_provevote_name` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `from_user` varchar(255) NOT NULL DEFAULT '' COMMENT '用户openid',
  `musicname` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `photoname` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_1_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_2_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_3_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_4_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_5_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_6_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_7_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `picarr_8_name` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `musicnamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `voicename` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `voicenamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '音乐',
  `vedioname` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  `vedionamefop` varchar(200) NOT NULL DEFAULT '' COMMENT '视频',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_from_user` (`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>