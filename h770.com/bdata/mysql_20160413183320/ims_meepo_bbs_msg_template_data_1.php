<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_meepo_bbs_msg_template_data`;");
E_C("CREATE TABLE `ims_meepo_bbs_msg_template_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(52) NOT NULL DEFAULT '',
  `set` text NOT NULL,
  `uniacid` int(11) unsigned NOT NULL DEFAULT '0',
  `tpl_id` varchar(124) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>