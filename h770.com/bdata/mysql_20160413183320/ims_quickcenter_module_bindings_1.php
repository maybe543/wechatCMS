<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_quickcenter_module_bindings`;");
E_C("CREATE TABLE `ims_quickcenter_module_bindings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) NOT NULL,
  `groupid` varchar(50) NOT NULL COMMENT '分组名称',
  `identifier` varchar(50) NOT NULL COMMENT '菜单标示符',
  `pidentifier` varchar(50) NOT NULL COMMENT '上级菜单标示符',
  `displayorder` int(11) NOT NULL COMMENT '显示顺序',
  `title` varchar(50) NOT NULL,
  `url` varchar(1000) NOT NULL,
  `thumb` varchar(1000) NOT NULL,
  `module` varchar(1000) NOT NULL,
  `do` varchar(100) NOT NULL COMMENT '打开按钮的跳转链接',
  `callback` varchar(10240) NOT NULL,
  `rich_callback_enable` int(11) NOT NULL DEFAULT '0',
  `enable` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>