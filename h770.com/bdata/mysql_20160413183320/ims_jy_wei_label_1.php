<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jy_wei_label`;");
E_C("CREATE TABLE `ims_jy_wei_label` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) unsigned NOT NULL,
  `companyid` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL COMMENT '标签',
  `unicode` varchar(255) NOT NULL COMMENT '唯一码MD5(name)',
  `description` varchar(255) NOT NULL COMMENT '描述',
  `createtime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` int(11) unsigned NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>