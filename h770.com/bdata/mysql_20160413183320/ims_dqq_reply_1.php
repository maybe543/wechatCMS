<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_dqq_reply`;");
E_C("CREATE TABLE `ims_dqq_reply` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL COMMENT '规则ID',
  `picture` varchar(100) NOT NULL COMMENT '活动图片',
  `description` varchar(100) NOT NULL COMMENT '活动描述',
  `rule` varchar(1000) NOT NULL COMMENT '规则',
  `periodlottery` smallint(10) unsigned NOT NULL DEFAULT '1' COMMENT '0为无周期',
  `maxlottery` tinyint(3) unsigned NOT NULL COMMENT '最大抽奖数',
  `default_tips` varchar(100) NOT NULL COMMENT '默认提示信息',
  `hitcredit` int(11) NOT NULL COMMENT '中奖奖励积分',
  `misscredit` int(11) NOT NULL COMMENT '未中奖奖励积分',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>