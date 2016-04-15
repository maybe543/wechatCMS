<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('latin1');
E_D("DROP TABLE IF EXISTS `ims_sinrch_dataosha_setting`;");
E_C("CREATE TABLE `ims_sinrch_dataosha_setting` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `subscribe_num` varchar(255) CHARACTER SET utf8 NOT NULL,
  `subscribe_skill` varchar(255) CHARACTER SET utf8 NOT NULL,
  `subscribe_url` varchar(255) CHARACTER SET utf8 NOT NULL,
  `subscribe_game` varchar(255) CHARACTER SET utf8 NOT NULL,
  `share_title` varchar(255) CHARACTER SET utf8 NOT NULL,
  `share_desc` varchar(255) CHARACTER SET utf8 NOT NULL,
  `photo` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1");

require("../../inc/footer.php");
?>