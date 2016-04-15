<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_shop_article_sys`;");
E_C("CREATE TABLE `ims_ewei_shop_article_sys` (
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `article_message` varchar(255) NOT NULL DEFAULT '',
  `article_title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `article_image` varchar(300) NOT NULL DEFAULT '' COMMENT '图片',
  `article_shownum` int(11) NOT NULL DEFAULT '0' COMMENT '每页数量',
  `article_keyword` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `article_temp` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`uniacid`),
  KEY `idx_article_message` (`article_message`),
  KEY `idx_article_keyword` (`article_keyword`),
  KEY `idx_article_title` (`article_title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>