<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_duanwu_fans`;");
E_C("CREATE TABLE `ims_duanwu_fans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `openid` varchar(100) NOT NULL,
  `content` varchar(500) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `yingcang` tinyint(1) NOT NULL DEFAULT '2' COMMENT '是否隐藏',
  `uid` int(10) NOT NULL DEFAULT '1' COMMENT '是否显示',
  `liketimes` int(10) NOT NULL DEFAULT '0' COMMENT '点赞次数',
  `sharetimes` int(10) NOT NULL DEFAULT '0' COMMENT '分享次数',
  `createtime` int(12) NOT NULL DEFAULT '1' COMMENT '是否显示',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>