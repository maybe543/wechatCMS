<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_duanwu_show`;");
E_C("CREATE TABLE `ims_duanwu_show` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `picurl` varchar(200) NOT NULL DEFAULT '0',
  `thumbpath` varchar(200) NOT NULL DEFAULT '0',
  `hei` varchar(20) DEFAULT '',
  `openid` varchar(100) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `uid` int(10) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `createtime` int(12) NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>