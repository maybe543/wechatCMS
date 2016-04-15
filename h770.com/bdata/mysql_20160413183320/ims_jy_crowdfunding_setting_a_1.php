<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_crowdfunding_setting_a`;");
E_C("CREATE TABLE `ims_jy_crowdfunding_setting_a` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `aname` varchar(250) NOT NULL,
  `url` varchar(200) NOT NULL,
  `hour` int(10) NOT NULL,
  `index` varchar(200) NOT NULL,
  `index_text` varchar(200) NOT NULL,
  `index_button` varchar(200) NOT NULL,
  `rule` text,
  `rule_bg` varchar(200) NOT NULL,
  `huodong` varchar(200) NOT NULL,
  `huodong_button_top` varchar(200) NOT NULL,
  `huodong_button_bottom` varchar(200) NOT NULL,
  `share_bg` varchar(200) NOT NULL,
  `geren` varchar(200) NOT NULL,
  `success` varchar(200) NOT NULL,
  `fail` varchar(200) NOT NULL,
  `friend` varchar(200) NOT NULL,
  `friend_text` varchar(200) NOT NULL,
  `friend_ad_text` varchar(200) NOT NULL,
  `friend_ad_url` varchar(200) NOT NULL,
  `pay_done` varchar(200) NOT NULL,
  `pay` varchar(200) NOT NULL,
  `f_end` varchar(200) NOT NULL,
  `sharelist` varchar(200) NOT NULL,
  `sharelist_color` varchar(200) NOT NULL,
  `updatetime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>