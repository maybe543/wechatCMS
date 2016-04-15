<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_cate_express`;");
E_C("CREATE TABLE `ims_cate_express` (
  `md5` varchar(32) DEFAULT NULL,
  `text` text,
  `indate` bigint(18) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED COMMENT='微餐饮 - 物流信息'");

require("../../inc/footer.php");
?>