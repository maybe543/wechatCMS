<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_agent`;");
E_C("CREATE TABLE `ims_agent` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `intro` varchar(800) NOT NULL,
  `mp` varchar(11) NOT NULL,
  `usercount` int(10) NOT NULL,
  `wxusercount` int(10) NOT NULL,
  `sitename` varchar(50) NOT NULL,
  `sitelogo` varchar(200) NOT NULL,
  `qrcode` varchar(100) NOT NULL,
  `sitetitle` varchar(60) NOT NULL,
  `siteurl` varchar(100) NOT NULL,
  `robotname` varchar(40) NOT NULL,
  `connectouttip` varchar(50) NOT NULL,
  `needcheckuser` tinyint(1) NOT NULL,
  `regneedmp` tinyint(1) NOT NULL,
  `reggid` int(10) NOT NULL,
  `regvaliddays` mediumint(4) NOT NULL,
  `qq` varchar(12) NOT NULL,
  `email` varchar(50) NOT NULL,
  `metades` varchar(300) NOT NULL,
  `metakeywords` varchar(200) NOT NULL,
  `statisticcode` varchar(300) NOT NULL,
  `copyright` varchar(100) NOT NULL,
  `alipayaccount` varchar(50) NOT NULL,
  `alipaypid` varchar(100) NOT NULL,
  `alipaykey` varchar(100) NOT NULL,
  `password` varchar(40) NOT NULL,
  `salt` varchar(6) NOT NULL,
  `money` int(10) NOT NULL,
  `moneybalance` int(10) NOT NULL,
  `time` int(10) NOT NULL,
  `endtime` varchar(15) NOT NULL,
  `lastloginip` varchar(26) NOT NULL,
  `lastlogintime` int(11) NOT NULL,
  `wxacountprice` mediumint(4) NOT NULL,
  `monthprice` mediumint(4) NOT NULL,
  `appid` varchar(50) NOT NULL,
  `appsecret` varchar(100) NOT NULL,
  `title` varchar(40) NOT NULL,
  `content` text NOT NULL,
  `level` int(11) NOT NULL,
  `isnav` int(11) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>