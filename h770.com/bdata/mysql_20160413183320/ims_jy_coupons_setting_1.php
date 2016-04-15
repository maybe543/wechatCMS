<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_coupons_setting`;");
E_C("CREATE TABLE `ims_jy_coupons_setting` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `aname` varchar(250) NOT NULL,
  `url` varchar(200) NOT NULL,
  `rule` text,
  `rule_bg` varchar(200) NOT NULL,
  `index_text` varchar(200) NOT NULL,
  `index1` varchar(200) NOT NULL,
  `index2` varchar(200) NOT NULL,
  `index3` varchar(200) NOT NULL,
  `index_bg` varchar(200) NOT NULL,
  `huodong1` varchar(200) NOT NULL,
  `huodong2` varchar(200) NOT NULL,
  `huodong3` varchar(200) NOT NULL,
  `huodong_bg` varchar(200) NOT NULL,
  `share_bg` varchar(200) NOT NULL,
  `friend` varchar(200) NOT NULL,
  `friend_bg` varchar(200) NOT NULL,
  `share_title` varchar(200) NOT NULL,
  `share_desc` varchar(200) NOT NULL,
  `share_logo` varchar(200) NOT NULL,
  `api_ticket` varchar(200) NOT NULL,
  `api_time` int(10) NOT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>