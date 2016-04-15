<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_j_tempmsg_temp`;");
E_C("CREATE TABLE `ims_j_tempmsg_temp` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL DEFAULT '0' COMMENT '所属帐号',
  `name` varchar(100) NOT NULL COMMENT '模板名称',
  `tempid` varchar(100) NOT NULL COMMENT '模板ID',
  `content` varchar(500) NOT NULL,
  `parama` varchar(1000) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>