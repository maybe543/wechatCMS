<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_eso_runman_reply`;");
E_C("CREATE TABLE `ims_eso_runman_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT '',
  `content` text,
  `background` varchar(255) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `description` text,
  `share_title` varchar(255) DEFAULT '',
  `share_desc` varchar(255) DEFAULT '',
  `share_url` varchar(255) DEFAULT '',
  `mp3` varchar(255) DEFAULT '',
  `join` int(10) unsigned DEFAULT '0',
  `view` int(10) unsigned DEFAULT '0',
  `share_txt` text,
  `regular` text,
  `setting` text,
  `starttime` bigint(18) unsigned DEFAULT '0',
  `endtime` bigint(18) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='奔跑兄弟 - 回复规则'");

require("../../inc/footer.php");
?>