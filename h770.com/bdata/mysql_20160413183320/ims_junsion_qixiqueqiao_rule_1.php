<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_junsion_qixiqueqiao_rule`;");
E_C("CREATE TABLE `ims_junsion_qixiqueqiao_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `stitle` varchar(50) NOT NULL,
  `sthumb` varchar(200) NOT NULL,
  `sdesc` text NOT NULL,
  `niulang` varchar(200) NOT NULL,
  `zhinv` varchar(200) NOT NULL,
  `bg` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `starttime` varchar(11) NOT NULL,
  `endtime` varchar(11) NOT NULL,
  `prize_mode` int(1) NOT NULL,
  `describe_limit` int(1) NOT NULL,
  `describe_limit2` int(1) DEFAULT '0',
  `prize_limit` int(11) NOT NULL,
  `birds_success` int(11) NOT NULL,
  `birds_limit` varchar(10) NOT NULL,
  `sharetitle` varchar(200) NOT NULL,
  `sharethumb` varchar(200) NOT NULL,
  `sharedesc` text NOT NULL,
  `isinfo` int(1) NOT NULL,
  `awardtips` varchar(200) NOT NULL,
  `isrealname` int(1) NOT NULL,
  `ismobile` int(1) NOT NULL,
  `isqq` int(1) NOT NULL,
  `isemail` int(1) NOT NULL,
  `isaddress` int(1) NOT NULL,
  `isfans` int(1) NOT NULL,
  `rank` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>