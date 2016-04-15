<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_weicard_photo`;");
E_C("CREATE TABLE `ims_amouse_weicard_photo` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `mid` int(10) NOT NULL COMMENT '会员表id',
  `cid` int(10) NOT NULL COMMENT '名片表id',
  `from_user` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL COMMENT '栏目名称',
  `icon` varchar(255) DEFAULT NULL COMMENT '栏目图标',
  `thumb` text COMMENT '图片',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>