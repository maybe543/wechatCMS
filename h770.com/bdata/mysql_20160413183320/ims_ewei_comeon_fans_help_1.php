<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_comeon_fans_help`;");
E_C("CREATE TABLE `ims_ewei_comeon_fans_help` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(100) DEFAULT '' COMMENT '助力的',
  `fansid` int(11) DEFAULT '0' COMMENT '被助力的',
  `date` varchar(20) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>