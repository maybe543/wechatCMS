<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_agent_expenserecords`;");
E_C("CREATE TABLE `ims_agent_expenserecords` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `agentid` int(10) NOT NULL,
  `amount` int(10) NOT NULL,
  `orderid` varchar(60) NOT NULL,
  `des` varchar(200) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `times` varchar(100) NOT NULL,
  `num` int(10) NOT NULL,
  `group` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `agentid` (`agentid`,`times`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>