<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_makeredpack_record`;");
E_C("CREATE TABLE `ims_meepo_makeredpack_record` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) NOT NULL DEFAULT '0',
  `weid` int(10) unsigned NOT NULL,
  `type` tinyint(2) NOT NULL DEFAULT '0',
  `from_user` varchar(50) NOT NULL,
  `openid` varchar(100) DEFAULT NULL,
  `fee` varchar(20) NOT NULL DEFAULT '',
  `nickname` varchar(50) DEFAULT NULL,
  `headimgurl` varchar(300) DEFAULT NULL,
  `createtime` int(11) DEFAULT '0' COMMENT '领取时间',
  `status` tinyint(2) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`from_user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>