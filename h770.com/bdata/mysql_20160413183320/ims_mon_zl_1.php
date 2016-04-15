<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_mon_zl`;");
E_C("CREATE TABLE `ims_mon_zl` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) NOT NULL,
  `weid` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `starttime` int(10) DEFAULT NULL,
  `endtime` int(10) DEFAULT NULL,
  `awardstime` int(10) DEFAULT NULL,
  `awardetime` int(10) DEFAULT NULL,
  `awardaddress` varchar(2000) DEFAULT NULL,
  `rule` text,
  `award` text,
  `content` text,
  `title_bg` varchar(300) DEFAULT NULL,
  `share_bg` varchar(300) DEFAULT NULL,
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
  `top_banner` varchar(500) DEFAULT NULL,
  `top_banner_title` varchar(100) DEFAULT NULL,
  `top_banner_show` int(1) DEFAULT '0',
  `top_banner_url` varchar(500) DEFAULT NULL,
  `zl_follow_enable` int(1) DEFAULT NULL,
  `join_follow_enable` int(1) DEFAULT NULL,
  `follow_dlg_tip` varchar(500) DEFAULT NULL,
  `follow_btn_name` varchar(20) DEFAULT NULL,
  `udetail_eable` int(1) DEFAULT NULL,
  `telname` varchar(30) DEFAULT '手机',
  `contact_tel` varchar(20) DEFAULT NULL,
  `contact_name` varchar(20) DEFAULT '联系小编',
  `startp` int(10) DEFAULT NULL,
  `maxp` int(10) DEFAULT NULL,
  `zl_rule` varchar(2000) DEFAULT NULL,
  `join_btn_name` varchar(100) DEFAULT '我要报名',
  `uzl_btn_name` varchar(100) DEFAULT '发送给好友助力',
  `fzl_btn_name` varchar(100) DEFAULT '发送给好友帮他助力',
  `top_tag` int(3) DEFAULT NULL,
  `view_count` int(3) DEFAULT NULL,
  `share_count` int(3) DEFAULT NULL,
  `f_zl_limit` int(3) DEFAULT NULL,
  `zlunit` varchar(10) DEFAULT NULL,
  `syncredit` int(1) DEFAULT NULL,
  `f_zl_limit_tip` varchar(2000) DEFAULT NULL,
  `f_day_limit` int(10) DEFAULT NULL,
  `f_day_limit_tip` varchar(2000) DEFAULT NULL,
  `f_diff_limt` int(10) DEFAULT NULL,
  `f_diff_tip` varchar(2000) DEFAULT NULL,
  `ip_limit` int(10) DEFAULT NULL,
  `ip_limit_tip` varchar(2000) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>