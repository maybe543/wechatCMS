<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_eso_share_list`;");
E_C("CREATE TABLE `ims_eso_share_list` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则id',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `userid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户uid',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '姓名',
  `tel` varchar(50) NOT NULL DEFAULT '' COMMENT '电话',
  `eso_sharenum` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分享量',
  `eso_sharetime` int(10) unsigned NOT NULL COMMENT '最后分享时间',
  `status` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '是否禁止',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分享达人'");

require("../../inc/footer.php");
?>