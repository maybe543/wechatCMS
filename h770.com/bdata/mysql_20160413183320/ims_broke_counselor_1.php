<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_broke_counselor`;");
E_C("CREATE TABLE `ims_broke_counselor` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `code` varchar(20) NOT NULL,
  `loupan` int(10) unsigned NOT NULL DEFAULT '0',
  `listorder` int(5) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `content` text,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=35 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>