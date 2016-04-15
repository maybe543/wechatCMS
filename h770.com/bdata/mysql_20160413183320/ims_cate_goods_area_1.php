<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_goods_area`;");
E_C("CREATE TABLE `ims_cate_goods_area` (
  `goodsid` int(10) unsigned NOT NULL DEFAULT '0',
  `area_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`goodsid`,`area_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微餐饮 - 扩展区域'");

require("../../inc/footer.php");
?>