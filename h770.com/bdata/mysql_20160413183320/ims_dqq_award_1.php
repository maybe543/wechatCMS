<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_dqq_award`;");
E_C("CREATE TABLE `ims_dqq_award` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `title` varchar(50) NOT NULL COMMENT '奖品名称',
  `total` int(11) NOT NULL COMMENT '数量',
  `probalilty` varchar(5) NOT NULL COMMENT '概率单位%',
  `description` varchar(100) NOT NULL DEFAULT '' COMMENT '描述',
  `inkind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物',
  `get_jf` int(11) NOT NULL COMMENT '获取的积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>