<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_shop_diyform_temp`;");
E_C("CREATE TABLE `ims_ewei_shop_diyform_temp` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL DEFAULT '0',
  `typeid` int(11) DEFAULT '0',
  `cid` int(11) NOT NULL DEFAULT '0' COMMENT '关联id',
  `diyformfields` text,
  `fields` text NOT NULL COMMENT '字符集',
  `openid` varchar(255) NOT NULL DEFAULT '' COMMENT '使用者openid',
  `type` tinyint(1) DEFAULT '0' COMMENT '类型',
  `diyformid` int(11) DEFAULT '0',
  `diyformdata` text,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_cid` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>