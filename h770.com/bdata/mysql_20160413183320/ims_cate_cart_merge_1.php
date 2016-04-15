<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_cart_merge`;");
E_C("CREATE TABLE `ims_cate_cart_merge` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `ordernum` varchar(20) DEFAULT NULL COMMENT '订单号，下单以后生成',
  `batch` int(10) unsigned DEFAULT '0' COMMENT '订单批次',
  `userid` int(10) unsigned DEFAULT '0' COMMENT '用户ID',
  `cartnum` int(10) unsigned DEFAULT '0',
  `cartarr` longtext,
  `cache` longtext COMMENT '快照',
  `setting` longtext,
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 订单（合并数据）'");

require("../../inc/footer.php");
?>