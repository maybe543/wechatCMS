<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hotel2_set`;");
E_C("CREATE TABLE `ims_hotel2_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `user` tinyint(1) DEFAULT '0' COMMENT '用户类型0微信用户1独立用户',
  `reg` tinyint(1) DEFAULT '0' COMMENT '是否允许注册0禁止注册1允许注册',
  `bind` tinyint(1) DEFAULT '0' COMMENT '是否绑定',
  `regcontent` text COMMENT '注册提示',
  `ordertype` tinyint(1) DEFAULT '0' COMMENT '预定类型0电话预定1电话和网络预订',
  `is_unify` tinyint(1) DEFAULT '0' COMMENT '0使用各分店电话,1使用统一电话',
  `tel` varchar(20) DEFAULT '' COMMENT '统一电话',
  `email` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒接受邮箱',
  `mobile` varchar(32) NOT NULL DEFAULT '' COMMENT '提醒接受手机',
  `paytype1` tinyint(1) DEFAULT '0',
  `paytype2` tinyint(1) DEFAULT '0',
  `paytype3` tinyint(1) DEFAULT '0',
  `version` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0单酒店版1多酒店版',
  `location_p` varchar(50) DEFAULT '',
  `location_c` varchar(50) DEFAULT '',
  `location_a` varchar(50) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC");

require("../../inc/footer.php");
?>