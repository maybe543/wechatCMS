<?php
define('InEmpireBakData',TRUE);
require("../../inc/header.php");

/*
		SoftName : EmpireBak Version 5.0
		Author   : wm_chief
		Copyright: Powered by www.phome.net
*/

DoSetDbChar('utf8');
E_D("DROP TABLE IF EXISTS `ims_hc_hunxiao_templatenews`;");
E_C("CREATE TABLE `ims_hc_hunxiao_templatenews` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `weid` int(10) NOT NULL,
  `template_id` varchar(100) DEFAULT NULL,
  `sendGoodsSend` varchar(100) DEFAULT NULL,
  `sendCommWarm` varchar(100) DEFAULT NULL,
  `sendCheckChange` varchar(100) DEFAULT NULL,
  `sendApplyMoneyBack` varchar(100) DEFAULT NULL,
  `sendMoneyBack` varchar(100) DEFAULT NULL,
  `createtime` int(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8");

require("../../inc/footer.php");
?>