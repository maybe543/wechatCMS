<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_chailihe_prizemika`;");
E_C("CREATE TABLE `ims_stonefish_chailihe_prizemika` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `prizeid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '奖品ID',
  `from_user` varchar(50) NOT NULL DEFAULT '' COMMENT '用户openid',
  `mikacodesn` varchar(100) NOT NULL COMMENT '密卡字符串',
  `virtual_value` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `actionurl` varchar(200) NOT NULL COMMENT '激活地址',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否领取1为领取过',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>