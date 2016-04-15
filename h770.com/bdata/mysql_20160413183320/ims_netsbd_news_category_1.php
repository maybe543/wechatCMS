<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_netsbd_news_category`;");
E_C("CREATE TABLE `ims_netsbd_news_category` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(10) DEFAULT NULL,
  `uniacid` int(10) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `ismenu` int(11) DEFAULT NULL,
  `ishide` int(11) DEFAULT NULL,
  `createtime` int(10) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='新闻分类表'");

require("../../inc/footer.php");
?>