<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cyl_wxwenzhang_article_share`;");
E_C("CREATE TABLE `ims_cyl_wxwenzhang_article_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(25) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `uid` varchar(25) NOT NULL,
  `article_id` int(11) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `member_uid` varchar(25) NOT NULL,
  `time` varchar(25) NOT NULL,
  `sharenum` int(10) NOT NULL,
  `credit_value` varchar(25) NOT NULL,
  `formuid` varchar(255) NOT NULL,
  `action` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>