<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cyl_wxwenzhang_shang`;");
E_C("CREATE TABLE `ims_cyl_wxwenzhang_shang` (
  `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
  `article_id` int(20) NOT NULL,
  `uniacid` int(20) NOT NULL,
  `openid` varchar(255) NOT NULL,
  `nickname` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `fee` varchar(20) NOT NULL,
  `time` varchar(255) NOT NULL,
  `status` int(2) NOT NULL,
  `tid` varchar(25) NOT NULL DEFAULT '',
  `uid` varchar(25) NOT NULL DEFAULT '',
  `memberuid` varchar(25) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>