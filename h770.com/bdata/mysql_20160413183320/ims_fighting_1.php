<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fighting`;");
E_C("CREATE TABLE `ims_fighting` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `fid` int(10) unsigned NOT NULL,
  `answerNum` int(11) unsigned NOT NULL COMMENT '已经答题数量',
  `from_user` varchar(30) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `lasttime` int(10) unsigned NOT NULL,
  `lastcredit` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>