<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_heka_list`;");
E_C("CREATE TABLE `ims_heka_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT NULL,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) DEFAULT NULL,
  `card` varchar(20) NOT NULL COMMENT '活动图片',
  `author` varchar(20) DEFAULT NULL,
  `content` varchar(500) NOT NULL COMMENT '活动描述',
  `cardName` varchar(50) DEFAULT NULL,
  `from_user` varchar(50) DEFAULT NULL,
  `hits` int(11) DEFAULT NULL,
  `share` int(11) DEFAULT NULL,
  `create_time` int(10) NOT NULL COMMENT '规则',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>