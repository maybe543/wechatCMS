<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_scene_cube_comment`;");
E_C("CREATE TABLE `ims_scene_cube_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `list_id` int(11) NOT NULL,
  `from` varchar(10) NOT NULL,
  `content` varchar(255) NOT NULL,
  `create_time` int(10) NOT NULL,
  `status` tinyint(1) NOT NULL,
  `from_user` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>