<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_broke_identity`;");
E_C("CREATE TABLE `ims_broke_identity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `identity_name` varchar(20) NOT NULL,
  `iscompany` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要填写公司名称，1要，默认不要',
  `listorder` int(5) NOT NULL DEFAULT '0',
  `createtime` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=62 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>