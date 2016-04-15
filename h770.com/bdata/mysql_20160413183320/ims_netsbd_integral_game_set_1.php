<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_netsbd_integral_game_set`;");
E_C("CREATE TABLE `ims_netsbd_integral_game_set` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `picture` varchar(255) DEFAULT NULL,
  `content` varchar(5000) DEFAULT NULL,
  `integral_eq_game` int(10) DEFAULT NULL COMMENT '1是赞\r\n            2是评论',
  `num_eq_result` int(10) DEFAULT NULL,
  `prize` varchar(255) DEFAULT NULL,
  `state` int(11) DEFAULT NULL,
  `ishome` int(11) DEFAULT NULL,
  `begintime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='积分小游戏设置'");

require("../../inc/footer.php");
?>