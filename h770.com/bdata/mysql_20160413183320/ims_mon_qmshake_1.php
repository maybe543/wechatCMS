<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_qmshake`;");
E_C("CREATE TABLE `ims_mon_qmshake` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `shake_follow_enable` int(1) DEFAULT NULL,
  `copyright` varchar(50) DEFAULT NULL,
  `randking_count` int(10) DEFAULT NULL,
  `follow_url` varchar(200) DEFAULT NULL,
  `new_title` varchar(200) DEFAULT NULL,
  `new_icon` varchar(200) DEFAULT NULL,
  `new_content` varchar(200) DEFAULT NULL,
  `share_title` varchar(200) DEFAULT NULL,
  `share_icon` varchar(200) DEFAULT NULL,
  `share_content` varchar(200) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `updatetime` int(10) DEFAULT NULL,
  `rule` varchar(2000) DEFAULT NULL,
  `top_banner` varchar(500) DEFAULT NULL,
  `top_banner_title` varchar(100) DEFAULT NULL,
  `top_banner_show` int(1) DEFAULT '0',
  `top_banner_url` varchar(500) DEFAULT NULL,
  `follow_dlg_tip` varchar(500) DEFAULT NULL,
  `follow_btn_name` varchar(20) DEFAULT NULL,
  `shake_day_limit` int(3) DEFAULT '0',
  `prize_limit` int(3) DEFAULT '0',
  `total_limit` int(3) DEFAULT '0',
  `dpassword` varchar(20) DEFAULT NULL,
  `title_bg` varchar(300) DEFAULT NULL,
  `shake_bg` varchar(300) DEFAULT NULL,
  `index_bg` varchar(300) DEFAULT NULL,
  `share_bg` varchar(300) DEFAULT NULL,
  `share_enable` int(1) DEFAULT '0',
  `share_times` int(3) DEFAULT '0',
  `share_award_count` int(3) DEFAULT '0',
  `share_url` varchar(1000) DEFAULT NULL,
  `unstarttip` text,
  `tmpId` varchar(2000) DEFAULT NULL,
  `tmpenable` int(1) DEFAULT NULL,
  `udefine` varchar(200) DEFAULT NULL,
  `lj_tip` varchar(2000) DEFAULT NULL,
  `jfye_auto_dh` int(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>