<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_account`;");
E_C("CREATE TABLE `ims_cate_account` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned DEFAULT '0',
  `cartid` int(10) unsigned DEFAULT '0',
  `goodsid` int(10) unsigned DEFAULT '0',
  `goodstitle` varchar(255) DEFAULT NULL COMMENT '商品标题（可空）',
  `content` varchar(255) DEFAULT NULL COMMENT '说明',
  `totalprice` decimal(10,2) DEFAULT '0.00' COMMENT '金额',
  `indate` bigint(18) unsigned DEFAULT '0',
  `indate_cn` varchar(20) DEFAULT NULL,
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微餐饮 - 会员流水账'");

require("../../inc/footer.php");
?>