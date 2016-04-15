<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hc_hunxiao_userdefault`;");
E_C("CREATE TABLE `ims_hc_hunxiao_userdefault` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `commission` int(10) unsigned NOT NULL COMMENT '分销级别佣金',
  `userdefault` int(10) unsigned NOT NULL COMMENT '分销级数标识',
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>