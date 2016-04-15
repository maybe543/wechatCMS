<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_netsbd_adlist`;");
E_C("CREATE TABLE `ims_netsbd_adlist` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `title` varchar(500) DEFAULT NULL,
  `picture` varchar(500) DEFAULT NULL,
  `ad_script` varchar(500) DEFAULT NULL,
  `url` varchar(500) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `click_num` int(11) DEFAULT NULL,
  `click_price` decimal(8,2) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='广告投放列表'");

require("../../inc/footer.php");
?>