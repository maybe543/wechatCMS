<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_data`;");
E_C("CREATE TABLE `ims_fm_photosvote_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `fromuser` varchar(150) NOT NULL DEFAULT '' COMMENT '分享用户openid',
  `from_user` varchar(150) NOT NULL DEFAULT '' COMMENT '当前用户openid',
  `tfrom_user` varchar(150) NOT NULL DEFAULT '' COMMENT '被分享用户openid',
  `avatar` varchar(200) NOT NULL DEFAULT '' COMMENT '微信头像',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享人UID',
  `isin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否参与',
  `visitorsip` varchar(15) NOT NULL DEFAULT '' COMMENT '访问IP',
  `visitorstime` int(10) unsigned NOT NULL COMMENT '访问时间',
  `viewnum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '查看次数',
  PRIMARY KEY (`id`),
  KEY `indx_uniacid` (`uniacid`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uid` (`uid`),
  KEY `indx_from_user` (`from_user`),
  KEY `IDX_TFROM_USER` (`tfrom_user`),
  KEY `IDX_FROMUSER` (`fromuser`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>