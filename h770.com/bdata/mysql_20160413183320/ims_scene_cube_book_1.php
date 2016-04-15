<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_scene_cube_book`;");
E_C("CREATE TABLE `ims_scene_cube_book` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL DEFAULT '0',
  `list_id` int(11) NOT NULL,
  `from_user` varchar(50) NOT NULL DEFAULT '',
  `str1` varchar(200) NOT NULL DEFAULT '',
  `str2` varchar(200) NOT NULL DEFAULT '',
  `str3` varchar(200) NOT NULL DEFAULT '',
  `status` tinyint(4) NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>