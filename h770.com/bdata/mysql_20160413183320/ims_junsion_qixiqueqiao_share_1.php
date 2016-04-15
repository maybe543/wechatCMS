<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_junsion_qixiqueqiao_share`;");
E_C("CREATE TABLE `ims_junsion_qixiqueqiao_share` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `pid` int(11) NOT NULL,
  `birds_num` int(11) NOT NULL,
  `createtime` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>