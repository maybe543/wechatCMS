<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_amouse_weicard_card`;");
E_C("CREATE TABLE `ims_amouse_weicard_card` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `from_user` varchar(255) NOT NULL,
  `mid` int(10) NOT NULL COMMENT '会员表id',
  `mobile` tinyint(1) DEFAULT '0' COMMENT 'type=1;0代表全部可见，1代表互相收藏可见，2代表自己可见',
  `email` tinyint(1) DEFAULT '0' COMMENT 'type=2;0代表全部可见，1代表互相收藏可见，2代表自己可见',
  `weixin` tinyint(1) DEFAULT '0' COMMENT 'type=3;0代表全部可见，1代表互相收藏可见，2代表自己可见',
  `address` tinyint(1) DEFAULT '0' COMMENT 'type=4;0代表全部可见，1代表互相收藏可见，2代表自己可见',
  `bgimg` varchar(255) DEFAULT NULL,
  `shopName` varchar(255) DEFAULT NULL,
  `templateFile` varchar(300) DEFAULT 'qianx_index',
  `shopIcon` varchar(255) DEFAULT NULL,
  `shopUrl` varchar(255) DEFAULT NULL,
  `zan` int(10) DEFAULT '0',
  `view` int(10) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>