<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_share_datas`;");
E_C("CREATE TABLE `ims_share_datas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `share_title` varchar(100) NOT NULL,
  `share_link` varchar(300) NOT NULL,
  `share_ztlink` varchar(300) NOT NULL,
  `share_content` varchar(300) NOT NULL,
  `share_logo` varchar(100) NOT NULL,
  `num` int(100) NOT NULL DEFAULT '0',
  `createtime` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>