<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_reply`;");
E_C("CREATE TABLE `ims_cate_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned DEFAULT '0',
  `uniacid` int(10) unsigned DEFAULT '0',
  `acid` int(10) unsigned DEFAULT '0',
  `title` varchar(50) DEFAULT '',
  `content` text,
  `thumb` varchar(60) DEFAULT '',
  `unit` varchar(20) DEFAULT '',
  `printmode` tinyint(3) unsigned DEFAULT '0',
  `description` varchar(255) DEFAULT '',
  `share_title` varchar(200) DEFAULT '',
  `share_desc` varchar(255) DEFAULT '',
  `share_url` varchar(100) DEFAULT '',
  `share_txt` text,
  `isattention` int(10) DEFAULT '0',
  `templet` varchar(255) DEFAULT '',
  `tmplmsg` text,
  `starttime` bigint(18) unsigned DEFAULT '0',
  `endtime` bigint(18) unsigned DEFAULT '0',
  `isshow` int(10) unsigned DEFAULT '0',
  `setting` text,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微餐饮 - 餐厅信息、回复规则'");

require("../../inc/footer.php");
?>