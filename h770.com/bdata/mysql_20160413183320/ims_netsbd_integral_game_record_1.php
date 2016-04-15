<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_netsbd_integral_game_record`;");
E_C("CREATE TABLE `ims_netsbd_integral_game_record` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `gameid` int(10) DEFAULT NULL,
  `prize` varchar(255) DEFAULT NULL,
  `state` int(11) DEFAULT NULL COMMENT '0未中奖\r\n            1中奖',
  `createtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分小游戏参与记录'");

require("../../inc/footer.php");
?>