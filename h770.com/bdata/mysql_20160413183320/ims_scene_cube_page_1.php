<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_scene_cube_page`;");
E_C("CREATE TABLE `ims_scene_cube_page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) unsigned NOT NULL,
  `list_id` varchar(50) NOT NULL DEFAULT '',
  `listorder` int(11) NOT NULL,
  `m_type` tinyint(4) NOT NULL,
  `thumb` varchar(300) NOT NULL,
  `param` text NOT NULL,
  `create_time` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>