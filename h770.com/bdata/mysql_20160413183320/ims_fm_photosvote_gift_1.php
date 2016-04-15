<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_fm_photosvote_gift`;");
E_C("CREATE TABLE `ims_fm_photosvote_gift` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `rid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '规则id',
  `uniacid` int(10) unsigned NOT NULL,
  `title` varchar(50) NOT NULL COMMENT '奖品名称',
  `total` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '数量',
  `total_winning` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '中奖数量',
  `lingjiangtype` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '奖品库存减少方式0为有资格1为提交2为兑奖',
  `description` text NOT NULL COMMENT '描述',
  `inkind` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否是实物',
  `activation_code` varchar(50) NOT NULL COMMENT '激活码',
  `activation_url` varchar(215) NOT NULL COMMENT '激活地址',
  `break` int(3) unsigned NOT NULL DEFAULT '0' COMMENT '需要朋友人数',
  `awardpic` varchar(200) NOT NULL COMMENT '奖品图片',
  `awardpass` varchar(20) NOT NULL COMMENT '兑奖密码',
  PRIMARY KEY (`id`),
  KEY `indx_rid` (`rid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>