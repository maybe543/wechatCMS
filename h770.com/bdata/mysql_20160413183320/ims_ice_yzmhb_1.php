<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ice_yzmhb`;");
E_C("CREATE TABLE `ims_ice_yzmhb` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(10) unsigned NOT NULL DEFAULT '1',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '1',
  `rid` int(10) unsigned NOT NULL DEFAULT '1',
  `title` varchar(16) NOT NULL DEFAULT '1',
  `content` int(4) unsigned NOT NULL DEFAULT '1',
  `time` varchar(16) NOT NULL DEFAULT '1',
  `stime` varchar(16) NOT NULL DEFAULT '1',
  `etime` varchar(16) NOT NULL DEFAULT '1',
  `nick_name` varchar(32) DEFAULT '',
  `send_name` varchar(32) DEFAULT '',
  `min_value` int(8) unsigned NOT NULL DEFAULT '0',
  `max_value` int(8) unsigned NOT NULL DEFAULT '0',
  `total_num` int(4) unsigned NOT NULL DEFAULT '1',
  `wishing` varchar(128) DEFAULT '',
  `act_name` varchar(32) DEFAULT '',
  `remark` varchar(128) DEFAULT '',
  `logo_imgurl` varchar(128) DEFAULT '',
  `share_content` varchar(256) DEFAULT '',
  `share_url` varchar(128) DEFAULT '',
  `share_imgurl` varchar(128) DEFAULT '',
  `status` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>