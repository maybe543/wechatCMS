<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_stonefish_bigwheel_prize`;");
E_C("CREATE TABLE `ims_stonefish_bigwheel_prize` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '公众号ID',
  `prizetype` varchar(20) NOT NULL COMMENT '奖品类型真实虚拟积分等',
  `prizevalue` int(10) NOT NULL COMMENT '积分或实物以及虚拟价值',
  `prizerating` varchar(50) NOT NULL COMMENT '奖品等级',
  `prizename` varchar(50) NOT NULL COMMENT '奖品名称',
  `prizepic` varchar(255) NOT NULL COMMENT '奖品图片',
  `prizetotal` int(10) NOT NULL COMMENT '奖品数量',
  `prizedraw` int(10) NOT NULL COMMENT '中奖数量',
  `prizeren` int(10) NOT NULL COMMENT '每人最多中奖',
  `prizeday` int(10) NOT NULL COMMENT '每天最多发奖',
  `probalilty` varchar(5) NOT NULL COMMENT '中奖概率%',
  `description` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要帮助人数',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`),
  KEY `indx_uniacid` (`uniacid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>