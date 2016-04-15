<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_buyok`;");
E_C("CREATE TABLE `ims_cate_buyok` (
  `userid` int(10) unsigned DEFAULT '0',
  `setting` text,
  `rid` int(10) unsigned DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微餐饮 - 支付临时数据保存'");

require("../../inc/footer.php");
?>