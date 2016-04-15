<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cyl_wxwenzhang_article`;");
E_C("CREATE TABLE `ims_cyl_wxwenzhang_article` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `rid` int(10) unsigned NOT NULL,
  `kid` int(10) unsigned NOT NULL,
  `iscommend` tinyint(1) NOT NULL,
  `ishot` tinyint(1) unsigned NOT NULL,
  `pcate` int(10) unsigned NOT NULL,
  `ccate` int(10) unsigned NOT NULL,
  `template` varchar(300) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  `content` mediumtext NOT NULL,
  `thumb` varchar(255) NOT NULL,
  `incontent` tinyint(1) NOT NULL,
  `source` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `displayorder` int(10) unsigned NOT NULL,
  `linkurl` varchar(500) NOT NULL,
  `createtime` int(10) unsigned NOT NULL,
  `click` int(10) unsigned NOT NULL,
  `ly` int(20) NOT NULL DEFAULT '0',
  `type` varchar(10) NOT NULL,
  `credit` varchar(255) NOT NULL,
  `sourcelink` varchar(255) NOT NULL,
  `sharelink` varchar(255) NOT NULL,
  `articlegg` varchar(255) NOT NULL,
  `articlelink` varchar(255) NOT NULL,
  `articledsfgg` text NOT NULL,
  `pic` text NOT NULL,
  `uid` varchar(25) NOT NULL DEFAULT '',
  `status` int(2) NOT NULL DEFAULT '1',
  `zongjia` varchar(255) NOT NULL DEFAULT '',
  `jiage` varchar(255) NOT NULL DEFAULT '',
  `jifen` int(2) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `idx_iscommend` (`iscommend`),
  KEY `idx_ishot` (`ishot`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>