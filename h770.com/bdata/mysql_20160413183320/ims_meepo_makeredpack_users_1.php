<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_makeredpack_users`;");
E_C("CREATE TABLE `ims_meepo_makeredpack_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL DEFAULT '0',
  `weid` int(11) DEFAULT NULL,
  `from_user` varchar(50) NOT NULL,
  `openid` varchar(40) NOT NULL DEFAULT '',
  `nickname` varchar(40) NOT NULL DEFAULT '',
  `headimgurl` varchar(300) NOT NULL DEFAULT '',
  `sonnums` int(11) DEFAULT '0' COMMENT '用户数量',
  `hbnums` int(11) DEFAULT '0' COMMENT '发放红包数量',
  `fatherid` int(11) DEFAULT '0',
  `money` varchar(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`openid`,`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>