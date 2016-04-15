<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_market_fans`;");
E_C("CREATE TABLE `ims_market_fans` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `id` int(11) DEFAULT NULL,
  `weid` int(11) DEFAULT NULL,
  `fid` int(11) DEFAULT NULL,
  `userName` varchar(30) DEFAULT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `card_num` varchar(20) DEFAULT NULL,
  `create_time` int(10) DEFAULT NULL,
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>