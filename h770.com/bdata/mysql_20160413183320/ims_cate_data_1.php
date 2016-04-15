<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_data`;");
E_C("CREATE TABLE `ims_cate_data` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) DEFAULT NULL COMMENT '类型：日、周、月',
  `indate` bigint(18) unsigned DEFAULT '0',
  `update` bigint(18) unsigned DEFAULT '0',
  `cndate` varchar(20) DEFAULT '0',
  `num` decimal(10,2) unsigned DEFAULT '0.00' COMMENT '支付总金额',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC COMMENT='微餐饮 - 成交金额统计'");

require("../../inc/footer.php");
?>