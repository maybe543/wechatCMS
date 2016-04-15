<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_egg`;");
E_C("CREATE TABLE `ims_mon_egg` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `intro` text,
  `music` varchar(500) DEFAULT NULL,
  `banner_bg` varchar(1000) DEFAULT NULL,
  `bg_img` varchar(1000) DEFAULT NULL,
  `share_bg` varchar(1000) DEFAULT NULL,
  `day_count` int(10) DEFAULT NULL,
  `prize_limit` int(10) DEFAULT NULL,
  `dpassword` varchar(20) DEFAULT NULL,
  `follow_url` varchar(1000) DEFAULT NULL,
  `copyright` varchar(100) NOT NULL,
  `follow_dlg_tip` varchar(500) DEFAULT NULL,
  `follow_btn_name` varchar(20) DEFAULT NULL,
  `share_enable` int(1) DEFAULT '0',
  `share_times` int(10) DEFAULT '0',
  `share_award_count` int(10) DEFAULT '0',
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT '0',
  `updatetime` int(10) DEFAULT NULL,
  `exchangeEnable` int(1) DEFAULT NULL,
  `xhjf_enable` int(1) DEFAULT NULL,
  `xhjf` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>