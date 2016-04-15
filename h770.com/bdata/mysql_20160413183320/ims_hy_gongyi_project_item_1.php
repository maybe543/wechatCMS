<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hy_gongyi_project_item`;");
E_C("CREATE TABLE `ims_hy_gongyi_project_item` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `pid` int(10) unsigned NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `description` varchar(2000) NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `limit_num` int(10) unsigned NOT NULL,
  `donenum` int(10) unsigned NOT NULL DEFAULT '0',
  `repaid_day` int(10) unsigned NOT NULL,
  `return_type` tinyint(1) unsigned NOT NULL,
  `dispatch` int(10) unsigned NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `qita` tinyint(2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=39 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>