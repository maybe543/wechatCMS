<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_junsion_qixiqueqiao_player`;");
E_C("CREATE TABLE `ims_junsion_qixiqueqiao_player` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `rid` int(11) NOT NULL,
  `openid` varchar(50) NOT NULL,
  `avatar` varchar(200) NOT NULL,
  `nickname` varchar(50) NOT NULL,
  `realname` varchar(50) NOT NULL,
  `mobile` varchar(50) NOT NULL,
  `qq` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `address` varchar(50) NOT NULL,
  `myname` varchar(50) NOT NULL,
  `hname` varchar(50) NOT NULL,
  `status` int(1) NOT NULL,
  `createtime` varchar(11) NOT NULL,
  `successtime` varchar(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>