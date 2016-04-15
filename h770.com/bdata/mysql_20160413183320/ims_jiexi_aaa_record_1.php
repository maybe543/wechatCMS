<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_jiexi_aaa_record`;");
E_C("CREATE TABLE `ims_jiexi_aaa_record` (
  `record_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uniacid` int(10) unsigned NOT NULL,
  `apply_uid` int(10) unsigned NOT NULL,
  `upgrade` tinyint(3) unsigned NOT NULL,
  `approval_uid` int(10) unsigned NOT NULL,
  `manager_uid` int(10) unsigned NOT NULL,
  `packet` tinyint(3) unsigned NOT NULL,
  `a_flag` tinyint(3) unsigned NOT NULL,
  `m_flag` tinyint(3) unsigned NOT NULL,
  `apply_time` int(10) unsigned NOT NULL,
  `approval_time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`record_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>