<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_ewei_comeon_fans`;");
E_C("CREATE TABLE `ims_ewei_comeon_fans` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rid` int(11) DEFAULT '0',
  `from_user` varchar(100) DEFAULT '' COMMENT '用户ID',
  `mobile` varchar(20) DEFAULT '' COMMENT '登记信息(手机等)',
  `points` decimal(10,2) DEFAULT '0.00' COMMENT '点数',
  `helps` int(11) DEFAULT '0' COMMENT '被助力次数',
  `createtime` int(10) DEFAULT '0',
  `status` int(10) DEFAULT '0',
  `awardid` int(10) DEFAULT '0',
  `awardtime` int(10) DEFAULT '0',
  `finger` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>