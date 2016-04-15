<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_scene_cube_list`;");
E_C("CREATE TABLE `ims_scene_cube_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL DEFAULT '',
  `s_id` int(11) NOT NULL,
  `iden` varchar(50) NOT NULL,
  `cover` varchar(300) NOT NULL,
  `cover_title` varchar(50) NOT NULL,
  `cover_subtitle` varchar(50) DEFAULT NULL,
  `cover1` varchar(300) NOT NULL,
  `cover2` varchar(300) NOT NULL,
  `thumb` varchar(300) NOT NULL,
  `share_title` varchar(200) NOT NULL DEFAULT '',
  `share_thumb` varchar(300) NOT NULL DEFAULT '',
  `share_content` varchar(1000) NOT NULL,
  `share_cb_url` varchar(500) NOT NULL,
  `share_cb_tel` varchar(20) NOT NULL,
  `diyurl` varchar(100) NOT NULL DEFAULT '',
  `share_cover` varchar(300) NOT NULL DEFAULT '',
  `share_url` varchar(300) NOT NULL DEFAULT '',
  `share_txt` varchar(500) NOT NULL DEFAULT '',
  `share_button` varchar(300) NOT NULL,
  `share_tips` varchar(300) NOT NULL,
  `reply_title` varchar(50) NOT NULL,
  `reply_thumb` varchar(300) NOT NULL,
  `reply_description` varchar(1000) NOT NULL,
  `isadvanced` int(3) NOT NULL DEFAULT '0',
  `advanced_thumb` varchar(300) NOT NULL,
  `email` varchar(300) NOT NULL DEFAULT '',
  `emailtitle` varchar(100) NOT NULL,
  `first_type` tinyint(2) NOT NULL,
  `first_btn_select` varchar(10) NOT NULL,
  `first_btn_value` varchar(500) NOT NULL,
  `bg_music_switch` tinyint(4) NOT NULL,
  `bg_music_icon` tinyint(4) NOT NULL,
  `bg_music_url` varchar(300) NOT NULL,
  `start_time` int(10) NOT NULL,
  `end_time` int(10) NOT NULL,
  `hits` int(10) NOT NULL,
  `shares` int(10) NOT NULL,
  `tongji` varchar(1000) NOT NULL,
  `isyuyue` tinyint(1) NOT NULL DEFAULT '0',
  `iscomment` tinyint(1) NOT NULL DEFAULT '0',
  `isdemo` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>