<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_goods_sort`;");
E_C("CREATE TABLE `ims_cate_goods_sort` (
  `goodsid` int(10) unsigned NOT NULL DEFAULT '0',
  `sort_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `rid` int(10) unsigned DEFAULT '0',
  PRIMARY KEY (`goodsid`,`sort_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微餐饮 - 扩展分类'");

require("../../inc/footer.php");
?>