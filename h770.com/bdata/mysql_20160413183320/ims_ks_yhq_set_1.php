<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('gbk');
E_D("DROP TABLE IF EXISTS `ims_ks_yhq_set`;");
E_C("CREATE TABLE `ims_ks_yhq_set` (
  `iid` int(11) NOT NULL AUTO_INCREMENT,
  `img` varchar(999) NOT NULL,
  PRIMARY KEY (`iid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=gbk");

require("../../inc/footer.php");
?>