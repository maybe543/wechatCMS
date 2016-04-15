<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cyl_wxwenzhang_article_gg`;");
E_C("CREATE TABLE `ims_cyl_wxwenzhang_article_gg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `link` varchar(255) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `uniacid` int(11) NOT NULL,
  `time` varchar(25) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `zongjia` varchar(25) NOT NULL,
  `jiage` varchar(25) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '2',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>