<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cover_reply`;");
E_C("CREATE TABLE `ims_cover_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `multiid` int(10) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned NOT NULL,
  `module` varchar(30) NOT NULL DEFAULT '',
  `do` varchar(30) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) NOT NULL DEFAULT '',
  `thumb` varchar(255) NOT NULL DEFAULT '',
  `url` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `rid` (`rid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8");
E_D("replace into `ims_cover_reply` values('1','1','0','7',0x6d63,'',0xe4b8aae4babae4b8ade5bf83e585a5e58fa3e8aebee7bdae,'','',0x2e2f696e6465782e7068703f633d6d6326613d686f6d6526693d31);");
E_D("replace into `ims_cover_reply` values('2','1','1','8',0x73697465,'',0xe5beaee8b59ee59ba2e9989fe585a5e58fa3e8aebee7bdae,'','',0x2e2f696e6465782e7068703f633d686f6d6526693d3126743d31);");

require("../../inc/footer.php");
?>