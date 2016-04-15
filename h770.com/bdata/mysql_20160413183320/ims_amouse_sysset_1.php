<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_sysset`;");
E_C("CREATE TABLE `ims_amouse_sysset` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `jjrmobile` varchar(13) NOT NULL COMMENT '手机',
  `broker` varchar(200) NOT NULL COMMENT '经纪人',
  `guanzhuUrl` varchar(255) DEFAULT '1' COMMENT '引导关注',
  `copyright` varchar(255) DEFAULT '' COMMENT '版权',
  `newflat_images` varchar(255) DEFAULT '' COMMENT '楼盘图片设置',
  `isoauth` int(10) DEFAULT '1' COMMENT '是否开启高级权限',
  `isshow` int(10) DEFAULT '1' COMMENT '是否只显示经纪人信息',
  `cnzz` varchar(255) DEFAULT '' COMMENT '统计',
  `appid` varchar(255) DEFAULT '',
  `appsecret` varchar(255) DEFAULT '',
  `appid_share` varchar(255) DEFAULT '',
  `appsecret_share` varchar(255) DEFAULT '',
  `defcity` varchar(1000) DEFAULT '中国',
  `nickname` varchar(500) DEFAULT NULL COMMENT '昵称',
  `openid` varchar(500) DEFAULT NULL COMMENT 'openid',
  `isadjuest` varchar(1) DEFAULT '1' COMMENT '是否审核',
  PRIMARY KEY (`id`),
  KEY `indx_weid` (`weid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>