<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_takephotoa_fans`;");
E_C("CREATE TABLE `ims_ewei_takephotoa_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `openid` varchar(255) DEFAULT '' COMMENT '用户openid',
  `nickname` varchar(255) DEFAULT '' COMMENT '用户昵称',
  `headimgurl` varchar(255) DEFAULT '' COMMENT '用户头像',
  `score` decimal(10,2) DEFAULT '0.00' COMMENT '平均',
  `img` varchar(255) DEFAULT '' COMMENT '成绩截图',
  `createtime` int(10) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>