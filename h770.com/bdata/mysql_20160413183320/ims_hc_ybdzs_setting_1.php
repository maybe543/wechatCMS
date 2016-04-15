<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('latin1');
E_D("DROP TABLE IF EXISTS `ims_hc_ybdzs_setting`;");
E_C("CREATE TABLE `ims_hc_ybdzs_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `hc_ybdzs_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `hc_ybdzs_url` varchar(200) CHARACTER SET utf8 NOT NULL,
  `share_title` varchar(100) CHARACTER SET utf8 NOT NULL,
  `share_desc` varchar(100) CHARACTER SET utf8 NOT NULL,
  `wechat` varchar(100) CHARACTER SET utf8 NOT NULL,
  `photo` varchar(100) CHARACTER SET utf8 NOT NULL,
  `counts` varchar(500) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");

require("../../inc/footer.php");
?>