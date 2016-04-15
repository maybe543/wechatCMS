<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_awarding`;");
E_C("CREATE TABLE `ims_fm_photosvote_awarding` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL COMMENT '公众号ID',
  `uniacid` int(10) unsigned NOT NULL,
  `typeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '区域ID',
  `shoptitle` varchar(50) NOT NULL DEFAULT '' COMMENT '兑奖店面名称',
  `address` varchar(200) NOT NULL DEFAULT '' COMMENT '兑奖地址',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '联系电话',
  `pass` varchar(20) NOT NULL DEFAULT '' COMMENT '兑奖密码',
  `images` varchar(200) NOT NULL DEFAULT '' COMMENT '广告或店面图',
  `carmap` varchar(50) NOT NULL COMMENT '地图导航',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>